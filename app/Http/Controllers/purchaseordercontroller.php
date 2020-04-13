<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Gate;
use Input;
use View;

use DocuSign\eSign as docusignclient;

use App\Attachment;
use App\City;
use App\Company;
use App\Country;
use App\Currency;
use App\Deliverytype;
use App\Freightexpense;
use App\Incoterm;
use App\Materialgroup;
use App\Paymentterm;
use App\Permission;
use App\Purchaseorder;
use App\Purchaseorderitem;
use App\Range;
use App\Role;
use App\Settings;
use App\Shippingaddress;
use App\Unit;
use App\Vendor;
use App\Shippinginquiry;

use App\Jobs\ProcessBizzmoPOreject;
use App\Jobs\ProcessBuyerPO;
use App\Jobs\ProcessBuyerPOreject;
use App\Jobs\Processpocredit;
use App\Jobs\ProcessBizzmoPO;
use App\Jobs\Processdelivery;
use App\Jobs\ProcessDeliveryCancel;
use App\Jobs\Processdeliverysignature;
use App\Jobs\ProcessPOoutput;
use App\Jobs\ProcessPOconfirmation;
use App\Brand;
use App\Helpers\RightSignatureHelper;
use App\Helpers\AWSsmsHelper;
use App\Helpers\TwilioHelper;
use App\Attachmenttype;
use App\Companytype;
use OwenIt\Auditing\Models\Audit;
use App\Status;

class purchaseordercontroller extends Controller
{
	public function view($id) {
		$purchaseorder = Purchaseorder::with('purchaseorderitems', 'purchaseorderitems.audits', 'company', 'vendor', 'status', 'audits', 'attachments','shippinginquiry')->find($id);
		$purchaseorder->date = date("j/n/Y",strtotime($purchaseorder->date));
		$purchaseorder->pickupbydate = date("j/n/Y", strtotime($purchaseorder->pickupbydate));
		$purchaseorder->deliverbydate = date("j/n/Y", strtotime($purchaseorder->deliverbydate));
		$changes = $purchaseorder->previousversion();
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		// $shippinginquiries = Shippinginquiry::find('purchaseorder_id', $purchaseorder->id);
// 		$hasInq = false;
// if($shippinginquiries->count>0){
// 	$hasInq = true;
// }
		return view('purchaseorders.manage')->with('title', 'View PO')->with('mode', 'v')
		->with('changes', $changes)
		->with('brands',$brands->pluck('name', 'id'))
		// ->with('shippinginquiries',$shippinginquiries)
		->with('purchaseorder', $purchaseorder);
	}
	
	public function changes($id) {
		$purchaseorder = Purchaseorder::with('purchaseorderitems', 'purchaseorderitems.audits', 'company', 'vendor', 'status', 'audits', 'attachments')->find($id);		
		$purchaseorder->date = date("j/n/Y",strtotime($purchaseorder->date));
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		
		return view('purchaseorders.changes')
		->with('title', 'PO changes')
		->with('brands',$brands->pluck('name', 'id'))
		->with('purchaseorder', $purchaseorder);
	}
	
	public function approve($id) {
		$purchaseorder = Purchaseorder::find($id);
		if ($purchaseorder->status_id  == 15) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.poapprovemsg'));		
		} elseif ($purchaseorder->status_id  == 5 || $purchaseorder->status_id  == 6 || $purchaseorder->status_id  == 14) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.porejectedmsg'));		
		}
		$purchaseorder->date = date("j/n/Y",strtotime($purchaseorder->date));
		return view('purchaseorders.manage')->with('title', __('messages.poapprove'))->with('mode', 'a')
		->with('changes', [])
		->with('purchaseorder', $purchaseorder);
	}
	
	public function vresend($id) {
		$purchaseorder = Purchaseorder::find($id);
		$purchaseorder->release_otp = null;
		$purchaseorder->save();		
		return $this->approvec($id);
	}
	
	public function approvec($id) {
		$purchaseorder = Purchaseorder::find($id);
		if ($purchaseorder->status_id  == 15) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.poapprovemsg'));		
		} elseif ($purchaseorder->status_id  == 5 || $purchaseorder->status_id  == 6 || $purchaseorder->status_id  == 14) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.porejectedmsg'));		
		}
		
		$companyName = env('COMPANY_NAME', 'Bizzmo');
		$AWSsmsHelper = new AWSsmsHelper();
		$phone = Auth::user()->phone()->phone;
		if ($purchaseorder->release_otp == null) {			
			$pin = $AWSsmsHelper->generatePIN();
			$purchaseorder->release_otp = $pin;
			$purchaseorder->save();		
			$message = "$pin is your $companyName PO verification code";							
			if (env('SMS_PROVIDER') == 'aws') {
				$AWSsmsHelper->sendSMS($phone, $message);
			} else {
				$TwilioHelper = new TwilioHelper();
				$TwilioHelper->sendSMS($phone, $message);
			}
		}
		return view('purchaseorders.po_otp', [
			'title' => 'Purchase order verification',
			'po_id' => $purchaseorder->id,
			'usertype' => 'supplier',
			'phone' => $AWSsmsHelper->hiddenphone($phone)
		]);
	}
	
	public function verifyvrelease(Request $request, $id) {
		$purchaseorder = Purchaseorder::find($id);
		$otp = $purchaseorder->release_otp;
		$rules = [
				'verificationCode' => 'required|in:' . $otp
				];
				
		$messages = [
			'verificationCode.required' => 'Please provide a validation code',
			'verificationCode.in' => 'Validation code is incorrect'
		];
		$this->validate($request, $rules, $messages);
		
		if (Input::get('verificationCode') == $otp) {
			$deliverynumber = DB::table('purchaseorders')->max('delivery');	
			$purchaseorder->delivery = $deliverynumber + 1;
			$purchaseorder->deliverydate = date('Y-m-d');			
			//buyer invoice number
			$setting = Settings::find(Settings::BUYER_INVOICE);
			$inv_no = $setting->buyerInvoiceNumber();
			$purchaseorder->binvoice = $inv_no;			
			//supplier invoice number
			$purchaseorder->binvoicedate = date('Y-m-d');
			$sinvoicenumber = DB::table('purchaseorders')->where('vendor_id', $purchaseorder->vendor_id)->max('sinvoice');	
			$purchaseorder->sinvoice = $sinvoicenumber + 1;
			$purchaseorder->sinvoicedate = date('Y-m-d');
			
			$purchaseorder->status_id = 15;
			$purchaseorder->accepted_by = Auth::user()->id;
			$purchaseorder->accepted_at = $date=date_create(date('Y-m-d H:i:s'));;
			$purchaseorder->release_otp = null;
			$purchaseorder->save();
						
			Processdelivery::dispatch($purchaseorder);
			ProcessPOconfirmation::dispatch($purchaseorder);
			return redirect('/purchaseorders/vview/' . $purchaseorder->id);
		}
	}
	
	public function reject($id) {
		$purchaseorder = Purchaseorder::find($id);
		if ($purchaseorder->status_id  == 13) {
			return view('message')->with('title', 'Reject PO')->with('message', 'This PO is approved. Cannot reject');		
		} elseif ($purchaseorder->status_id  == 5 || $purchaseorder->status_id  == 6) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.porejectedmsg'));		
		}
		$purchaseorder->date = date("j/n/Y",strtotime($purchaseorder->date));
		return view('purchaseorders.manage')->with('title', 'Reject PO')->with('mode', 'r')->with('purchaseorder', $purchaseorder);
	}
	
	public function rejectc($id) {
		$purchaseorder = Purchaseorder::find($id);
		if ($purchaseorder->status_id  == 13) {
			return view('message')->with('title', 'Reject PO')->with('message', 'This PO is approved. Cannot reject');		
		} elseif ($purchaseorder->status_id  == 5 || $purchaseorder->status_id  == 6) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.porejectedmsg'));		
		} elseif ($purchaseorder->status_id  == 15) {
			ProcessDeliveryCancel::dispatch($purchaseorder);
		}
		$purchaseorder->status_id = 5;
		$purchaseorder->save();		
		ProcessBuyerPOreject::dispatch($purchaseorder);
		ProcessBizzmoPOreject::dispatch($purchaseorder);
		return redirect('/purchaseorders/vview/' . $purchaseorder->id);
	}
	
	public function creject($id) {
		$purchaseorder = Purchaseorder::find($id);
		if (!$purchaseorder->canCancel())
			return view('message')->with('title', 'Reject PO')->with('message', 'This PO is signed. Cannot reject');

		$purchaseorder->date = date("j/n/Y",strtotime($purchaseorder->date));
		return view('purchaseorders.manage')->with('title', 'Reject PO')->with('mode', 'c')->with('purchaseorder', $purchaseorder);
	}
	
	public function crejectc($id) {
		$purchaseorder = Purchaseorder::find($id);
		if (!$purchaseorder->canCancel())
			return view('message')->with('title', 'Reject PO')->with('message', 'This PO is signed. Cannot reject');

		$doc = Attachment::where('attachable_id', $purchaseorder->id)
			->where('attachable_type', 'purchaseorder')
			->where('attachmenttype_id', Attachmenttype::SIGNED_DELIVERY_DOCUMENT)
			->first();

		// if(isset($doc)) {			
			// $documentId = $doc->document;
			// switch($provider) {
				// case 'docusign':
				
				// case 'rightsignature':
					// $rightSignature = new RightSignatureHelper();
					// $rightSignature->voidDocument($documentId);
			// }			
		// }
		$orgstatus = $purchaseorder->status_id;		
		$purchaseorder->status_id = 6;
		$purchaseorder->save();
		ProcessBuyerPOreject::dispatch($purchaseorder);
		if ($orgstatus == Status::PO_PENDING_SUPPLIER_APPROVAL || $purchaseorder->accepted_by != null) {
			ProcessBizzmoPOreject::dispatch($purchaseorder);
		}
		return redirect('/purchaseorders/view/' . $purchaseorder->id);
	}

	public function creditReject($id) {
		$purchaseorder = Purchaseorder::find($id);
		if ($purchaseorder->status_id  == 13) {
			return view('message')->with('title', 'Reject PO')->with('message', 'This PO is approved. Cannot reject');		
		} elseif ($purchaseorder->status_id  == 5 || $purchaseorder->status_id  == 6) {
			return view('message')->with('title', __('messages.poapprove'))->with('message', __('messages.porejectedmsg'));		
		}
		$purchaseorder->status_id = 14;
		$purchaseorder->save();
		// ProcessBuyerPOreject::dispatch($purchaseorder);
		// if ($purchaseorder->accepted_by != null) {
			// ProcessBizzmoPOreject::dispatch($purchaseorder);
		// }
		// Dispatch the event of update PO status
		try {
			broadcast(new \App\Events\PoStatusUpdate($purchaseorder));
		} catch(\Exception $e) {
			\Log::warning("Fail to fire the event");
		}

		return redirect('/purchaseorders/mview/' . $purchaseorder->id);
	}

	public function delete($id) {
		$purchaseorder = Purchaseorder::find($id);
		if (!$purchaseorder->canDelete()) {
			return view('message')->with('title', 'Delete PO')->with('message', 'This PO is submitted. Cannot delete');		
		}

		foreach ($purchaseorder->purchaseorderitems as $item) {
			foreach ($item->audits as $audit) {
				$audit->delete();
			}
			$item->delete();
		}

		foreach ($purchaseorder->audits as $audit) {
			$audit->delete();
		}
		$purchaseorder->poaddresses()->delete();
		$purchaseorder->delete();

		return redirect('/purchaseorders/');
	}
	
	public function orderrelease($id) {
		$purchaseorder = Purchaseorder::find($id);
		if ($purchaseorder->status_id  != 13) {
			return view('message')->with('title', 'Release PO')->with('message', 'The PO status is ' . $purchaseorder->status->name . '. Cannot release');		
		}
		return view('purchaseorders.manage')->with('title', 'Release PO')->with('mode', 'l')->with('purchaseorder', $purchaseorder);
	}
	
	public function resend($id) {
		$purchaseorder = Purchaseorder::find($id);
		$purchaseorder->release_otp = null;
		$purchaseorder->save();		
		return $this->orderreleasec($id);
	}
	
	public function orderreleasec($id) {
		$purchaseorder = Purchaseorder::find($id);
		$companyName = env('COMPANY_NAME', 'Bizzmo');
		$AWSsmsHelper = new AWSsmsHelper();
		$phone = Auth::user()->phone()->phone;
		if ($purchaseorder->release_otp == null) {			
			$pin = $AWSsmsHelper->generatePIN();
			$purchaseorder->release_otp = $pin;
			$purchaseorder->save();		
			$message = "$pin is your $companyName PO verification code";							
			if (env('SMS_PROVIDER') == 'aws') {
				$AWSsmsHelper->sendSMS($phone, $message);
			} else {
				$TwilioHelper = new TwilioHelper();
				$TwilioHelper->sendSMS($phone, $message);
			}
		}
		return view('purchaseorders.po_otp', [
			'title' => 'Purchase order verification',
			'po_id' => $purchaseorder->id,
			'usertype' => 'buyer',
			'phone' => $AWSsmsHelper->hiddenphone($phone)
		]);
	}
	
	public function verifyrelease(Request $request, $id) {
		$purchaseorder = Purchaseorder::find($id);
		$otp = $purchaseorder->release_otp;
		$rules = [
				'verificationCode' => 'required|in:' . $otp
				];
				
		$messages = [
			'verificationCode.required' => 'Please provide a validation code',
			'verificationCode.in' => 'Validation code is incorrect'
		];
		$this->validate($request, $rules, $messages);
		
		if (Input::get('verificationCode') == $otp) {
			$purchaseorder->status_id = 4;
			$purchaseorder->release_otp = null;
			$purchaseorder->released_by = Auth::user()->id;
			$purchaseorder->released_at = $date=date_create(date('Y-m-d H:i:s'));;
			$purchaseorder->save();
			// BILL_TO, SHIP_TO, PAYER, SOLD_TO Addersses			
			Processpocredit::dispatch($purchaseorder);
			ProcessBuyerPO::dispatch($purchaseorder);
			return redirect('/purchaseorders/view/' . $purchaseorder->id);
		}
		
	}
	
	public function resubmitc($id) {
		$purchaseorder = Purchaseorder::find($id);
		$purchaseorder->status_id = 4;
		$purchaseorder->save();
		Processpocredit::dispatch($purchaseorder);
		//ProcessBuyerPO::dispatch($purchaseorder);
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		return view('purchaseorders.manage')->with('title', 'View PO')->with('mode', 'v')->with('brands',$brands->pluck('name', 'id'))->with('purchaseorder', $purchaseorder);
	}
	
	public function creditrelease($id) {
		$purchaseorder = Purchaseorder::find($id);
		ProcessBizzmoPO::dispatch($purchaseorder);
		$purchaseorder->status_id = 7;
		$purchaseorder->approver_id = Auth::user()->id;
		$purchaseorder->approved_at = $date=date_create(date('Y-m-d H:i:s'));		
		$purchaseorder->save();

		// Dispatch the event of update PO status
		try {
			broadcast(new \App\Events\PoStatusUpdate($purchaseorder));
		} catch(\Exception $e) {
			\Log::warning("Fail to fire the event");
		}

		return redirect('/purchaseorders/mview/' . $purchaseorder->id);
	}
	
	public function companies()
	{
		// $companies = Auth::user()->companypermissions(['po_cr']);
		// $activecompanies = $companies->where('active', 1);
		// return view::make('purchaseorders.list')->with('title', 'Choose company')
		// ->with('companies', $activecompanies);
		$company = Auth::user()->getBuyerCompany();
		if(!$company)
			return view('message',[
				'title' => 'Create PO',
				'message' => 'Cannot create PO',
				'description' =>  __('messages.noBuyerCompany', ['context' => 'purchase order']),
				'error' => true,
			]);

		if (!$company->active) {
			if(!$company->confirmed)
				$msg = __('messages.compBuyerNotConfirmed');

			$msg = __('messages.compBuyerNotActive');
			return view('message',[
				'title' => 'Create PO',
				'message' => 'Cannot create PO',
				'description' => $msg,
				'error' => true,
			]);
		}

		return redirect('/purchaseorders/create/' . $company->id);
	}
	
	public function create($id)
	{
		//DB::enableQueryLog();
		$company = Company::with('shippingaddresses', 'shippingaddresses.city')->find($id);		
		if (!$company->customer_signed)
			//return view('message')->with('title', 'Create credit request')->with('message', 'This company\'s contract is not signed. Cannot create Purchase Order.');
			return view('message',[
					'title' => 'Create purchase order',
					'message' => 'Cannot create a purchase order',
					'description' =>'Your company\'s contract is not signed yet.',
					'error' => true
				]);

		$vendors = $company->vendors->whereIn('companytype_id', [2, 3]);
		// if ($vendors->count() == 0 ) {
		// 	return view('message')->with('title', 'Create PO')->with('message', __('messages.nosupppo'));		
		// }
		$incoterms = Incoterm::where('active', 1)->orderBy('name')->get();
		$units = Unit::where('active', 1)->orderBy('name')->get();
		$currencies = Currency::where('active', 1)->orderBy('name')->get();
		//DB::enableQueryLog();
		$paymentterms = $company->paymentterms()->where('company_paymentterm.active', 1)->orderBy('name')->get();
		$deliverytypes = Deliverytype::where('id', 1)->orderBy('name')->get();
		//var_dump( DB::getQueryLog());
		//die;
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
		$countriesList = $countries->pluck('countryname', 'id');
		$countriesList[0] = 'Other';
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		$timelist = Range::where('active', 1)->where('rangetype', 'hours')->orderBy('id')->get();
		if ($company->getSortedAddresses()->first()->vat) {
			$vat = $company->vat;
		} else {
			$vat = 0;
		}
		return view::make('purchaseorders.manage')
		->with('incoterms',$incoterms->pluck('name', 'id'))->with('currencies',$currencies->pluck('name', 'id'))
		->with('deliverytypes',[])
		->with('freightexpenses',[])
		->with('paymentterms',$paymentterms->pluck('name', 'id'))->with('buyup', $paymentterms->first()->pivot->buyup)
		->with('shippingaddresses',$company->getSortedAddresses())
		->with('pickupaddresses',[])
		->with('vat', $vat)
		->with('units',$units)->with('unitsarr',$units->pluck('abbreviation', 'id'))
		->with('countries',$countriesList)->with('cities',$cities->pluck('cityname', 'id'))
		->with('company',$company)->with('vendors',$vendors->pluck('companyname', 'id'))
		->with('brands',$brands->pluck('name', 'id'))
		->with('timelist', $timelist->pluck('name', 'id'))
		->with('isCreate', true);
	}
	
	public function manage($id = '')
    {	
		if ($id != '') {						
			$purchaseorder = Purchaseorder::find($id);
			//dd($purchaseorder->userrelation);
			if (($purchaseorder->userrelation == 1 && $purchaseorder->canchange) || ($purchaseorder->userrelation == 2 && $purchaseorder->status_id == 7)) {
			} else {
				return view('message')->with('title', 'Change PO')->with('message', 'This PO status is ' . $purchaseorder->status->name . '. Cannot change');		
			}
			$vendors = $purchaseorder->company->vendors;
			if ($vendors->count() == 0 ) {
				//return view('message')->with('title', 'Create PO')->with('message', __('messages.nosupppo'));		
			}
			$purchaseorder->date = date("j/n/Y",strtotime($purchaseorder->date));
			$purchaseorder->pickupbydate = date("j/n/Y", strtotime($purchaseorder->pickupbydate));
			$purchaseorder->deliverbydate = date("j/n/Y", strtotime($purchaseorder->deliverbydate));
			$incoterms = Incoterm::where('active', 1)->orderBy('name')->get();
			$units = Unit::where('active', 1)->orderBy('name')->get();
			$currencies = Currency::where('active', 1)->orderBy('name')->get();
			$paymentterms = $purchaseorder->company->paymentterms()->where('company_paymentterm.active', 1)->orderBy('name')->get();
			$deliverytypes = Deliverytype::where('active', 1)->orderBy('name')->get();
			$freightexpenses = Freightexpense::where('active', 1)->get();
			$countries = Country::where('active', 1)->orderBy('countryname')->get();
			$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
			$countriesList = $countries->pluck('countryname', 'id');
			$countriesList[0] = 'Other';
			$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
			$timelist = Range::where('active', 1)->where('rangetype', 'hours')->orderBy('id')->get();
			return view::make('purchaseorders.manage')->with('purchaseorder', $purchaseorder)
			->with('incoterms',$incoterms->pluck('name', 'id'))->with('currencies',$currencies->pluck('name', 'id'))
			->with('deliverytypes',$deliverytypes->pluck('name', 'id'))
			->with('freightexpenses',$freightexpenses->pluck('name', 'id'))
			->with('paymentterms',$paymentterms->pluck('name', 'id'))->with('buyup', $purchaseorder->buyup)
			->with('shippingaddresses', $purchaseorder->company->getSortedAddresses())
			->with('pickupaddresses', $purchaseorder->vendor->getSortedPickupAddresses())
			->with('units',$units)->with('unitsarr',$units->pluck('abbreviation', 'id'))
			->with('countries',$countriesList)->with('cities',$cities->pluck('cityname', 'id'))
			->with('vendors',$vendors->pluck('companyname', 'id'))
			->with('brands',$brands->pluck('name', 'id'))
			->with('timelist', $timelist->pluck('name', 'id'));
		} else {
			$companies = Auth::user()->companypermissions(['po_cr']);
			$activecompanies = $companies->where('active', 1)->where('creditlimit', '>', 0);
			if ($activecompanies->count() == 0 ) {
				return view('message')->with('title', 'Create PO')->with('message', 'There is no active companies. Cannot create PO.');		
			}
			return view::make('purchaseorders.manage')->with('materialgroups', $materialgroups)
			->with('incoterms',$incoterms->pluck('name', 'id'))->with('currencies',$currencies->pluck('name', 'id'))
			->with('units',$units)->with('unitsarr',$units->pluck('abbreviation', 'id'))
			->with('companies',$activecompanies->pluck('companyname', 'id'))->with('vendors',$vendors->pluck('companyname', 'id'))
			->with('brands',$brands->pluck('name', 'id'));
		}
    }
	
	public function savenew(Request $request, $id = 0) {
		// Check if company's contract is signed 
		$companyId = Input::get('company_id');
		$company = Company::where('id', $companyId)->first();
		if (isset($company) && !$company->customer_signed)
			return view('message')->with('title', 'Create credit request')->with('message', 'This company\'s contract is not signed. Cannot create Purchase Order.');
		
		$id  = $this->save($request);
		return redirect('/purchaseorders/view/' . $id);
	}
	
	public function save(Request $request, $id = 0) {
		$rules = [
			'date' => 'required|date_format:j/n/Y',
			'deliverbydate' => 'required|date_format:j/n/Y',
			'pickupbydate' => 'required|date_format:j/n/Y',
			'shippingaddress_id' => 'required|integer|min:1',
			'shipaddress' => 'required_if:shippingaddress_id,0|max:180',
			'po_box' => 'max:180',
			'city_id' => 'required_unless:country_id,0',
			'country_id' => 'required_if:shippingaddress_id,0',
			'otherCountry' => 'required_if:country_id,0',
			'otherCity' => 'required_if:country_id,0',
			'note' => 'max:180',
			'itemcount' => 'required|integer|min:1',
			'productname.*' => 'required_with:mpn.*,brand.*,price.*,quantity.*|max:180',
			'mpn.*' => 'required_with:productname.*,brand.*,price.*,quantity.*|max:60',
			'brand.*' => 'required_with:productname.*,mpn.*,price.*,quantity.*|max:60',
			'price.*' => 'sometimes|nullable|required_with:productname.*,mpn.*,brand.*,quantity.*|numeric',
			'quantity.*' => 'sometimes|nullable|required_with:productname.*,mpn.*,brand.*,price.*|numeric',
			'vendor_id' => 'required'
				];
				
		$messages = [
			'otherCountry.required_if' => 'Please provide the shipping country',
			'otherCity.required_if' => 'Please provide the shipping city',
			'vendor_id.required' => 'Please provide a supplier',
			'shippingaddress_id.required' => 'Please provide a shipping address',
			'note' => "Note length should not exceed 180 characters",
			'productname.*.max' => "Product description length should not exceed 180 characters",
			'productname.*' => "Please provide the product description",
			'mpn.*' => "Please provide the MPN",
			'brand.*' => "Please select the brand",
			'price.*' => "Please provide the price",
			'quantity.*' => "Please provide the quantity",
		];
		$this->validate($request, $rules, $messages);

		// Validate products count
		$productsCount = 0;
		$i = 0;
		foreach (Input::get('productname') as $item) {
			if ($item && Input::get('itemdel')[$i] != 1)
				$productsCount++;
			$i++;
		}

		if($productsCount < 1) {
			$firstProduct = 0;
			foreach (Input::get('itemdel') as $key => $item) {
				if ($item != 1) {
					$firstProduct = $key;
					break;
				}
			}

			$rules = [
				"productname.$firstProduct" => 'required|max:180',
				"mpn.$firstProduct" => 'required|max:60',
				"brand.$firstProduct" => 'required|max:60',
				"price.$firstProduct" => 'required|numeric',
				"quantity.$firstProduct" => 'required|numeric'
				];
			$messages = [
			'productname.*.max' => "Product description length should not exceed 180 characters",
			'productname.*' => "Please provide the product description",
			'mpn.*' => "Please provide the MPN",
			'brand.*' => "Please select the brand",
			'price.*' => "Please provide the price",
			'quantity.*' => "Please provide the quantity",
		];
			$this->validate($request, $rules, $messages);
		}
		
		if ($id == 0 ) {
			$number = DB::table('purchaseorders')->where('company_id', Input::get('company_id'))->max('number');				
			$date = date_create_from_format("d/m/Y",Input::get('date'));
			//sales order number
			$setting = Settings::find(Settings::SALES_ORDER);						
			$purchaseorder = new Purchaseorder;
			$purchaseorder->salesorder = $setting->SalesOrderNumber();
			$purchaseorder->number = $number + 1;						
			$vendornumber = DB::table('purchaseorders')->max('vendornumber');	
			$purchaseorder->vendornumber = $vendornumber + 1;
			$purchaseorder->company_id = Input::get('company_id');
			$purchaseorder->vendor_id = Input::get('vendor_id');
			$purchaseorder->date = $date->format('Y-m-d');			
			$purchaseorder->paymentterm_id = Input::get('paymentterm_id');			
			$purchaseorder->created_by = Auth::user()->id;			
			$purchaseorder->version = -1;
		} else {
			$purchaseorder = Purchaseorder::find($id);
			if ($purchaseorder->approver_id != null) {
				$purchaseorder->changed = true;
			}
		}
		$purchaseorder->status_id = 13;
		if (Input::get('shippingaddress_id') != 0) {
			$purchaseorder->shippingaddress_id = Input::get('shippingaddress_id');
		}
		$purchaseorder->pickupaddress_id = Input::get('pickupaddress_id');
		$purchaseorder->deliverytype_id = Input::get('deliverytype_id');
		if (Input::get('freightexpense_id')) {
			$purchaseorder->freightexpense_id = Input::get('freightexpense_id');
		} else {
			$purchaseorder->freightexpense_id = null;
		}
		$purchaseorder->vat = $this->getVAT(Input::get('shippingaddress_id'));
		$purchaseorder->note = Input::get('note');
		$purchaseorder->currency_id = Input::get('currency_id');
		$purchaseorder->incoterm_id = Input::get('incoterm_id');
		$purchaseorder->paymentterm_id = Input::get('paymentterm_id');
		$purchaseorder->buyup = Company::find($purchaseorder->company_id)->paymentterms()->where('paymentterm_id', Input::get('paymentterm_id'))->first()->pivot->buyup;
		$purchaseorder->deliverbydate = date_create_from_format("d/m/Y",Input::get('deliverbydate'));
		$purchaseorder->deliverbytime_id = Input::get('deliverbytime_id');
		$purchaseorder->pickupbydate = date_create_from_format("d/m/Y",Input::get('pickupbydate'));
		$purchaseorder->pickupbytime_id = Input::get('pickupbytime_id');
		$purchaseorder->updated_by = Auth::user()->id;
		if ($purchaseorder->userrelation == 1) {
			$purchaseorder->vendor_id = Input::get('vendor_id');
		}

		// Increament PO version
		$purchaseorder->version = $purchaseorder->version + 1;

		// Save
		$purchaseorder->save();

		//PO sub data
		$i = 0;
		if (Input::has('itemid')) {
			foreach (Input::get('itemid') as $item) {
				if (!Input::get('productname')[$i]) {
					$i++;
					continue;
				}

				$price = Input::get('price')[$i];
				if ($item == '' && Input::get('itemdel')[$i] == '') {					
					$purchaseorderitem  = new Purchaseorderitem(array('productname' => Input::get('productname')[$i], 'MPN' => Input::get('mpn')[$i], 'brand_id' => Input::get('brand')[$i], 'unit_id' => Input::get('unit_id')[$i], 'quantity'=> Input::get('quantity')[$i], 'price'=> $price));
					$purchaseorder->purchaseorderitems()->save($purchaseorderitem);
				} elseif ($item != '') {
					if (Input::get('itemdel')[$i] == '') {
						$purchaseorderitem = Purchaseorderitem::find($item);
						if ($purchaseorderitem->productname != Input::get('productname')[$i] ||
							$purchaseorderitem->mpn != Input::get('mpn')[$i] || 
							$purchaseorderitem->brand_id != Input::get('brand')[$i] || 
							$purchaseorderitem->unit_id != Input::get('unit_id')[$i] || 
							$purchaseorderitem->quantity != Input::get('quantity')[$i] || 
							$purchaseorderitem->price != $price
						) {
							$purchaseorderitem->productname = Input::get('productname')[$i];
							$purchaseorderitem->mpn = Input::get('mpn')[$i];
							$purchaseorderitem->brand_id = Input::get('brand')[$i];
							$purchaseorderitem->unit_id = Input::get('unit_id')[$i];
							$purchaseorderitem->quantity = Input::get('quantity')[$i];
							$purchaseorderitem->price = $price;
							$purchaseorderitem->save();
						}
					} else {
						$purchaseorderitem = Purchaseorderitem::destroy($item);
					}
				}
				$i++;
			}
		}
		//shipping address
		$shippingaddresses = Shippingaddress::where('address', $purchaseorder->shippingaddress)->where('company_id', $purchaseorder->company_id)->get();
		$purchaseorder->saveAddresses();
		ProcessPOoutput::dispatch($purchaseorder);
		if ($id == 0) {
			return $purchaseorder->id;
		} else {
			if ($purchaseorder->userrelation == 2) {
				ProcessBizzmoPO::dispatch($purchaseorder);
				return redirect('/purchaseorders/vview/' . $purchaseorder->id);
			} else {
				return redirect('/purchaseorders/view/' . $purchaseorder->id);
			}			
		}		
	}
	
	public function pending() {
		$pospendingcustomer = Auth::user()->pospendingcustomer();
		$pospendingvendor = Auth::user()->pospendingvendor();
		$pospendingcredit = Purchaseorder::where('id', 0)->get();
		if (Gate::allows('po_rc')) {
			$pospendingcredit = Auth::user()->pospendingcredit();
		}
		$purchaseorders = $pospendingcredit->merge($pospendingcustomer->merge($pospendingvendor));
		$poStatuses = Status::where(['statustype' => 'purchaseorder', 'active' => 1])->orderBy('name')->get();
		return View('purchaseorders.search')->with('title', 'Pending purchase orders')
		->with('poStatuses', array('-1' => 'Pending') + array('0' => 'All') + $poStatuses->pluck('name', 'id')->all())
		->with('purchaseorders', $purchaseorders);
	}
	
	public function pendingcustomer() {
		$purchaseorders = Auth::user()->pospendingcustomer();
		return View('purchaseorders.search')->with('title', 'Pending customer purchase orders')
		->with('hideconditions', true)
		->with('purchaseorders', $purchaseorders);
	}
	
	public function pendingvendor() {
		$purchaseorders = Auth::user()->pospendingvendor();
		return View('purchaseorders.search')->with('title', 'Purchase orders pending credit')
		->with('hideconditions', true)
		->with('purchaseorders', $purchaseorders);
	}
	
	public function pendingcredit() {
		$purchaseorders = Auth::user()->pospendingcredit();
		return View('purchaseorders.search')->with('title', 'Pending vendor purchase orders')
		->with('hideconditions', true)
		->with('purchaseorders', $purchaseorders);
	}
	
	public function searchstart(Request $request) {
		return $this->search($request, false);
	}
	
	public function search(Request $request, $startsearch = true)
    {
		if ($startsearch) {
			$rules = [
				'fromdate' => 'date_format:"j/n/Y"|nullable',
				'todate' => 'date_format:"j/n/Y"|nullable',
			];
			$customMessages = [
				'fromdate.date_format' => 'Date format must be d/m/yyyy',
				'todate.date_format' => 'Date format must be d/m/yyyy',
			];
			$this->validate($request, $rules, $customMessages);
		}
		$roles = Auth::User()->roles;
		$companyzero = $roles->where('company_id', '0');		
		if ($companyzero->count() > 0 ) { //member of metra
			$companies = Company::orderBy('companyname', 'asc');
			$buyers = $companies->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			$suppliers = $companies->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
		} else {
			$companies = Auth::user()->companypermissions(['po_cr', 'po_ch', 'po_vw','vp_ch', 'vp_vw']);
			$buyers = $companies->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			$suppliers = $companies->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);		
		}
		if (Gate::allows('cr_ap') || Gate::allows('pt_as')) {
			$companies = Company::orderBy('companyname', 'asc');
			$buyers = Company::whereIn('companytype_id', [1, 3])->orderBy('companyname', 'asc')->get();
			$suppliers = Company::whereIn('companytype_id', [2, 3])->orderBy('companyname', 'asc')->get();
		}
		$query = Purchaseorder::orderBy('number', 'asc');
		
		
		$query = $query->where (function($q) use($buyers, $suppliers) {
			if ($buyers->count() > 0 && $suppliers->count() > 0) {
				$q->whereIn('company_id', $buyers->pluck('id'))
				->orWhereIn('vendor_id', $suppliers->pluck('id'));
			} elseif ($buyers->count() > 0) {
				$q->whereIn('company_id', $buyers->pluck('id'));
			} elseif ($suppliers->count() > 0) {
				$q->whereIn('vendor_id', $suppliers->pluck('id'));
			}
		});
		
		if (Input::get('fromdate') != '') {
			$date = date_create_from_format("j/n/Y",Input::get('fromdate'));
			$query = $query->where('date', '>=', $date->format('Y-m-d'));
		}		
		if (Input::get('todate') != '') {
			$date = date_create_from_format("j/n/Y",Input::get('todate'));
			$query = $query->where('date', '<=', $date->format('Y-m-d'));
		}

		if (Input::get('po_status') == '-1') {
			$query = $query->whereIn('status_id', [4, 7, 13]);
		} elseif (Input::get('po_status') != '0') {
			$query = $query->where('status_id', Input::get('po_status'));
		}

		if (Input::get('search') != '') {
			$query->where(function ($nested) {
				// Search buyers
				$nested->orWhereHas('company', function($q){
					$q->where('companyname', 'like', '%' . Input::get('search') . '%');
				});

				// // Search suppliers
				$nested->orWhereHas('vendor', function($q){
					$q->where('companyname', 'like', '%' . Input::get('search') . '%');
				});
			});
		}

		$poStatuses = Status::where(['statustype' => 'purchaseorder', 'active' => 1])->orderBy('name')->get();

		if ($startsearch) {
			//DB::enableQueryLog();
			$allpurchaseorders = $query->get();
			if ($companyzero->count() > 0 ) { //member of metra
				$purchaseorders = $allpurchaseorders;
				$purchaseorders = $allpurchaseorders->reject(function ($purchaseorder) {
					return $purchaseorder->status_id == 13 ;
				});
			} else {
				$purchaseorders = $allpurchaseorders->filter(function ($purchaseorder) use($suppliers, $companies) {
				return ((in_array($purchaseorder->vendor_id, $suppliers->pluck('id')->all()) && $purchaseorder->status_id != 13) || in_array($purchaseorder->company_id, $companies->pluck('id')->all())) ;
				});
			}
			//var_dump( DB::getQueryLog());
			//die;			
			return View('purchaseorders.search')->with('title', 'Search POs')
			->with('buyers', array('0' => 'All') + $buyers->pluck('companyname', 'id')->all())
			->with('suppliers', array('0' => 'All') + $suppliers->pluck('companyname', 'id')->all())
			->with('poStatuses', array('-1' => 'Pending') + array('0' => 'All') + $poStatuses->pluck('name', 'id')->all())
			->with('purchaseorders', $purchaseorders);
		} else {
			return View('purchaseorders.search')->with('title', 'Search POs')
			->with('buyers', array('0' => 'All') + $buyers->pluck('companyname', 'id')->all())
			->with('suppliers', array('0' => 'All') + $suppliers->pluck('companyname', 'id')->all())
			->with('poStatuses', array('-1' => 'Pending') + array('0' => 'All') + $poStatuses->pluck('name', 'id')->all());
		}
	}
	
	public function signature($code) {
		$authcode  = substr($code, 0, 20);
		$id = substr($code, 20, 20);
		$purchaseorder = Purchaseorder::find($id);
		$attachments = $purchaseorder->attachments()->where('authcode', $authcode)->get();
		
		//https://github.com/docusign/docusign-php-client
		//require_once('../vendor/autoload.php');
		//require_once('../vendor/docusign/esign-client/autoload.php');
		// DocuSign account credentials & Integrator Key
		$username = "sherif@egynile.com";
		$password = "26192619";
		$integrator_key = "726b5548-94eb-4cdc-817e-0adb38fadb8d";
		$host = "https://demo.docusign.net/restapi";

		// create a new DocuSign configuration and assign host and header(s)
		$config = new docusignclient\Configuration();
		$config->setHost($host);
		$config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"" . $username . "\",\"Password\":\"" . $password . "\",\"IntegratorKey\":\"" . $integrator_key . "\"}");

		/////////////////////////////////////////////////////////////////////////
		// STEP 1:  Login() API
		/////////////////////////////////////////////////////////////////////////
		// instantiate a new docusign api client
		$apiClient = new docusignclient\ApiClient($config);
		// we will first make the Login() call which exists in the AuthenticationApi...
		$authenticationApi = new docusignclient\Api\AuthenticationApi($apiClient);
		// optional login parameters
		$options = new docusignclient\Api\AuthenticationApi\LoginOptions();

		// call the login() API
		$loginInformation = $authenticationApi->login($options);

		// parse the login results
		if(isset($loginInformation) && count($loginInformation) > 0)
		{
			// note: defaulting to first account found, user might be a 
			// member of multiple accounts
			$loginAccount = $loginInformation->getLoginAccounts()[0];
			if(isset($loginInformation))
			{
				$accountId = $loginAccount->getAccountId();
				if(!empty($accountId))
				{
					//echo "Account ID = $accountId\n";
				}
			}
		}
		
		/////////////////////////////////////////////////////////////////////////
		// STEP 2:  Create & Send Envelope (aka Signature Request)
		/////////////////////////////////////////////////////////////////////////
		// set recipient information
		$recipientName = $purchaseorder->company->companyname;
		$recipientEmail = $purchaseorder->company->email;
				
		// instantiate a new envelopeApi object
		$envelopeApi = new docusignclient\Api\EnvelopesApi($apiClient);		
		$documents = array();
		
		$i = 1;
		foreach ($attachments as $attachment) {
			// configure the document we want signed
			// Add a document to the envelope
			$document = new docusignclient\Model\Document();
			echo str_replace('\\', '/', storage_path()) . '/app/' . $attachment->path;
			
			$document->setDocumentBase64(base64_encode(file_get_contents(str_replace('\\', '/', storage_path()) . '/app/' . $attachment->path)));
			$document->setName('s-' . $attachment->filename);
			//$docid = (string)$i;
			$document->setDocumentId("$i");
			array_push($documents, $document);
			$i++;
		}
		
		// Create a |SignHere| tab somewhere on the document for the recipient to sign
		$signHere = new docusignclient\Model\SignHere();
		//$signHere->setXPosition("100");
		//$signHere->setYPosition("700");
		$signHere->setDocumentId("1");
		//$signHere->setPageNumber("1");	
		$signHere->setRecipientId("1");

		$signHere->setAnchorString("Signature:");	
		$signHere->setAnchorXOffset("1");
		$signHere->setAnchorYOffset("0");
		$signHere->setAnchorIgnoreIfNotPresent("false");
		$signHere->setAnchorUnits("inches");
		
		// add the signature tab to the envelope's list of tabs
		$tabs = new docusignclient\Model\Tabs();
		$tabs->setSignHereTabs(array($signHere));

		// add a signer to the envelope
		$signer = new docusignclient\Model\Signer();
		$signer->setEmail($recipientEmail);
		$signer->setName($recipientName);
		$signer->setRecipientId("1");
		$signer->setTabs($tabs);
		$signer->setClientUserId("1234");  // must set this to embed the recipient!

		// Add a recipient to sign the document
		$recipients = new docusignclient\Model\Recipients();
		$recipients->setSigners(array($signer));
		$envelop_definition = new docusignclient\Model\EnvelopeDefinition();
		$envelop_definition->setEmailSubject("Delivery note");

		// set envelope status to "sent" to immediately send the signature request
		$envelop_definition->setStatus("sent");
		$envelop_definition->setRecipients($recipients);
		$envelop_definition->setDocuments($documents);

		// create and send the envelope! (aka signature request)
		$envelop_summary = $envelopeApi->createEnvelope($accountId, $envelop_definition, null);
		
		DB::table('attachments')
            ->where('attachable_id', $id)
			->where('authcode', $authcode)
            ->update(['envelope' => $envelop_summary->getEnvelopeId()]);
		
		
		
		/////////////////////////////////////////////////////////////////////////
		// STEP 3:  Recipient View
		/////////////////////////////////////////////////////////////////////////
		// instantiate a RecipientViewRequest object
		$recipient_view_request = new docusignclient\Model\RecipientViewRequest();
		// set where the recipient is re-directed once they are done signing
		$recipient_view_request->setReturnUrl("http://projectx.metragroup.com/dsignature/" . $envelop_summary->getEnvelopeId());
		// configure the embedded signer 
		$recipient_view_request->setUserName($recipientName);
		$recipient_view_request->setEmail($recipientEmail);
		// must reference the same clientUserId that was set for the recipient when they 
		// were added to the envelope in step 2
		$recipient_view_request->setClientUserId("1234");
		// used to indicate on the certificate of completion how the user authenticated
		$recipient_view_request->setAuthenticationMethod("email");
		// generate the recipient view! (aka embedded signing URL)
		$signingView = $envelopeApi->createRecipientView($accountId, $envelop_summary->getEnvelopeId(), $recipient_view_request);
		//echo "Signing URL = " . $signingView->getUrl() . "\n";

		return redirect($signingView->getUrl());
	}
	
	public function signed($envelope) {
			$attachments = Attachment::where('envelope', $envelope)->get();
			foreach ($attachments as $attachment) {
				$attachment->status = Input::get('event');
				if (Input::get('event') == 'signing_complete') {
					$attachment->authcode = null;
				}
				$attachment->save();
				if (Input::get('event') == 'signing_complete') {
					$purchaseorder = Purchaseorder::find($attachment->attachable_id);
					$purchaseorder->status_id = 16; //delivered
					$purchaseorder->save();
					Processdeliverysignature::dispatch($envelope);
				}
			}
			if (Auth::guest()) {
				if (Input::get('event') == 'signing_complete') {
					return view('message')->with('title', 'Sign document')->with('message', 'Thank you for signing the document(s)');		
				} else {
					return view('message')->with('title', 'Sign document')->with('message', 'Signing not complete. Please try again.');		
				}				
			} else {
				return redirect('/purchaseorder/view/' . $purchaseorder->id);
			}			
		}

	public function getVAT($shipaddressid) 
	{
		if (!$shipaddressid || $shipaddressid == 0)
			return 0;
		else {
			$shipaddress = Shippingaddress::find($shipaddressid);
			return $shipaddress->vat ? $shipaddress->company->vat : 0;
		}
	}
	public function tookandoc(Request $request)
    {
        $qry = "SELECT attachments.id, attachments.path, attachments.filename, attachments.status, purchaseorders.delivery_job_id, purchaseorders.signed_at "
            . " FROM purchaseorders inner join attachments on purchaseorders.id = attachments.attachable_id "
            . " where deliverytype_id = 1 and delivery_status = 'Delivery Successful' "
            . " and attachments.attachable_type = 'purchaseorder' and attachments.attachmenttype_id = 19 and attachments.path = '/' limit 1;";
        $result = DB::select(DB::raw($qry));
        return View('purchaseorders.tookandoc')->with('result', $result);
    }
    public function fileupload(Request $request)
    {
        $path = $request->file('attach')->store('upload/' . date('Y') . '/' . date('m'));
        $attachment = Attachment::find($request->input('attid'));
        $attachment->path = $path;
        $attachment->filename = $path;
        //$attachment->created_by = Auth::user()->id;
        $attachment->updated_by = Auth::user()->id;
        $attachment->description = 'Tookan file';
        $attachment->save();
        //return $attachment->id;
        //return response()->json($attachment);
        
        $purchaseorder = Purchaseorder::find($attachment->attachable_id);
        $purchaseorder->delivery_status = 'Signed & Downloaded';
        $purchaseorder->save();
		return Response(['id' => $attachment->id,'path' => $attachment->path]);

    }

}
