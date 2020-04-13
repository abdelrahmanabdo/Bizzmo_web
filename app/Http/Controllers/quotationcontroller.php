<?php

namespace App\Http\Controllers;

use App\Repositories\QuotationRepository;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;

use App\Brand;
use App\City;
use App\Country;
use App\Company;
use App\Currency;
use App\Freightexpense;
use App\Helpers\AWSsmsHelper;
use App\Helpers\TwilioHelper;
use App\Incoterm;
use App\Paymentterm;
use App\Quotation;
use App\Quotationitem;
use App\Range;
use App\Shippingaddress;
use App\Unit;
use App\Vendor;
use App\Http\Requests\CreateQuotationRequest;
use App\Purchaseorder;
use App\Companytype;
use Illuminate\Support\Facades\Gate;
use App\Status;

use App\Jobs\ProcessBquotation;
use App\Jobs\ProcessSquotation;
use App\Jobs\ProcessBquotationChange;
use App\Jobs\ProcessSquotationChange;
use App\Jobs\ProcessBquotationReject;
use App\Jobs\ProcessSquotationReject;

class quotationcontroller extends Controller
{
	public function view($id)
	{
		$quotation = Quotation::with('quotationitems', 'quotationitems.audits', 'company', 'vendor', 'status', 'audits', 'attachments')->find($id);
		$quotation->date = date("j/n/Y", strtotime($quotation->date));
		$quotation->pickupbydate = date("j/n/Y", strtotime($quotation->pickupbydate));
		$quotation->deliverbydate = date("j/n/Y", strtotime($quotation->deliverbydate));
		$changes = $quotation->previousversion();
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		return view('quotations.manage')->with('title', 'View Quotation')->with('mode', 'v')
			->with('changes', $changes)
			->with('quotation', $quotation)
			->with('brands', $brands->pluck('name', 'id'));
	}

	public function edit($id)
	{
		$quotation = Quotation::find($id);
		if($quotation->status_id == Status::QU_COMPLETED)
			abort(404);

		if (($quotation->userrelation == 2 && $quotation->canchange) || ($quotation->userrelation == 1 && $quotation->status_id == 24)) {
		} else {
			return view('message')->with('title', 'Change Quotation')->with('message', 'This Quotation status is ' . $quotation->status->name . '. Cannot change');
		}
		$buyers = Vendor::find($quotation->vendor_id)->companies;
		$vendor = Vendor::find($quotation->vendor_id);
		// if ($buyers->count() == 0) {
		// 	return view('message')->with('title', 'Create Quotation')->with('message', __('messages.nosupppo'));		
		// }
		$quotation->date = date("j/n/Y", strtotime($quotation->date));
		$quotation->pickupbydate = date("j/n/Y", strtotime($quotation->pickupbydate));
		$quotation->deliverbydate = date("j/n/Y", strtotime($quotation->deliverbydate));
		$incoterms = Incoterm::where('active', 1)->orderBy('name')->get();
		$units = Unit::where('active', 1)->orderBy('name')->get();
		$currencies = Currency::where('active', 1)->orderBy('name')->get();
		$paymentterms = $quotation->company->paymentterms()->where('company_paymentterm.active', 1)->orderBy('name')->get();
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
		$countriesList = $countries->pluck('countryname', 'id');
		$countriesList[0] = 'Other';
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		$deliverytypes = $vendor->deliverytypes;
		$freightexpenses = Freightexpense::where('active', 1)->get();
		$pickupaddresses = $vendor->getSortedPickupAddresses();
		$timelist = Range::where('active', 1)->where('rangetype', 'hours')->orderBy('id')->get();
		return view::make('quotations.manage')->with('quotation', $quotation)
			->with('deliverytypes', $deliverytypes->pluck('name', 'id'))
			->with('freightexpenses',$freightexpenses->pluck('name', 'id'))
			->with('pickupaddresses', $pickupaddresses)
			->with('timelist', $timelist->pluck('name', 'id'))
			->with('incoterms', $incoterms->pluck('name', 'id'))->with('currencies', $currencies->pluck('name', 'id'))
			->with('paymentterms', $paymentterms->pluck('name', 'id'))->with('buyup', $quotation->buyup)
			->with('shippingaddresses', $quotation->company->getSortedAddresses())
			->with('units', $units)->with('unitsarr', $units->pluck('abbreviation', 'id'))
			->with('countries', $countriesList)->with('cities', $cities->pluck('cityname', 'id'))
			->with('buyers', $buyers->pluck('companyname', 'id'))
			->with('brands', $brands->pluck('name', 'id'));
	}

	public function delete($id)
	{
		$quotation = Quotation::find($id);
		if($quotation->status_id == Status::QU_COMPLETED)
			abort(404);

		if (!$quotation->canDelete()) {
			return view('message')->with('title', 'Delete Quotation')->with('message', 'This Quotation is submitted. Cannot delete');
		}

		foreach ($quotation->quotationitems as $item) {
			foreach ($item->audits as $audit) {
				$audit->delete();
			}
			$item->delete();
		}

		foreach ($quotation->audits as $audit) {
			$audit->delete();
		}

		$quotation->delete();

		return redirect('/quotations/');
	}

	public function resend($id) {
		$quotation = Quotation::find($id);
		$quotation->release_otp = null;
		$quotation->save();		
		return $this->orderreleasec($id);
	}
	
	public function orderreleasec($id)
	{
		$quotation = Quotation::find($id);
		$companyName = env('COMPANY_NAME', 'Bizzmo');
		$AWSsmsHelper = new AWSsmsHelper();
		$phone = Auth::user()->phone()->phone;
		if ($quotation->release_otp == null) {			
			$pin = $AWSsmsHelper->generatePIN();
			$quotation->release_otp = $pin;
			$quotation->save();		
			$message = "$pin is your $companyName quotation release verification code";
			if (env('SMS_PROVIDER') == 'aws') {
				$AWSsmsHelper->sendSMS($phone, $message);
			} else {
				$TwilioHelper = new TwilioHelper();
				$TwilioHelper->sendSMS($phone, $message);
			}
		}
		return view('quotations.qu_otp', [
			'title' => 'Quotation release verification',
			'qu_id' => $quotation->id,
			'usertype' => 'buyer',
			'phone' => $AWSsmsHelper->hiddenphone($phone)
		]);
		die;
		$quotation = Quotation::find($id);
		$quotation->status_id = 24;
		$quotation->released_by = Auth::user()->id;
		$quotation->released_at = $date = date_create(date('Y-m-d H:i:s'));;
		$quotation->save();
		
		return redirect('/quotations/view/' . $quotation->id);
	}

	public function verifyrelease(Request $request, $id) {
		$quotation = Quotation::find($id);
		$otp = $quotation->release_otp;
		$rules = [
				'verificationCode' => 'required|in:' . $otp
				];
				
		$messages = [
			'verificationCode.required' => 'Please provide a validation code',
			'verificationCode.in' => 'Validation code is incorrect'
		];
		$this->validate($request, $rules, $messages);
		
		if (Input::get('verificationCode') == $otp) {
			$quotation->status_id = 24;
			$quotation->release_otp = null;
			$quotation->released_by = Auth::user()->id;
			$quotation->released_at = $date = date_create(date('Y-m-d H:i:s'));;			
			$quotation->save();
			$quotation->saveAddresses();
			if ($quotation->changed) {
				$quotation->changed = 0;
				$quotation->save();
				ProcessSquotationChange::dispatch($quotation);
			} else {
				ProcessSquotation::dispatch($quotation);
				ProcessBquotation::dispatch($quotation);
			}			
			return redirect('/quotations/view/' . $quotation->id);
		}
	}
	public function create()
	{
		//DB::enableQueryLog();
		$vendor = Auth::user()->getSupplierCompany();		
		
		if(!$vendor)
			return view('message',[
				'title' => 'Create quotation',
				'message' => 'Cannot create quotation',
				'description' =>  __('messages.noSupplierCompany', ['context' => 'quotation']),
				'error' => true,
			]);
			
		if (!$vendor->active) {
			if(!$vendor->confirmed)
				$msg = __('messages.compSupplierNotConfirmed');

			$msg = __('messages.compSupplierNotActive');
			return view('message',[
				'title' => 'Create quotation',
				'message' => 'Cannot create quotation',
				'description' => $msg,
				'error' => true,
			]);
		}
		
		if (!$vendor->vendor_signed) {
			return view('message',[
				'title' => 'Create quotation',
				'message' => 'Cannot create a quotation',
				'description' =>'Your company\'s contract is not signed yet.',
				'error' => true
			]);
		}
		$vendor = Vendor::find($vendor->id);
		$incoterms = Incoterm::where('active', 1)->orderBy('name')->get();
		$units = Unit::where('active', 1)->orderBy('name')->get();
		$currencies = Currency::where('active', 1)->orderBy('name')->get();
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
		$brands = Brand::where('active', 1)->orderBy('name', 'asc')->get();
		$deliverytypes = $vendor->deliverytypes;
		$freightexpenses = Freightexpense::where('active', 1)->get();
		$pickupaddresses = $vendor->getSortedPickupAddresses();
		$timelist = Range::where('active', 1)->where('rangetype', 'hours')->orderBy('id')->get();
		$buyers = $vendor->companies->whereIn('companytype_id', [1, 3]);
		$firstBuyer = $buyers->first();
		// if (isset($firstBuyer)) {
			// $paymentterms = $firstBuyer->paymentterms()->where('company_paymentterm.active', 1)->orderBy('name')->get();
			// $buyup = $paymentterms->first()->buyup;
			// $paymentterms = $paymentterms->pluck('name', 'id');

			// $shippingaddresses = $firstBuyer->getSortedAddresses();
			// $vat = $shippingaddresses->first()->vat;
		// } else {
			$paymentterms = collect();
			$buyup = 0;
			$shippingaddresses = collect();
			$vat = 0;
		// }
		return view::make('quotations.manage')
			->with('buyers', $buyers->pluck('companyname', 'id'))->with('vendor', $vendor)
			->with('paymentterms', $paymentterms)
			->with('shippingaddresses', $shippingaddresses)
			->with('buyup', $buyup)
			->with('vat', $vat)
			->with('countries', $countries->pluck('countryname', 'id'))
			->with('unitsarr', $units->pluck('abbreviation', 'id'))
			->with('currencies', $currencies->pluck('name', 'id'))
			->with('incoterms', $incoterms->pluck('name', 'id'))
			->with('cities', $cities->pluck('cityname', 'id'))
			->with('deliverytypes', $deliverytypes->pluck('name', 'id'))
			->with('freightexpenses',$freightexpenses->pluck('name', 'id'))
			->with('timelist', $timelist->pluck('name', 'id'))
			->with('pickupaddresses', $pickupaddresses)
			->with('brands', $brands->pluck('name', 'id'))
			->with('units', $units)
			->with('isCreate', true);
	}

	public function newquotation(CreateQuotationRequest $request)
	{
		$data = $request->input();
		$quotation = QuotationRepository::create($data);
		return redirect('/quotations/view/' . $quotation->id);
	}

	public function save(CreateQuotationRequest $request)
	{
		$data = $request->input();
		$quotation = QuotationRepository::save($data);

		if ($quotation->userrelation == 2)
			return redirect('/quotations/view/' . $quotation->id);
		else
			ProcessBquotationChange::dispatch($quotation);
			return redirect('/quotations/bview/' . $quotation->id);
	}

	public function searchstart(Request $request)
	{
		return $this->search($request, true);
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
		if ($companyzero->count() > 0) {
			$companies = Company::orderBy('companyname', 'asc');
			$buyers = $companies->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			$suppliers = $companies->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
		} else {
			$companies = Auth::user()->companypermissions(['qu_cr', 'qu_ch', 'cq_vw', 'cq_ch']);
			$buyers = $companies->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			$suppliers = $companies->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
		}
		$query = Quotation::orderBy('number', 'asc');


		$query = $query->where(function ($q) use ($buyers, $suppliers) {
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
			$date = date_create_from_format("j/n/Y", Input::get('fromdate'));
			$query = $query->where('date', '>=', $date->format('Y-m-d'));
		}
		if (Input::get('todate') != '') {
			$date = date_create_from_format("j/n/Y", Input::get('todate'));
			$query = $query->where('date', '<=', $date->format('Y-m-d'));
		}

		if (Input::get('quotation_status') == '-1' || Input::get('quotation_status') == null) {
			$query = $query->whereIn('status_id', [23, 24]);
		} elseif (Input::get('quotation_status') != '0') {
			$query = $query->where('status_id', Input::get('quotation_status'));
		}

		if (Input::get('search') != '') {
			$query->where(function ($nested) {
				// Search buyers
				$nested->orWhereHas('company', function ($q) {
					$q->where('companyname', 'like', '%' . Input::get('search') . '%');
				});

				// // Search suppliers
				$nested->orWhereHas('vendor', function ($q) {
					$q->where('companyname', 'like', '%' . Input::get('search') . '%');
				});
			});
		}

		$quotationStatuses = Status::where(['statustype' => 'quotation', 'active' => 1])->orderBy('name')->get();

		if ($startsearch) {
			$allQuotations = $query->get();
			if ($companyzero->count() > 0) {				
				$quotations = $allQuotations;
				$quotations = $allQuotations->reject(function ($quotation) {
					return $quotation->status_id == 23;
				});
			} else {
				$buyerCompany = Auth::user()->getBuyerCompany();
				$buyerid = 0;
				if($buyerCompany) {
					$buyerid = $buyerCompany->id;
				}
				$supplierid = 0;
				$supplierCompany = Auth::user()->getSupplierCompany();
				if ($supplierCompany) {
					$supplierid = $supplierCompany->id;
				}				
				$quotations = $allQuotations->filter(function ($quotation) use ($suppliers, $companies, $buyerid, $supplierid) {
					//return ((in_array($quotation->vendor_id, $suppliers->pluck('id')->all()) && $quotation->status_id != 23) || (in_array($quotation->company_id, $companies->pluck('id')->all()) && $quotation->status_id != 23));
					return (((in_array($quotation->vendor_id, $suppliers->pluck('id')->all()) || in_array($quotation->company_id, $companies->pluck('id')->all())) && $quotation->status_id != 23) || $quotation->vendor_id == $supplierid);
				});
			}
			return View('quotations.search')->with('title', 'Search Quotations')
				->with('buyers', array('0' => 'All') + $buyers->pluck('companyname', 'id')->all())
				->with('suppliers', array('0' => 'All') + $suppliers->pluck('companyname', 'id')->all())
				->with('quotationStatuses', array('-1' => 'Pending') + array('0' => 'All') + $quotationStatuses->pluck('name', 'id')->all())
				->with('quotations', $quotations);
		} else {
			return View('quotations.search')->with('title', 'Search Quotations')
				->with('buyers', array('0' => 'All') + $buyers->pluck('companyname', 'id')->all())
				->with('suppliers', array('0' => 'All') + $suppliers->pluck('companyname', 'id')->all())
				->with('quotationStatuses', array('-1' => 'Pending') + array('0' => 'All') + $quotationStatuses->pluck('name', 'id')->all());
		}
	}

	public function vresend($id) {
		$quotation = Quotation::find($id);
		$quotation->release_otp = null;
		$quotation->save();		
		return $this->approvec($id);
	}
	
	public function approvec($id) {
		$quotation = Quotation::findOrFail($id);
		if($quotation->status_id == Status::QU_COMPLETED)
			abort(404);

		if ($quotation->status_id !== 24) {
			return view('message',[
				'title' => 'Quotation Approve',
				'message' => 'Cannot approve quotation'
			]);
		}
		$companyName = env('COMPANY_NAME', 'Bizzmo');
		$AWSsmsHelper = new AWSsmsHelper();
		$phone = Auth::user()->phone()->phone;
		if ($quotation->release_otp == null) {			
			$pin = $AWSsmsHelper->generatePIN();
			$quotation->release_otp = $pin;
			$quotation->save();		
			$message = "$pin is your $companyName quotation release verification code";							
			if (env('SMS_PROVIDER') == 'aws') {
				$AWSsmsHelper->sendSMS($phone, $message);
			} else {
				$TwilioHelper = new TwilioHelper();
				$TwilioHelper->sendSMS($phone, $message);
			}
		}
		return view('quotations.qu_otp', [
			'title' => 'Quotation release verification',
			'qu_id' => $quotation->id,
			'usertype' => 'supplier',
			'phone' => $AWSsmsHelper->hiddenphone($phone)
		]);
		
		die;
		$quotation = Quotation::findOrFail($id);
		if($quotation->status_id == Status::QU_COMPLETED)
			abort(404);

		if ($quotation->status_id !== 24) {
			return view('message',[
				'title' => 'Quotation Approve',
				'message' => 'Cannot approve quotation'
			]);
		}
		
		$purchaseOrder = $quotation->toPurchaseOrder();
		if(!$purchaseOrder)
			abort(500);

		$quotation->po_id = $purchaseOrder->id;
		$quotation->status_id = Status::QU_COMPLETED;
		$quotation->save();
		return redirect("/purchaseorders/view/$purchaseOrder->id");
	}

	public function verifyvrelease(Request $request, $id) {
		$quotation = Quotation::findOrFail($id);
		$otp = $quotation->release_otp;
		$rules = [
				'verificationCode' => 'required|in:' . $otp
				];
				
		$messages = [
			'verificationCode.required' => 'Please provide a validation code',
			'verificationCode.in' => 'Validation code is incorrect'
		];
		$this->validate($request, $rules, $messages);
		if (Input::get('verificationCode') == $otp) {
			$purchaseOrder = $quotation->toPurchaseOrder();
			if(!$purchaseOrder)
				abort(500);

			$quotation->po_id = $purchaseOrder->id;
			$quotation->status_id = Status::QU_COMPLETED;			
			$quotation->release_otp = null;
			$quotation->save();
			return redirect("/purchaseorders/view/$purchaseOrder->id");
		}
	}
	
	public function rejectc($id) {
		$quotation = Quotation::find($id);
		if($quotation->status_id == Status::QU_COMPLETED)
			abort(404);

		if ($quotation->status_id !== 24)
			return view('message')->with('title', "Quotation Reject")->with('message', "Cannot reject quotation");		

		$quotation->status_id = 25; // Buyer reject
		$quotation->save();

		return redirect('/quotations/bview/' . $quotation->id);
	}

	public function cancel($id) {
		$quotation = Quotation::find($id);
		if($quotation->status_id == Status::QU_COMPLETED)
			abort(404);

		if (!$quotation->canCancel() && $quotation->canDelete())
			return view('message')->with('title', "Quotation Reject")->with('message', "Cannot cancel quotation");		

		$quotation->status_id = 26; // Supplier canceled
		$quotation->save();
		ProcessBquotationReject::dispatch($quotation);
		ProcessSquotationReject::dispatch($quotation);
		return redirect('/quotations/view/' . $quotation->id);
	}

	
}
