<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use Gate;
use View;

use App\Attachment;
use App\Attachmenttype;
use App\Balancesheetitem;
use App\Company;
use App\Country;
use App\Currency;
use App\Creditrequest;
use App\Creditrequestbusref;
use App\Creditrequestbankstatement;
use App\Creditrequestbalancesheet;
use App\Creditrequestincomestatement;
use App\Creditrequestsecurity;
use App\Creditstatus;
use App\Incomestatementitem;
use App\Margindeposittype;
use App\Paymentterm;
use App\Range;
use App\Securitytype;
use App\Settings;
use App\Tenor;

use DocuSign\eSign as docusignclient;

use Mail;
use App\Mail\Creditrequestsecuritymail;
use App\Jobs\Processcreditrequestsecurities;
use App\Jobs\ProcessSecurityChequeSap;
use App\Jobs\Processsecuritiessignature;
use App\Jobs\Processcreditrequest;
use App\Jobs\ProcessSDinvoice;

class creditrequestcontroller extends Controller
{
    public function view($id) {
		$creditrequest = $this->getCreditRequest($id);
		$financialscurrencies = Currency::orderBy('name')->get()->pluck('name', 'id');
		return view('creditrequests.manage', [
			'title' => 'View credit request',
			'mode' => 'v',
			'financialscurrencies' => $financialscurrencies,
			'creditrequest' => $creditrequest
		]);
	}
	
	public function approvec($id) {
		$creditrequest = Creditrequest::with('busrefs', 'balancesheets','attachments', 'incomestatements')->find($id);
		$creditrequest->incomestatements = $creditrequest->incomestatements->sortBy('order');
		$creditrequest->balancesheets = $creditrequest->balancesheets->sortBy('order');
		$margindeposittypes = Margindeposittype::all()->pluck('name', 'id');
		return view('creditrequests.manage')->with('title', 'View credit request')->with('mode', 'a')
		->with('creditrequest', $creditrequest)->with('margindeposittypes', $margindeposittypes);		
	}
	
	public function postDelete($id) {
		$creditrequest = Creditrequest::find($id);
		if ($creditrequest->appointment_id != null) {
			return view('message',[
				'title' => 'Cancel credit request',
				'message' => 'Cannot delete credit request',
				'description' => 'This credit request has a scheduled appointment',
				'error' => true
			]);		
		}
		Creditrequest::destroy($id);
		return redirect('creditrequests');
	}

	public function getDelete($id) {
		$creditrequest = $this->getCreditRequest($id);
		return view('creditrequests.manage', [
			'title' => 'Delete credit request',
			'mode' => 'del',
			'creditrequest' => $creditrequest
		]);
	}
	
	public function sign($id) {
		$creditrequest = Creditrequest::with('busrefs', 'incomestatements', 'balancesheets', 'attachments')->find($id);
		if ($creditrequest->creditstatus_id == '1') {
			return view('message')->with('title', 'Sign credit request')->with('message', 'This credit request is already approved. Cannot sign it.');		
		} elseif ($creditrequest->creditstatus_id == '2' && $creditrequest->requesttype_id == 1 && empty($creditrequest->appointment_id)) {
			return view('message')->with('title', 'Sign credit request')->with('message', 'A site visit has to be completed before signing this request.');		
		} elseif ($creditrequest->creditstatus_id == '3') {
			return view('message')->with('title', 'Sign credit request')->with('message', 'This credit request is rejected. Cannot sign it.');		
		}
		return view('creditrequests.manage')->with('title', 'Sign and/or attach credit request documents')->with('mode', 's')
		->with('creditrequest', $creditrequest);		
	}
	
	public function proceed($id) {
		$creditrequest = Creditrequest::with('securities', 'busrefs', 'margindeposittype', 'balancesheets','attachments', 'incomestatements', 'company', 'company.paymentterms')->find($id);
		$creditrequest->incomestatements = $creditrequest->incomestatements->sortBy('order');
		$creditrequest->balancesheets = $creditrequest->balancesheets->sortBy('order');
		//return $creditrequest;
		if ($creditrequest->creditstatus_id == '1') {
			return view('message')->with('title', 'Proceed to credit decision')->with('message', 'This credit request is already approved. Cannot approve it again.');		
		} elseif ($creditrequest->creditstatus_id == '2' && $creditrequest->requesttype_id == 1 && empty($creditrequest->appointment_id)) {
			return view('message')->with('title', 'Proceed to credit decision')->with('message', 'A site visit has to be completed before approving this request.');		
		} elseif ($creditrequest->creditstatus_id == '3') {
			return view('message')->with('title', 'Proceed to credit decision')->with('message', 'This credit request is rejected. Cannot approve it.');		
		}
		$creditstatuses = Creditstatus::whereIn('id', [1, 3])->where('active', 1)->orderBy('name')->pluck('name', 'id');
		$margindeposittypes = Margindeposittype::all()->pluck('name', 'id');
		$tenors = Tenor::all()->pluck('name', 'id');
		$paymentterms = Paymentterm::all();
		$securitytypes = Securitytype::all()->where('active', 1);
		$countries = Country::orderBy('countryname')->get();
		return view('creditrequests.proceed')->with('title', 'Proceed to credit decision')->with('mode', 'a')
		->with('creditstatuses', $creditstatuses)->with('margindeposittypes', $margindeposittypes)->with('tenors', $tenors)
		->with('countries', $countries)
		->with('creditrequest', $creditrequest)->with('paymentterms', $paymentterms->pluck('name', 'id'))
		->with('securitytypes', $securitytypes->pluck('name', 'id'));
	}
	
	public function reject($id) {
		$creditrequest = Creditrequest::findOrFail($id);
		if ($creditrequest->creditstatus_id  == 1) {
			return view('message')->with('title', 'Reject CR')->with('message', 'This CR is approved. Cannot reject');		
		} 
		$creditrequest->creditstatus_id = Creditstatus::REJECTED;
		$creditrequest->save();
		return redirect("/creditrequests/view/$creditrequest->id");
	}
	public function saveapprove(Request $request, $id) {
		$rules = [
			'limit' => 'required|numeric|min:1',
			'margindepositvalue' => 'required|numeric',
			'description.*' => 'required|max:60',
			'signername.*' => 'required|max:60',
			'passportno.*' => 'required|max:60',
			'signeremail.*' => 'required|max:60|email',
			'amount.*' => 'required|numeric',
			'securitycheckvalue' => 'bail|required_if:sc,4|nullable|numeric',
			'securitysignername' => 'required_if:sc,4',
			'securitysigneremail' => 'bail|required_if:sc,4|nullable|email',
			'personalsignername' => 'required_if:pg,4',
			'personalsigneremail' => 'bail|required_if:pg,1|nullable|email',
			'corporatesignername' => 'required_if:cg,2',
			'corporatesigneremail' => 'bail|required_if:cg,2|nullable|email',
			'promissarysignername' => 'required_if:pn,3',
			'promissarysigneremail' => 'bail|required_if:pn,3|nullable|email',
        ];
		$customMessages = [
			'limit.min' => 'Approved limit should be more than 0',
			'limit.required' => 'Approved limit is required',
			'limit.numeric' => 'Approved limit must be numeric',
			'margindepositvalue.required' => 'Margin deposit percent is required',
			'margindepositvalue.numeric' => 'Margin deposit percent must be numeric',
			'description.*.required' => 'Description is required',
			'description.*.max' => 'Description should not be more than 60 characters',
		];
		$this->validate($request, $rules, $customMessages);
		$creditrequest = Creditrequest::find($id);
		if ($creditrequest->creditstatus_id == Creditstatus::PENDING_RECEIPT_OF_SECURITIES) {
			return view('message',[
				'title' => 'Credit request status',
				'message' => 'Cannot approve. Credit request is pending reciept of securities'
			]);		
		}
		$creditrequest->creditstatus_id = Creditstatus::PENDING_RECEIPT_OF_SECURITIES;
		$creditrequest->limit = Input::get('limit');
		$creditrequest->approved_by = Auth::user()->id;
		$creditrequest->approved_on = date('Y-m-d H:i:s');
		$creditrequest->margindeposittype_id = Input::get('margindeposittype_id');
		$creditrequest->margindepositvalue = Input::get('margindepositvalue');
		
		if ($creditrequest->creditstatus_id == Creditstatus::REJECTED) {
			$creditrequest->limit = 0;
			Processcreditrequest::dispatch($creditrequest);
		}
		$creditrequest->save();		
		if (Input::has('attachmentid')) {
			$i = 0;
			foreach (Input::get('attachmentid') as $item) {
				$attachment = Attachment::find($item);
				$attachment->attachable_id = $creditrequest->id;
				$attachment->attachable_type = 'Creditrequest';
				$attachment->description = Input::get('description')[$i];
				$attachment->save();
				$i++;
			}
		}		
		if (Input::has('id')) {			
			$i = 0;
			foreach (Input::get('id') as $item) {
				if (Input::get('securitytype_id')[$i] == 1 || Input::get('securitytype_id')[$i] == 2) {
					$passportno = Input::get('passportno')[$i];
					$country_id = Input::get('country_id')[$i];
				} else {
					$passportno = null;
					$country_id = null;
				}
				if (Input::get('securitytype_id')[$i] == 5) {
					$country_id = Input::get('country_id')[$i];
					$company_name = Input::get('company_name')[$i];
					$commercial_register = Input::get('commercial_register')[$i];
					$address = Input::get('address')[$i];
					$company_owner = Input::get('company_owner')[$i];
					$designation = Input::get('designation')[$i];
					$passportno = null;
				} else {
					$company_name = null;
					$commercial_register = null;
					$address = null;
					$company_owner = null;
					$designation = null;
				}
				if ($item == '' && Input::get('securitytypedel')[$i] == '') {
					$creditrequestsecurity  = new Creditrequestsecurity(array(
						'securitytype_id' => Input::get('securitytype_id')[$i], 
						'signername' => Input::get('signername')[$i], 
						'signeremail'=> Input::get('signeremail')[$i], 
						'passportno' => $passportno,
						'country_id' => $country_id,
						'company_name' => $company_name,
						'commercial_register' => $commercial_register,
						'address' => $address,
						'company_owner' => $company_owner,
						'designation' => $designation,
						'amount'=> Input::get('amount')[$i])
					);
					$creditrequest->securities()->save($creditrequestsecurity);
					// If the security is a check, add its autho form
					if (Input::get('securitytype_id')[$i] == Securitytype::SECURITY_CHEQUE) {
						$creditrequestsecurity  = new Creditrequestsecurity(array(
							'securitytype_id' => Securitytype::SECURITY_CHQAUTH, 
							'signername' => Input::get('signername')[$i], 
							'signeremail'=> Input::get('signeremail')[$i], 
							'passportno' => $passportno,
							'country_id' => $country_id,
							'company_name' => $company_name,
							'commercial_register' => $commercial_register,
							'address' => $address,
							'company_owner' => $company_owner,
							'designation' => $designation,
							'amount'=> Input::get('amount')[$i])
						);
						$creditrequest->securities()->save($creditrequestsecurity);
					}
				} elseif ($item != '') {
					if (Input::get('securitytypedel')[$i] == '') {						
						$creditrequestsecurity = Creditrequestsecurity::find($item);
						$creditrequestsecurity->signername = Input::get('signername')[$i];
						$creditrequestsecurity->signeremail = Input::get('signeremail')[$i];
						$creditrequestsecurity->amount = Input::get('amount')[$i];
						$creditrequestsecurity->passportno = $passportno;
						$creditrequestsecurity->country_id = $country_id;
						$creditrequestsecurity->company_name = $company_name;
						$creditrequestsecurity->commercial_register = $commercial_register;
						$creditrequestsecurity->address = $address;
						$creditrequestsecurity->company_owner = $company_owner;
						$creditrequestsecurity->designation = $designation;
						$creditrequestsecurity->save();
					} else {
						$creditrequestsecurity = Creditrequestsecurity::destroy($item);
					}
				}
				$i++;
			}
		}
		$securities = DB::table('creditrequestsecurities')->select('signername', 'signeremail')->distinct()
		->where('creditrequest_id', $creditrequest->id)->where('securitytype_id', '<>', 4)->where('securitytype_id', '<>', 6)->get();
		foreach ($securities as $security) {
			DB::table('creditrequestsecurities')
			->where('creditrequest_id', $creditrequest->id)->where('securitytype_id', '<>', 4)->where('securitytype_id', '<>', 6)
			->where('signername', $security->signername)->where('signeremail', $security->signeremail)
			->update(['authcode' => str_random(20)]);
		}
		
		//Security deposit
		if ($creditrequest->creditstatus_id == Creditstatus::PENDING_RECEIPT_OF_SECURITIES) {
			if(Input::get('margindepositvalue') > 0){
				$setting = Settings::find(Settings::BUYER_INVOICE);
				$inv_no = $setting->buyerInvoiceNumber();
				$depositValue = Input::get('limit') * Input::get('margindepositvalue') / 100;
				$creditrequestsecurity  = new Creditrequestsecurity(array(
					'securitytype_id' => 7, 
					'amount'=> $depositValue,
					'inv_no' => $inv_no)
				);
				$creditrequest->securities()->save($creditrequestsecurity);
				ProcessSDinvoice::dispatch($creditrequestsecurity);
			} else {
				if ($creditrequest->securities->count() == 0) {
					$creditrequest->creditstatus_id = Creditstatus::APPROVED;
					$creditrequest->save();
					$creditrequest->updatestatus();
				}
			}
			$provider = env('SIGNATURE_PROVIDER');
			Processcreditrequestsecurities::dispatch($provider, $creditrequest);
		}
		
		return $this->view($creditrequest->id);
	}
	
	public function attachdocument(Request $request) {		
		$path = $request->file('attach')->store('images');
		$attachment  = new Attachment;
		$attachment->path = $path;
		$attachment->created_by = Auth::user()->id;
		$attachment->updated_by = Auth::user()->id;
		$attachment->save();
		return $attachment->id;
	}
	
	public function attachstart($id) {
		$company = Creditrequest::find($id);
		$company->incorporated = date("j/n/Y",strtotime($company->incorporated));
		$attachmenttypes = Attachmenttype::where('module_id', 1)->get();
		$attachments = Creditrequestattachment::where('company_id', $id)->get();
		return view('companies.attach')->with('title', 'Creditrequest attachments')->with('company', $company)
		->with('attachmenttypes', $attachmenttypes->pluck('name', 'id'))->with('attachments', $attachments);
	}
	
	public function attach(Request $request, $id) {
		$path = $request->file('photo')->store('images');
		$companyattachment = new Creditrequestattachment;
		$companyattachment->company_id = $id;
		$companyattachment->attachmenttype_id  = Input::get('attachmenttype_id');
		$companyattachment->attachmentname  = Input::get('attachmentdescription');
		$companyattachment->path  = $path;
		$companyattachment->save();
		return $this->attachstart($id);
	}
	
	public function bank($id) {
		$creditrequest = Creditrequest::find($id);
		return view('creditrequests.attachment')->with('title', 'View bank attachments')->with('creditrequest', $creditrequest);
	}
	
	public function financial($id) {
		$creditrequest = Creditrequest::find($id);
		return view('creditrequests.attachment')->with('title', 'View financial attachments')->with('creditrequest', $creditrequest);
	}
	
	public function viewattachment($id) {
		$attachment = Attachment::find($id);
		$creditrequest = Creditrequest::find($attachment->attachable_id);
		return view('creditrequests.attachment')->with('title', 'View attachment')->with('attachment', $attachment)->with('creditrequest', $creditrequest);
	}
	
	public function actions()
    {
		// $allcompanies = Company::with('creditrequests')->whereIn('companytype_id', [1,3])->where('active', 1)
		// ->whereIn('id', $companies = Auth::user()->companypermissions(['cr_cr', 'cr_ch', 'cr_vw'])->pluck('id'))
		// ->orderBy('companyname', 'asc')->get();
		// $companies = $allcompanies->where('canrequestcredit', true);
		// return view::make('creditrequests.list')->with('title', 'Credit request')
		// ->with('companies',$companies)->with('actiontype', 'initial');
		$buyerCompany = Auth::user()->getBuyerCompany();
		if(!$buyerCompany)
			return view('message',[
				'title' => 'Create Credit Request',
				'message' => 'Cannot request a credit line',
				'description' =>  __('messages.noBuyerCompany', ['context' => 'credit request']),
				'error' => true,
			]);

		return redirect('/creditrequests/create/' . $buyerCompany->id);
    }
	
	public function raise()
    {		
		$buyerCompany = Auth::user()->getBuyerCompany();
		if(!$buyerCompany) {
			return view('message',[
				'title' => 'Increase credit',
				'message' => 'Cannot increase credit',
				'description' => __('messages.noBuyerCompany', ['context' => 'creadit raise']),
				'error' => true
			]);
		}
		if (!$buyerCompany->active || !$buyerCompany->confirmed || !$buyerCompany->customer_signed) {
			if (!$buyerCompany->active) 
				return view('message',[
					'title' => 'Increase credit',
					'message' => 'Cannot increase credit line',
					'description' => __('messages.compBuyerNotActive'),
					'error' => true
				]);
			elseif (!$buyerCompany->confirmed)
				return view('message',[
					'title' => 'Increase credit',
					'message' => 'Cannot increase credit line',
					'description' => __('messages.compBuyerNotConfirmed'),
					'error' => true
				]);
			else
				return view('message',[
					'title' => 'Increase credit',
					'message' => 'Cannot increase credit line',
					'description' =>'This company\'s contract is not signed.',
					'error' => true
				]);
		}
		
		$creditrequests = Creditrequest::where('company_id', $buyerCompany->id)->whereIn('creditstatus_id', [2, 4, 5, 6])->get();		
		if ($creditrequests->count() > 0) {
			return view('message',[
				'title' => 'Increase credit',
				'message' => 'Cannot increase credit line',
				'description' => 'There\'s a pending credit request for this company.',
				'error' => true
			]);
		}
		
		if($buyerCompany->creditlimit <= 0)
			return view('message',[
				'title' => 'Increase credit',
				'message' => 'Cannot increase credit line',
				'description' => 'The buyer does not have a credit limit',
				'error' => true
			]);

			
		$approvedCreditrequest = Creditrequest::where([
			'company_id' => $buyerCompany->id,
			'creditstatus_id' => 1
			])->latest()->first();
		
		if(!$approvedCreditrequest)
			return view('message',[
				'title' => 'Increase credit',
				'message' => 'Cannot increase credit line',
				'description' => 'You don\'t have a credit to increase',
				'error' => true
			]);

		$datetime1 = date_create($approvedCreditrequest->approved_on);
		$today = new \DateTime();
		$interval = date_diff($datetime1, $today);
		echo $interval->days;
	
		// if($interval->days < 180)
			// return view('message',[
				// 'title' => 'Increase credit',
				// 'message' => 'Cannot increase credit line',
				// 'description' => 'Last credit limit was changed less than six months ago',
				// 'error' => true
			// ]);

		return redirect('/creditrequests/increase/' . $buyerCompany->id);
    }
	
	public function create($id)
	{
		$margindeposittypes = Margindeposittype::all()->pluck('name', 'id');
		$tenors = Tenor::all()->pluck('name', 'id');
		$currencies = Currency::where('active', 1)->orderBy('name')->get()->pluck('name', 'id');
		$financialscurrencies = Currency::orderBy('name')->get()->pluck('name', 'id');
		$company = Company::findorfail($id);		
		if (!$company->active || !$company->confirmed || !$company->customer_signed || $company->creditlimit > 0) {
			if (!$company->active) 
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot request a credit line',
					'description' => __('messages.compBuyerNotActive'),
					'error' => true
				]);
			elseif (!$company->confirmed)
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot request a credit line',
					'description' => __('messages.compBuyerNotConfirmed'),
					'error' => true
				]);
			elseif (!$company->customer_signed)
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot request a credit line',
					'description' =>'This company\'s contract is not signed.',
					'error' => true
				]);
			elseif (!$company->canRequestCredit)
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot request a new credit line',
					'description' => __('messages.compHasCreditLine'),
					'error' => true
				]);
		}
		$creditrequests = Creditrequest::where('company_id', $id)->whereIn('creditstatus_id', [2, 4, 5, 6])->get();		
		if ($creditrequests->count() > 0) {
			return view('message',[
				'title' => 'Create credit request',
				'message' => 'Cannot request a credit line',
				'description' => 'There\'s a pending credit request for this company.',
				'error' => true
			]);
		}
		$busreflengths = Range::where('active', 1)->where('rangetype', 'years')->orderBy('id')->get();
		$financialsdate = date('Y') - 1 . '-12-31';
		$incomestatementstart = $financialsdate;
		$balancesheeton = $financialsdate;
		$incomestatementitems = Incomestatementitem::all();
		$balancesheetitems = Balancesheetitem::where('calc', 0)->orderBy('order')->get();
		return view::make('creditrequests.manage')->with('title', 'Credit request')
		->with('margindeposittypes', $margindeposittypes)->with('tenors', $tenors)->with('currencies', $currencies)
		->with('financialscurrencies', $financialscurrencies)
		->with('busreflengths',$busreflengths)->with('arrbusreflengths',$busreflengths->pluck('name', 'id'))
		->with('incomestatementitems',$incomestatementitems)->with('incomestatementstart',$incomestatementstart)
		->with('balancesheetitems',$balancesheetitems)->with('balancesheeton',$balancesheeton)
		->with('company',$company)->with('requesttype_id', '1');
    }
	
	public function increase($id)
    {
		$company = Company::findorfail($id);
		$financialscurrencies = Currency::orderBy('name')->get()->pluck('name', 'id');
		if (!$company->active || !$company->confirmed || !$company->customer_signed) {
			if (!$company->active) 
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot increase credit line',
					'description' => __('messages.compBuyerNotActive'),
					'error' => true
				]);
			elseif (!$company->confirmed)
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot increase credit line',
					'description' => __('messages.compBuyerNotConfirmed'),
					'error' => true
				]);
			else
				return view('message',[
					'title' => 'Create credit request',
					'message' => 'Cannot increase credit line',
					'description' =>'This company\'s contract is not signed.',
					'error' => true
				]);
		}
		$creditrequests = Creditrequest::where('company_id', $id)->where('creditstatus_id', 0)->get();		
		if ($creditrequests->count() > 0) {
			return view('message')->with('title', 'Create credit request')->with('message', 'There are credit requests pending approval. Cannot create a new one.');		
		}
		$margindeposittypes = Margindeposittype::all()->pluck('name', 'id');
		$incomestatementstart = (date('Y') - 3) . '-01-01';
		$balancesheeton = (date('Y') - 3) . '-01-01';
		$incomestatementitems = Incomestatementitem::where('calc', 0)->get();
		$balancesheetitems = Balancesheetitem::where('calc', 0)->get();
		$tenors = Tenor::all()->pluck('name', 'id');
		$currencies = Currency::where('active', 1)->orderBy('name')->get()->pluck('name', 'id');
		return view::make('creditrequests.manage', [
			'title' => 'Request credit increase',
			'requesttype_id' => '2',
			'tenors' => $tenors,
			'incomestatementitems' => $incomestatementitems,
			'currencies' => $currencies,
			'incomestatementstart' => $incomestatementstart,
			'margindeposittypes' => $margindeposittypes,
			'balancesheetitems' => $balancesheetitems,
			'balancesheeton' => $balancesheeton,
			'financialscurrencies' => $financialscurrencies,
			'company' => $company
		]);
    }
	
	public function edit($id)
    {
		$creditrequest = Creditrequest::with('busrefs', 'balancesheets','attachments', 'incomestatements')->find($id);
		$creditrequest->incomestatements = $creditrequest->incomestatements->sortBy('order')->reject(function ($incomestatement) {
			return ($incomestatement->incomestatementitem->calc) ;
		});
		$creditrequest->balancesheets = $creditrequest->balancesheets->sortBy('order')->reject(function ($balancesheet) {
			return ($balancesheet->balancesheetitem->calc) ;
		});
		if ($creditrequest->creditstatus_id != 2 && $creditrequest->creditstatus_id != 4 ) {
			return view('message')->with('title', 'Change credit request')->with('message', 'Credit request already approved. Cannot change');		
		}
		$margindeposittypes = Margindeposittype::all()->pluck('name', 'id');
		$tenors = Tenor::all()->pluck('name', 'id');
		$bankattachment = $creditrequest->attachments->where('attachmenttype_id', 6)->first();
		$financialattachment = $creditrequest->attachments->where('attachmenttype_id', 8)->first();
		$busreflengths = Range::where('active', 1)->where('rangetype', 'years')->orderBy('id')->get();
		return view::make('creditrequests.manage')->with('creditrequest', $creditrequest)
		->with('bankattachment', $bankattachment)->with('financialattachment', $financialattachment)
		->with('busreflengths',$busreflengths)->with('arrbusreflengths',$busreflengths->pluck('name', 'id'))
		->with('margindeposittypes', $margindeposittypes)->with('tenors', $tenors);
    }
	
	public function manage($id)
    {
		$creditrequest = Creditrequest::where('company_id', $id)->orderBy('id', 'desc');
		if ($creditrequest->count() != 0 ) {
			$company = Creditrequest::find($id);
			$company->incorporated = date("j/n/Y",strtotime($company->incorporated));
			$countries = Country::where('active', 1)->orWhere('id', $company->country_id)->orderBy('countryname')->get();
			$cities = City::where('country_id', $company->country_id)->where('active', 1)->orWhere('id', $company->city_id)->orderBy('cityname')->get();
			return view::make('companies/manage')->with('company', $company)
			->with('countries',$countries->pluck('countryname', 'id'))->with('cities',$cities->pluck('cityname', 'id'));
		} else {
			$company = Company::find($id);
			return view::make('creditrequests.manage')->with('title', 'Credit request')
			->with('company',$company);
		}
    }
	
	public function searchpending() {
		return $this->search(true, true, false);
	}
	
	public function searchpendingcustomer() {
		return $this->search(true, false, true);
	}
	
	public function searchstart() {
		if (Gate::allows('co_cr'))
			return $this->search(true, false, false);

		return $this->search(true, false, false);
	}

	public function search($startsearch = true, $pending = false, $pendingcustomer = false)
	{
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$creditstatuses = Creditstatus::all();
		$roles = Auth::User()->roles->pluck('id');
		if (Gate::allows('cr_ap') || Gate::allows('cr_of')) {
			$query = Company::with('creditrequests')->whereIn('companytype_id', [1, 3])->orderBy('companyname', 'asc');
		} else {
			$query = Company::with('creditrequests')->whereIn('companytype_id', [1, 3])->whereHas('roles', function ($q) use ($roles) {
				$q->whereIn('roles.id', $roles);
			})->orderBy('companyname', 'asc');
		}
		$companylist = $query->get();
		if (Input::get('company_id') != '0' && Input::get('company_id') != '') {
			$query = $query->where('id', Input::get('company_id'));
		}
		if (Input::get('country_id') != '0' && Input::get('country_id') != '') {
			$query = $query->where('country_id', '=', Input::get('country_id'));
		}
		if ($startsearch) {
			$companies = $query->where('confirmed', 1)->get();
			$query = Creditrequest::with('appointment')->whereIn('company_id', $companies->pluck('id'));
			if ($pending) {
				$title = 'Pending credit requests';
				$query = $query->where('creditstatus_id', '2');
			} else if ($pendingcustomer) {
				$title = 'Pending Credit Requests';
				$query = $query->whereIn('id', Auth::user()->creditrequestpendingcustomer()->pluck('id'));
			} else {
				$title = 'Search credit requests';
				if (Input::get('creditstatus_id') != '') {
					if (Input::get('creditstatus_id') != 0) {
						if (Input::get('creditstatus_id') == '-1')
							$query = $query->whereIn('creditstatus_id', [2, 4, 5, 6]);
						else
							$query = $query->where('creditstatus_id', '=', Input::get('creditstatus_id'));
					}
				} else {
					$query = $query->whereIn('creditstatus_id', [2, 4, 5, 6]);
					$showAllPending = true;
				}
			}

			$creditrequests = $query->get();

			if (Gate::allows('co_cr')) {
				$buyerCompany = Auth::user()->getBuyerCompany();
				if(isset($buyerCompany)) {
					$pendingCreditRequests = Creditrequest::where('company_id', $buyerCompany->id)->whereIn('creditstatus_id', [2, 4, 5, 6])->get();
					$previousApprovedCreditRequests = Creditrequest::where('company_id', $buyerCompany->id)->where('creditstatus_id', 1)->get();
					$previousRejectedCreditRequests = Creditrequest::where('company_id', $buyerCompany->id)->where('creditstatus_id', 3)->get();
				}
				return View('creditrequests.search')->with('title', "List credit requests")
					->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
					->with('creditstatuses', array('0' => 'All', '-1' => "Pending") + $creditstatuses->pluck('name', 'id')->all())
					->with('companies', array('0' => 'All') + $companylist->pluck('companyname', 'id')->all())
					->with('creditrequests', $creditrequests)
					->with('pendingCreditRequests', $pendingCreditRequests)
					->with('previousApprovedCreditRequests', $previousApprovedCreditRequests)
					->with('previousRejectedCreditRequests', $previousRejectedCreditRequests);
			}

			return View('creditrequests.search')->with('title', $title)
				->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
				->with('creditstatuses', array('0' => 'All', '-1' => "Pending") + $creditstatuses->pluck('name', 'id')->all())
				->with('companies', array('0' => 'All') + $companylist->pluck('companyname', 'id')->all())
				->with('creditrequests', $creditrequests)
				->with('showAllPending', isset($showAllPending) ? $showAllPending : false);
		} else {
			return View('creditrequests.search')->with('title', 'Search credit requests')
				->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
				->with('creditstatuses', array('0' => 'All', '-1' => "Pending") + $creditstatuses->pluck('name', 'id')->all())
				->with('companies', array('0' => 'All') + $companylist->pluck('companyname', 'id')->all());
		}
	}
	
	public function save(Request $request, $id = 0) {
		$numberRegex = "/^(\-)?(\d+|\d{1,3}(,\d{3})*)(\.\d+)?$/";
		//$phoneRegex = "/^\+\d+(-| )?\d+(-| )?\d+(-| )?\d+(-| )?\d+$/";
		$phoneRegex = "/^[\+|\(|\)|\d|\- ]*$/";

		if (Input::get('requesttype_id') == 1) {
			$rules = [
			'askedlimit' => 'required|numeric',
			'busrefname.*' => 'required|max:60',
			'busreflimit.*' => 'required|numeric',
			'busreftype.*' => 'required|max:60',
			'busreflength.*' => 'required|max:60',
			'busrefcount' => 'required|numeric|min:3',
			'busref_contact_name.*' => 'required|max:60',
			'busref_contact_mobile.*' => ['required', 'max:60', "regex:$phoneRegex"],
			'busref_contact_email.*' => 'required|max:60|email',
			//'bankfile' => 'required',
			'balancesheetitemy1value' => 'numeric|min:0|max:0',
			'balancesheetitemy2value' => 'numeric|min:0|max:0',
			'balancesheetitemy3value' => 'numeric|min:0|max:0',
			//'financialfile' => 'required',
			'incomestatementfrom' => 'required|date_format:j/n/Y',
			//'incomestatementto' => 'required|date_format:j/n/Y',
			'balancesheeton' => 'required|date_format:j/n/Y',			
			'Month1D.*' => 'numeric|required',
			'Month2D.*' => 'numeric|required',
			'Month3D.*' => 'numeric|required',
			'Month4D.*' => 'numeric|required',
			'Month5D.*' => 'numeric|required',
			'Month6D.*' => 'numeric|required',
			'Month1C.*' => 'numeric|required',
			'Month2C.*' => 'numeric|required',
			'Month3C.*' => 'numeric|required',
			'Month4C.*' => 'numeric|required',
			'Month5C.*' => 'numeric|required',
			'Month6C.*' => 'numeric|required',
			'incomestatementitemy1.*' => ['nullable', "numeric"],
			'incomestatementitemy2.*' => ['nullable', "numeric"],
			'incomestatementitemy3.*' => ['nullable', "numeric"],
			//'incomestatementitemy3.*' => ['nullable', "regex:$numberRegex"],
			'balancesheetitemy1.*' => 'numeric|nullable',
			'balancesheetitemy2.*' => 'numeric|nullable',
			'balancesheetitemy3.*' => 'numeric|nullable',
				];
				
		$customMessages = [
			'askedlimit.required' => 'Asked limit is required',
			'askedlimit.numeric' => 'Asked limit must be a number',
			'busrefname.*.required' => 'Credit reference name is required',
			'busrefname.*.max' => 'Credit reference name should not be more than 60 characters',
			'busreflimit.*.required' => 'Credit limit offered is required',
			'busreflimit.*.numeric' => 'Credit limit offered must be numeric',
			'busreftype.*.required' => 'Type of credit is required',
			'busreftype.*.max' => 'Type of credit should not be more than 60 characters',
			'busreflength.*.required' => 'Length of business is required',
			'busreflength.*.min' => 'Length of business should not be less than 0',
			'busreflength.*.numeric' => 'Length of business must be numeric',
			'busref_contact_name.*.required' => 'Contact name is required',
			'busref_contact_name.*.max' => 'Contact name should not be more than 60 characters',
			'busref_contact_email.*.required' => 'Contact email is required',
			'busref_contact_email.*.max' => 'Contact email should not be more than 60 characters',
			'busref_contact_email.*.email' => 'Contact email is invalid',
			'busref_contact_mobile.*.required' => 'Contact mobile is required',
			'busref_contact_mobile.*.max' => 'Contact mobile should not be more than 60 characters',
			'busref_contact_mobile.*.regex' => 'Contact mobile is invalid',
			'busrefcount.required' => 'At least three credit references must be entered',
			'busrefcount.min' => 'At least three credit references must be entered',
			'busrefcount.numeric' => 'At least three credit references must be entered',
			'bankfile.required' => 'Bank statement attachment missing',
			'balancesheetitemy1value.numeric' => 'Income statement balance must be 0.',
			'balancesheetitemy1value.min' => 'Income statement balance must be 0.',
			'balancesheetitemy1value.max' => 'Income statement balance must be 0.',
			'balancesheetitemy2value.numeric' => 'Balance must be 0',
			'balancesheetitemy2value.min' => 'Balance must be 0',
			'balancesheetitemy2value.max' => 'Balance must be 0',
			'balancesheetitemy3value.numeric' => 'Balance must be 0',
			'balancesheetitemy3value.min' => 'Balance must be 0',
			'balancesheetitemy3value.max' => 'Balance must be 0',
			'financialfile.required' => 'Financial statement attachment missing',
			'incomestatementfrom.required' => 'Income statement from date is required',
			'incomestatementfrom.format' => 'Income statement from date must be in d/m/yyyy format',
			'incomestatementto.required' => 'Income statement to date is required',
			'incomestatementto.format' => 'Income statement to date must be in d/m/yyyy format',
			'balancesheeton.required' => 'Banalce sheet on date is required',
			'balancesheeton.format' => 'Banalce sheet on date must be in d/m/yyyy format',
			'Month1D.*.numeric' => 'Bank statement fields must be numeric',
			'Month2D.*.numeric' => 'Bank statement fields must be numeric',
			'Month3D.*.numeric' => 'Bank statement fields must be numeric',
			'Month4D.*.numeric' => 'Bank statement fields must be numeric',
			'Month5D.*.numeric' => 'Bank statement fields must be numeric',
			'Month6D.*.numeric' => 'Bank statement fields must be numeric',
			'Month1C.*.numeric' => 'Bank statement fields must be numeric',
			'Month2C.*.numeric' => 'Bank statement fields must be numeric',
			'Month3C.*.numeric' => 'Bank statement fields must be numeric',
			'Month4C.*.numeric' => 'Bank statement fields must be numeric',
			'Month5C.*.numeric' => 'Bank statement fields must be numeric',
			'Month6C.*.numeric' => 'Bank statement fields must be numeric',
			'Month1D.*.required' => 'Bank statement fields are required',
			'Month2D.*.required' => 'Bank statement fields are required',
			'Month3D.*.required' => 'Bank statement fields are required',
			'Month4D.*.required' => 'Bank statement fields are required',
			'Month5D.*.required' => 'Bank statement fields are required',
			'Month6D.*.required' => 'Bank statement fields are required',
			'Month1C.*.required' => 'Bank statement fields are required',
			'Month2C.*.required' => 'Bank statement fields are required',
			'Month3C.*.required' => 'Bank statement fields are required',
			'Month4C.*.required' => 'Bank statement fields are required',
			'Month5C.*.required' => 'Bank statement fields are required',
			'Month6C.*.required' => 'Bank statement fields are required',
			'incomestatementitemy1.*.regex' => 'Income statement fields must be numeric',
			'incomestatementitemy1.*.required' => 'Income statement fields are required',
			'incomestatementitemy2.*.regex' => 'Income statement fields must be numeric',
			'incomestatementitemy2.*.required' => 'Income statement fields are required',
			'incomestatementitemy3.*.regex' => 'Income statement fields must be numeric',
			'incomestatementitemy3.*.required' => 'Income statement fields are required',
			'balancesheetitemy1.*.numeric' => 'Balance sheet fields must be numeric',
			'balancesheetitemy1.*.required' => 'Balance sheet fields are required',
			'balancesheetitemy2.*.numeric' => 'Balance sheet fields must be numeric',
			'balancesheetitemy2.*.required' => 'Balance sheet fields are required',
			'balancesheetitemy3.*.numeric' => 'Balance sheet fields must be numeric',
			'balancesheetitemy3.*.required' => 'Balance sheet fields are required',
			];		
		} else {
			$rules = [
			'justification' => 'required|max:190',
			'askedlimit' => 'required|numeric',
			'bankfile' => 'required',
			'financialfile' => 'required',
			'Month1D.*' => 'numeric|required',
			'Month2D.*' => 'numeric|required',
			'Month3D.*' => 'numeric|required',
			'Month4D.*' => 'numeric|required',
			'Month5D.*' => 'numeric|required',
			'Month6D.*' => 'numeric|required',
			'Month1C.*' => 'numeric|required',
			'Month2C.*' => 'numeric|required',
			'Month3C.*' => 'numeric|required',
			'Month4C.*' => 'numeric|required',
			'Month5C.*' => 'numeric|required',
			'Month6C.*' => 'numeric|required',
        ];
		$customMessages = [
			'justification.required' => 'Justification for credit increase is required',
			'justification.max' => 'Justification for credit increase should not be more than 190 characters',
			'askedlimit.required' => 'Asked limit is required',
			'askedlimit.numeric' => 'Asked limit must be a number',
			'bankfile.required' => 'Bank statement attachment missing',
			'financialfile.required' => 'Financial statement attachment missing',
			'Month1D.*.numeric' => 'Bank statement fields must be numeric',
			'Month2D.*.numeric' => 'Bank statement fields must be numeric',
			'Month3D.*.numeric' => 'Bank statement fields must be numeric',
			'Month4D.*.numeric' => 'Bank statement fields must be numeric',
			'Month5D.*.numeric' => 'Bank statement fields must be numeric',
			'Month6D.*.numeric' => 'Bank statement fields must be numeric',
			'Month1C.*.numeric' => 'Bank statement fields must be numeric',
			'Month2C.*.numeric' => 'Bank statement fields must be numeric',
			'Month3C.*.numeric' => 'Bank statement fields must be numeric',
			'Month4C.*.numeric' => 'Bank statement fields must be numeric',
			'Month5C.*.numeric' => 'Bank statement fields must be numeric',
			'Month6C.*.numeric' => 'Bank statement fields must be numeric',
			'Month1D.*.required' => 'Bank statement fields are required',
			'Month2D.*.required' => 'Bank statement fields are required',
			'Month3D.*.required' => 'Bank statement fields are required',
			'Month4D.*.required' => 'Bank statement fields are required',
			'Month5D.*.required' => 'Bank statement fields are required',
			'Month6D.*.required' => 'Bank statement fields are required',
			'Month1C.*.required' => 'Bank statement fields are required',
			'Month2C.*.required' => 'Bank statement fields are required',
			'Month3C.*.required' => 'Bank statement fields are required',
			'Month4C.*.required' => 'Bank statement fields are required',
			'Month5C.*.required' => 'Bank statement fields are required',
			'Month6C.*.required' => 'Bank statement fields are required',
			];		
		}
		
		$this->validate($request, $rules, $customMessages);		 
		 
		if (Input::has('company_id')) {
			$creditrequest = new Creditrequest;
			$creditrequest->created_by = Auth::user()->id;
			$creditrequest->limit = 0;
			$creditrequest->approved_by = 0;					
			$creditrequest->company_id = $id;
			$creditrequest->requesttype_id = Input::get('requesttype_id');
			$creditrequest->currency_id = Input::get('currency_id');
			$creditrequest->financialscurrency_id = Input::get('financialscurrency_id');
			if (Input::get('requesttype_id') == 1) {
				$creditrequest->creditstatus_id = 6;
			} else {
				$creditrequest->creditstatus_id = 2;
			}
			
		} else {
			$creditrequest = Creditrequest::find($id);
		}		
		
		if ($creditrequest->requesttype_id == 1) {
			$creditrequest->justification = "Initial request";		
		} else {
			
			$creditrequest->justification = Input::get('justification');		
		}
		if (Input::get('margindeposittype_id') == '') {
			$creditrequest->margindeposittype_id = 1;
		} else {
			$creditrequest->margindeposittype_id = Input::get('margindeposittype_id');
		}
		$creditrequest->tenor_id = Input::get('tenor_id');		
		$creditrequest->askedlimit = Input::get('askedlimit');
		$creditrequest->bankstatementstart = '2017-01-01' ;
		$date = date_create_from_format("j/n/Y",Input::get('incomestatementfrom'));
		$creditrequest->incomestatementfrom = $date->format('Y-m-d');
		$date = date_create_from_format("j/n/Y",Input::get('balancesheeton'));
		$creditrequest->balancesheeton = $date->format('Y-m-d');
		$creditrequest->updated_by = Auth::user()->id;
		$creditrequest->save();
		
		//bank statement attachment
		if (Input::get('bankfile') <> '' && Input::get('bankattachid') <> '') {
			$bankFiles = explode(',', Input::get('bankfile'));
			$bankAttachIds = explode(',', Input::get('bankattachid'));

			// Delete uploaded files deleted by user
			DB::table('attachments')->where([
				'attachable_id' => $creditrequest->id, 
				'attachmenttype_id' => '6',
				'attachable_type' => 'creditrequest'
				])->whereNotIn('id',  $bankAttachIds)->delete();

			for($i = 0; $i < count($bankAttachIds); $i++) {
				DB::table('attachments')->where('id', $bankAttachIds[$i])->update([
					'attachable_type' => 'creditrequest', 
					'attachable_id' => $creditrequest->id, 
					'description' => 'Bank statement', 
					'attachmenttype_id' => 6, 
					'filename' => $bankFiles[$i]
					]);
			}
		}
		
		//financial statement attachment
		if (Input::get('financialfile') <> '' && Input::get('financialattachid') <> '') {
			$financialFiles = explode(',', Input::get('financialfile'));
			$financialAttachIds = explode(',', Input::get('financialattachid'));

			// Delete uploaded files deleted by user
			DB::table('attachments')->where([
				'attachable_id' => $creditrequest->id, 
				'attachmenttype_id' => '8',
				'attachable_type' => 'creditrequest'
				])->whereNotIn('id',  $financialAttachIds)->delete();

			for($i = 0; $i < count($financialAttachIds); $i++) {
				DB::table('attachments')->where('id', $financialAttachIds[$i])->update([
					'attachable_type' => 'creditrequest', 
					'attachable_id' => $creditrequest->id, 
					'description' => 'Financial statement', 
					'attachmenttype_id' => 8, 
					'filename' => $financialFiles[$i]
					]);
			}
		}
		
		//personal guarantee attachment
		if (Input::get('personalguaranteefile') <> '' && Input::get('personalguaranteeattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $creditrequest->id)->where('attachmenttype_id', '11')
			->where('attachable_type', 'creditrequest')->where('id', '<>', Input::get('personalguaranteeattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('personalguaranteeattachid'))->update(['attachable_type' => 'creditrequest', 'attachable_id' => $creditrequest->id, 
			'description' => 'Personal guarantee', 'attachmenttype_id' => 11, 'filename' => Input::get('personalguaranteefile')]);
		}
		
		//corporate guarantee attachment
		if (Input::get('corporateguaranteefile') <> '' && Input::get('corporateguaranteeattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $creditrequest->id)->where('attachmenttype_id', '12')
			->where('attachable_type', 'creditrequest')->where('id', '<>', Input::get('corporateguaranteeattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('corporateguaranteeattachid'))->update(['attachable_type' => 'creditrequest', 'attachable_id' => $creditrequest->id, 
			'description' => 'Corporate guarantee', 'attachmenttype_id' => 12, 'filename' => Input::get('corporateguaranteefile')]);
		}
		
		//promissary note attachment
		if (Input::get('promissarynotefile') <> '' && Input::get('promissarynoteattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $creditrequest->id)->where('attachmenttype_id', '13')
			->where('attachable_type', 'creditrequest')->where('id', '<>', Input::get('promissarynoteattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('promissarynoteattachid'))->update(['attachable_type' => 'creditrequest', 'attachable_id' => $creditrequest->id, 
			'description' => 'Promissary note', 'attachmenttype_id' => 13, 'filename' => Input::get('promissarynotefile')]);
		}
		
		//security check attachment
		if (Input::get('securitycheckfile') <> '' && Input::get('securitycheckattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $creditrequest->id)->where('attachmenttype_id', '7')
			->where('attachable_type', 'creditrequest')->where('id', '<>', Input::get('securitycheckattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('securitycheckattachid'))->update(['attachable_type' => 'creditrequest', 'attachable_id' => $creditrequest->id, 
			'description' => 'Security check', 'attachmenttype_id' => 7, 'filename' => Input::get('securitycheckfile')]);
		}
		
		$i = 0;
		if (Input::has('busrefid')) {
			foreach (Input::get('busrefid') as $item) {
				if ($item == '' && Input::get('busrefdel')[$i] == '') {
					$creditrequestbusref  = new Creditrequestbusref(array(
						'busrefname' => Input::get('busrefname')[$i], 
						'busreflimit'=> Input::get('busreflimit')[$i], 
						'busreftype'=> Input::get('busreftype')[$i], 
						'busreflength'=> Input::get('busreflength')[$i],
						'contact_name'=> Input::get('busref_contact_name')[$i],
						'contact_email'=> Input::get('busref_contact_email')[$i],
						'contact_mobile'=> Input::get('busref_contact_mobile')[$i],					
					));
					$creditrequest->busrefs()->save($creditrequestbusref);
				} elseif ($item != '') {
					if (Input::get('busrefdel')[$i] == '') {
						$creditrequestbusref = Creditrequestbusref::find($item);
						$creditrequestbusref->busrefname = Input::get('busrefname')[$i];
						$creditrequestbusref->busreflimit = Input::get('busreflimit')[$i];
						$creditrequestbusref->busreftype = Input::get('busreftype')[$i];
						$creditrequestbusref->busreflength = Input::get('busreflength')[$i];
						$creditrequestbusref->contact_name = Input::get('busref_contact_name')[$i];
						$creditrequestbusref->contact_email = Input::get('busref_contact_email')[$i];
						$creditrequestbusref->contact_mobile = Input::get('busref_contact_mobile')[$i];
						$creditrequestbusref->save();
					} else {
						$creditrequestbusref = Creditrequestbusref::destroy($item);
					}
				}
				$i++;
			}
		}
		$i = 0;
		if (Input::has('title')) {
			foreach (Input::get('title') as $item) {
				if (Input::get('bankstatementid')[$i] == '') {
					$bankstatement = New Creditrequestbankstatement(array('title' => $item, 'order' => Input::get('order')[$i], 'Month1D' => Input::get('Month1D')[$i], 'Month2D' => Input::get('Month2D')[$i], 'Month3D' => Input::get('Month3D')[$i], 'Month4D' => Input::get('Month4D')[$i], 'Month5D' => Input::get('Month5D')[$i], 'Month6D' => Input::get('Month6D')[$i], 'Month1C' => Input::get('Month1C')[$i], 'Month2C' => Input::get('Month2C')[$i], 'Month3C' => Input::get('Month3C')[$i], 'Month4C' => Input::get('Month4C')[$i], 'Month5C' => Input::get('Month5C')[$i], 'Month6C' => Input::get('Month6C')[$i]));
					$creditrequest->bankstatements()->save($bankstatement);
				} else {
					$bankstatement = Creditrequestbankstatement::find(Input::get('bankstatementid')[$i]);
					$bankstatement->Month1D = Input::get('Month1D')[$i];
					$bankstatement->Month2D = Input::get('Month2D')[$i];
					$bankstatement->Month3D = Input::get('Month3D')[$i];
					$bankstatement->Month4D = Input::get('Month4D')[$i];
					$bankstatement->Month5D = Input::get('Month5D')[$i];
					$bankstatement->Month6D = Input::get('Month6D')[$i];
					$bankstatement->Month1C = Input::get('Month1C')[$i];
					$bankstatement->Month2C = Input::get('Month2C')[$i];
					$bankstatement->Month3C = Input::get('Month3C')[$i];
					$bankstatement->Month4C = Input::get('Month4C')[$i];
					$bankstatement->Month5C = Input::get('Month5C')[$i];
					$bankstatement->Month6C = Input::get('Month6C')[$i];
					$bankstatement->save();
				}							
				$i++;
			}			
		}
		//income statement
		$i = 0;
		if (Input::has('incomestatementid')) {
			$gpy1 = 0;
			$gpy2 = 0;
			$gpy3 = 0;
			$revenuey1 = 0;
			$revenuey2 = 0;
			$revenuey3 = 0;
			$sgay1 = 0;
			$sgay2 = 0;
			$sgay3 = 0;
			$inti1 = 0;
			$inti2 = 0;
			$inti3 = 0;
			$inte1 = 0;
			$inte2 = 0;
			$inte3 = 0;
			$otheriy1 = 0;
			$otheriy2 = 0;
			$otheriy3 = 0;
			$otherey1 = 0;
			$otherey2 = 0;
			$otherey3 = 0;
			foreach (Input::get('incomestatementid') as $item) {
				if (Input::get('incomestatementid')[$i] == '') {
					$incomestatement = New Creditrequestincomestatement(
						array('incomestatementitem_id' => Input::get('incomestatementitem_id')[$i], 
						'order' => Input::get('order')[$i],
						'incomestatementitemy1' => str_replace( ',', '', trim(Input::get('incomestatementitemy1')[$i]) == '' ? 0 : Input::get('incomestatementitemy1')[$i]),
						'incomestatementitemy2' => str_replace( ',', '', trim(Input::get('incomestatementitemy2')[$i]) == '' ? 0 : Input::get('incomestatementitemy2')[$i]),
						'incomestatementitemy3' => str_replace( ',', '', trim(Input::get('incomestatementitemy3')[$i]) == '' ? 0 : Input::get('incomestatementitemy3')[$i])));					
					$creditrequest->incomestatements()->save($incomestatement);
				} else {
					\Log::debug(str_replace( ',', '', Input::get('incomestatementitemy1')[$i]));
					$incomestatement = Creditrequestincomestatement::find(Input::get('incomestatementid')[$i]);
					$incomestatement->incomestatementitem_id = Input::get('incomestatementitem_id')[$i];
					$incomestatement->incomestatementitemy1 = str_replace( ',', '', trim(Input::get('incomestatementitemy1')[$i]) == '' ? 0 : Input::get('incomestatementitemy1')[$i]);
					$incomestatement->incomestatementitemy2 = str_replace( ',', '', trim(Input::get('incomestatementitemy2')[$i]) == '' ? 0 : Input::get('incomestatementitemy2')[$i]);
					$incomestatement->incomestatementitemy3 = str_replace( ',', '', trim(Input::get('incomestatementitemy3')[$i]) == '' ? 0 : Input::get('incomestatementitemy3')[$i]);
					$incomestatement->save();
				}							
				$i++;
				if ($incomestatement->incomestatementitem_id == 4 || $incomestatement->incomestatementitem_id == 5) {
					if ($incomestatement->incomestatementitem_id == 4) {
						$gpy1 = $gpy1 + $incomestatement->incomestatementitemy1;
						$gpy2 = $gpy2 + $incomestatement->incomestatementitemy2;
						$gpy3 = $gpy3 + $incomestatement->incomestatementitemy3;
						$revenuey1 = $incomestatement->incomestatementitemy1;
						$revenuey2 = $incomestatement->incomestatementitemy2;
						$revenuey3 = $incomestatement->incomestatementitemy3;
					} else {
						$gpy1 = $gpy1 + $incomestatement->incomestatementitemy1;
						$gpy2 = $gpy2 + $incomestatement->incomestatementitemy2;
						$gpy3 = $gpy3 + $incomestatement->incomestatementitemy3;
					}					
				} else if ($incomestatement->incomestatementitem_id == 8) {
					$sgay1 = $incomestatement->incomestatementitemy1;
					$sgay2 = $incomestatement->incomestatementitemy2;
					$sgay3 = $incomestatement->incomestatementitemy3;
				} else if ($incomestatement->incomestatementitem_id == 11) {
					$inti1 = $incomestatement->incomestatementitemy1;
					$inti2 = $incomestatement->incomestatementitemy2;
					$inti3 = $incomestatement->incomestatementitemy3;
				} else if ($incomestatement->incomestatementitem_id == 12) {
					$inte1 = $incomestatement->incomestatementitemy1;
					$inte2 = $incomestatement->incomestatementitemy2;
					$inte3 = $incomestatement->incomestatementitemy3;
				} else if ($incomestatement->incomestatementitem_id == 13) {
					$otheriy1 = $incomestatement->incomestatementitemy1;
					$otheriy2 = $incomestatement->incomestatementitemy2;
					$otheriy3 = $incomestatement->incomestatementitemy3;
				} else if ($incomestatement->incomestatementitem_id == 14) {
					$otherey1 = $incomestatement->incomestatementitemy1;
					$otherey2 = $incomestatement->incomestatementitemy2;
					$otherey3 = $incomestatement->incomestatementitemy3;
				}
			}
			// income statement calculations
			if ($revenuey1 != 0) {
				$gppy1 = $gpy1 / $revenuey1;
				$ebtidapy1 = ($gpy1 + $sgay1) / $revenuey1;
				$netincomepy1 = ($gpy1 + $sgay1 + $inti1 + $inte1 + $otheriy1 + $otherey1) / $revenuey1;
			} else {
				$gppy1 = 0;
				$ebtidapy1 = 0;
				$netincomepy1 = 0;
			}
			if ($revenuey2 != 0) {
				$gppy2 = $gpy2 / $revenuey2;
				$ebtidapy2 = ($gpy2 + $sgay2) / $revenuey2;
				$netincomepy2 = ($gpy2 + $sgay2 + $inti2 + $inte2 + $otheriy2 + $otherey2) / $revenuey2;
			} else {
				$gppy2 = 0;
				$ebtidapy2 = 0;
				$netincomepy2 = 0;
			}
			if ($revenuey3 != 0) {
				$gppy3 = $gpy3 / $revenuey3;
				$ebtidapy3 = ($gpy3 + $sgay3) / $revenuey3;
				$netincomepy3 = ($gpy3 + $sgay3 + $inti3 + $inte3 + $otheriy3 + $otherey3) / $revenuey3;
			} else {
				$gppy3 = 0;
				$ebtidapy3 = 0;
				$netincomepy3 = 0;
			}
			
			$incomestatement = $creditrequest->incomestatements()->firstOrNew(['incomestatementitem_id' => 6]);
			$incomestatement->incomestatementitem_id = 6;
			$incomestatement->incomestatementitemy1 = $gpy1;
			$incomestatement->incomestatementitemy2 = $gpy2;
			$incomestatement->incomestatementitemy3 = $gpy3;
			$incomestatement->order = 3;
			$creditrequest->incomestatements()->save($incomestatement);
			$incomestatement = $creditrequest->incomestatements()->firstOrNew(['incomestatementitem_id' => 7]);
			$incomestatement->incomestatementitem_id = 7;
			$incomestatement->incomestatementitemy1 = $gppy1;
			$incomestatement->incomestatementitemy2 = $gppy2;
			$incomestatement->incomestatementitemy3 = $gppy3;
			$incomestatement->order = 4;
			$creditrequest->incomestatements()->save($incomestatement);
			$incomestatement = $creditrequest->incomestatements()->firstOrNew(['incomestatementitem_id' => 9]);
			$incomestatement->incomestatementitem_id = 9;
			$incomestatement->incomestatementitemy1 = $gpy1 + $sgay1;
			$incomestatement->incomestatementitemy2 = $gpy2 + $sgay2;
			$incomestatement->incomestatementitemy3 = $gpy3 + $sgay3;
			$incomestatement->order = 6;
			$creditrequest->incomestatements()->save($incomestatement);
			$incomestatement = $creditrequest->incomestatements()->firstOrNew(['incomestatementitem_id' => 10]);
			$incomestatement->incomestatementitem_id = 10;
			$incomestatement->incomestatementitemy1 = $ebtidapy1;
			$incomestatement->incomestatementitemy2 = $ebtidapy2;
			$incomestatement->incomestatementitemy3 = $ebtidapy3;
			$incomestatement->order = 7;
			$creditrequest->incomestatements()->save($incomestatement);
			$incomestatement = $creditrequest->incomestatements()->firstOrNew(['incomestatementitem_id' => 15]);
			$incomestatement->incomestatementitem_id = 15;
			$incomestatement->incomestatementitemy1 = $gpy1 + $sgay1 + $inti1 + $inte1 + $otheriy1 + $otherey1;
			$incomestatement->incomestatementitemy2 = $gpy2 + $sgay2 + $inti2 + $inte2 + $otheriy2 + $otherey2;
			$incomestatement->incomestatementitemy3 = $gpy3 + $sgay3 + $inti3 + $inte3 + $otheriy3 + $otherey3;
			$incomestatement->order = 12;
			$creditrequest->incomestatements()->save($incomestatement);
			$incomestatement = $creditrequest->incomestatements()->firstOrNew(['incomestatementitem_id' => 16]);
			$incomestatement->incomestatementitem_id = 16;
			$incomestatement->incomestatementitemy1 = $netincomepy1;
			$incomestatement->incomestatementitemy2 = $netincomepy2;
			$incomestatement->incomestatementitemy3 = $netincomepy3;
			$incomestatement->order = 13;
			$creditrequest->incomestatements()->save($incomestatement);
		}		

		//balance sheet
		$i = 0;
		if (Input::has('balancesheetid')) {
			$currentassetsy1 = 0;
			$currentassetsy2 = 0;
			$currentassetsy3 = 0;
			$noncurrentassetsy1 = 0;
			$noncurrentassetsy2 = 0;
			$noncurrentassetsy3 = 0;
			$currentliabilitiesy1 = 0;
			$currentliabilitiesy2 = 0;
			$currentliabilitiesy3 = 0;
			$noncurrentliabilitiesy1 = 0;
			$noncurrentliabilitiesy2 = 0;
			$noncurrentliabilitiesy3 = 0;
			$totalequityy1 = 0;
			$totalequityy2 = 0;
			$totalequityy3 = 0;
			foreach (Input::get('balancesheetid') as $item) {
				if (Input::get('balancesheetid')[$i] == '') {
					$balancesheet = New Creditrequestbalancesheet(
					array('balancesheetitem_id' => Input::get('balancesheetitem_id')[$i], 
					'order' => Input::get('bsorder')[$i], 
					'balancesheetitemy1' => trim(Input::get('balancesheetitemy1')[$i]) == '' ? 0 : Input::get('balancesheetitemy1')[$i], 
					'balancesheetitemy2' => trim(Input::get('balancesheetitemy2')[$i]) == '' ? 0 : Input::get('balancesheetitemy2')[$i], 
					'balancesheetitemy3' => trim(Input::get('balancesheetitemy3')[$i]) == '' ? 0 : Input::get('balancesheetitemy3')[$i]));
					$creditrequest->balancesheets()->save($balancesheet);
				} else {
					$balancesheet = Creditrequestbalancesheet::find(Input::get('balancesheetid')[$i]);
					$balancesheet->balancesheetitem_id = Input::get('balancesheetitem_id')[$i];
					$balancesheet->balancesheetitemy1 = trim(Input::get('balancesheetitemy1')[$i]) == '' ? 0 : Input::get('balancesheetitemy1')[$i];
					$balancesheet->balancesheetitemy2 = trim(Input::get('balancesheetitemy2')[$i]) == '' ? 0 : Input::get('balancesheetitemy2')[$i];
					$balancesheet->balancesheetitemy3 = trim(Input::get('balancesheetitemy3')[$i]) == '' ? 0 : Input::get('balancesheetitemy3')[$i];
					$balancesheet->save();
				}							
				$i++;
				if ($balancesheet->balancesheetitem_id == 3 || $balancesheet->balancesheetitem_id == 4 || $balancesheet->balancesheetitem_id == 5 || $balancesheet->balancesheetitem_id == 6 || $balancesheet->balancesheetitem_id == 7 || $balancesheet->balancesheetitem_id == 29 || $balancesheet->balancesheetitem_id == 10 || $balancesheet->balancesheetitem_id == 11) {
					if ($balancesheet->balancesheetitem_id == 3 || $balancesheet->balancesheetitem_id == 4 || $balancesheet->balancesheetitem_id == 5 || $balancesheet->balancesheetitem_id == 6 || $balancesheet->balancesheetitem_id == 7 || $balancesheet->balancesheetitem_id == 29) {
						$currentassetsy1 = $currentassetsy1 + $balancesheet->balancesheetitemy1;
						$currentassetsy2 = $currentassetsy2 + $balancesheet->balancesheetitemy2;
						$currentassetsy3 = $currentassetsy3 + $balancesheet->balancesheetitemy3;
					} else {
						$noncurrentassetsy1 = $noncurrentassetsy1 + $balancesheet->balancesheetitemy1;
						$noncurrentassetsy2 = $noncurrentassetsy2 + $balancesheet->balancesheetitemy2;
						$noncurrentassetsy3 = $noncurrentassetsy3 + $balancesheet->balancesheetitemy3;
					}
				} else if ($balancesheet->balancesheetitem_id == 16 || $balancesheet->balancesheetitem_id == 17 || $balancesheet->balancesheetitem_id == 18 || $balancesheet->balancesheetitem_id == 28 || $balancesheet->balancesheetitem_id == 20 || $balancesheet->balancesheetitem_id == 21) {
					if ($balancesheet->balancesheetitem_id == 16 || $balancesheet->balancesheetitem_id == 17 || $balancesheet->balancesheetitem_id == 18 || $balancesheet->balancesheetitem_id == 28) {
						$currentliabilitiesy1 = $currentliabilitiesy1 + $balancesheet->balancesheetitemy1;
						$currentliabilitiesy2 = $currentliabilitiesy2 + $balancesheet->balancesheetitemy2;
						$currentliabilitiesy3 = $currentliabilitiesy3 + $balancesheet->balancesheetitemy3;
					} else {
						$noncurrentliabilitiesy1 = $noncurrentliabilitiesy1 + $balancesheet->balancesheetitemy1;
						$noncurrentliabilitiesy2 = $noncurrentliabilitiesy2 + $balancesheet->balancesheetitemy2;
						$noncurrentliabilitiesy3 = $noncurrentliabilitiesy3 + $balancesheet->balancesheetitemy3;
					}
				} else if ($balancesheet->balancesheetitem_id == 24 || $balancesheet->balancesheetitem_id == 25) {
					$totalequityy1 = $totalequityy1 + $balancesheet->balancesheetitemy1;
					$totalequityy2 = $totalequityy2 + $balancesheet->balancesheetitemy2;
					$totalequityy3 = $totalequityy3 + $balancesheet->balancesheetitemy3;
				}
			}
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 8]);
			$balancesheet->balancesheetitem_id = 8;
			$balancesheet->balancesheetitemy1 = $currentassetsy1;
			$balancesheet->balancesheetitemy2 = $currentassetsy2;
			$balancesheet->balancesheetitemy3 = $currentassetsy3;
			$balancesheet->order = 9;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 12]);
			$balancesheet->balancesheetitem_id = 12;
			$balancesheet->balancesheetitemy1 = $noncurrentassetsy1;
			$balancesheet->balancesheetitemy2 = $noncurrentassetsy2;
			$balancesheet->balancesheetitemy3 = $noncurrentassetsy3;
			$balancesheet->order = 13;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 13]);
			$balancesheet->balancesheetitem_id = 13;
			$balancesheet->balancesheetitemy1 = $currentassetsy1 + $noncurrentassetsy1;
			$balancesheet->balancesheetitemy2 = $currentassetsy2 + $noncurrentassetsy2;
			$balancesheet->balancesheetitemy3 = $currentassetsy3 + $noncurrentassetsy3;
			$balancesheet->order = 14;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 19]);
			$balancesheet->balancesheetitem_id = 19;
			$balancesheet->balancesheetitemy1 = $currentliabilitiesy1;
			$balancesheet->balancesheetitemy2 = $currentliabilitiesy2;
			$balancesheet->balancesheetitemy3 = $currentliabilitiesy3;
			$balancesheet->order = 21;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 22]);
			$balancesheet->balancesheetitem_id = 22;
			$balancesheet->balancesheetitemy1 = $noncurrentliabilitiesy1;
			$balancesheet->balancesheetitemy2 = $noncurrentliabilitiesy2;
			$balancesheet->balancesheetitemy3 = $noncurrentliabilitiesy3;
			$balancesheet->order = 24;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 23]);
			$balancesheet->balancesheetitem_id = 23;
			$balancesheet->balancesheetitemy1 = $currentliabilitiesy1 + $noncurrentliabilitiesy1;
			$balancesheet->balancesheetitemy2 = $currentliabilitiesy2 + $noncurrentliabilitiesy2;
			$balancesheet->balancesheetitemy3 = $currentliabilitiesy3 + $noncurrentliabilitiesy3;
			$balancesheet->order = 25;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 26]);
			$balancesheet->balancesheetitem_id = 26;
			$balancesheet->balancesheetitemy1 = $totalequityy1;
			$balancesheet->balancesheetitemy2 = $totalequityy2;
			$balancesheet->balancesheetitemy3 = $totalequityy3;
			$balancesheet->order = 28;
			$creditrequest->balancesheets()->save($balancesheet);
			$balancesheet = $creditrequest->balancesheets()->firstOrNew(['balancesheetitem_id' => 27]);
			$balancesheet->balancesheetitem_id = 27;
			$balancesheet->balancesheetitemy1 = $currentliabilitiesy1 + $noncurrentliabilitiesy1 + $totalequityy1;
			$balancesheet->balancesheetitemy2 = $currentliabilitiesy2 + $noncurrentliabilitiesy2 + $totalequityy2;
			$balancesheet->balancesheetitemy3 = $currentliabilitiesy3 + $noncurrentliabilitiesy3 + $totalequityy3;
			$balancesheet->order = 29;
			$creditrequest->balancesheets()->save($balancesheet);
		}
		
		$creditrequest = Creditrequest::find($creditrequest->id);
		if ($creditrequest->isapprovable && $creditrequest->creditstatus_id == 4) {
			$creditrequest->creditstatus_id = 2;
			$creditrequest->save();
		}
		//return $this->view($creditrequest->id);
		return redirect('/creditrequests/view/' . $creditrequest->id);
	}
	
	public function signature($code) {
		$authcode  = substr($code, 0, 20);
		$id = substr($code, 20, 20);
		$creditrequest = Creditrequest::find($id);
		$pendingecurities = $creditrequest->securities()->where('authcode', $authcode)->get();
		
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
		$recipientName = $creditrequestsecurities->first()->signername;
		$recipientEmail = $creditrequestsecurities->first()->signeremail;
				
		// instantiate a new envelopeApi object
		$envelopeApi = new docusignclient\Api\EnvelopesApi($apiClient);		
		$documents = array();
		
		$i = 1;
		foreach ($creditrequestsecurities as $security) {
			// configure the document we want signed
			if ($security->securitytype_id == 1) {
				$documentFileName = "personalguarantee.pdf";
				$documentName = "personalguarantee-s.pdf";
			} elseif ($security->securitytype_id == 2) {
				$documentFileName = "corporateguarantee.pdf";
				$documentName = "corporateguarantee-s.pdf";
			} elseif ($security->securitytype_id == 3) {
				$documentFileName = "promissarynote.pdf";
				$documentName = "promissarynote-s.pdf";
			}
			
			// Add a document to the envelope
			$document = new docusignclient\Model\Document();
			$document->setDocumentBase64(base64_encode(file_get_contents(str_replace('\\', '/', storage_path()) . '/app/templates/' . $documentFileName)));
			$document->setName($documentName);
			//$docid = (string)$i;
			$document->setDocumentId("$i");
			$security->document_id = "$i";
			$security->save();
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
		$envelop_definition->setEmailSubject("Securities");

		// set envelope status to "sent" to immediately send the signature request
		$envelop_definition->setStatus("sent");
		$envelop_definition->setRecipients($recipients);
		$envelop_definition->setDocuments($documents);

		// create and send the envelope! (aka signature request)
		$envelop_summary = $envelopeApi->createEnvelope($accountId, $envelop_definition, null);

		DB::table('creditrequestsecurities')
            ->where('creditrequest_id', $id)
			->where('authcode', $authcode)
            ->update(['envelope' => $envelop_summary->getEnvelopeId()]);
		
		
		
		/////////////////////////////////////////////////////////////////////////
		// STEP 3:  Recipient View
		/////////////////////////////////////////////////////////////////////////
		// instantiate a RecipientViewRequest object
		$recipient_view_request = new docusignclient\Model\RecipientViewRequest();
		// set where the recipient is re-directed once they are done signing
		$recipient_view_request->setReturnUrl("http://projectx.metragroup.com/signature/" . $envelop_summary->getEnvelopeId());
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
		$creditrequestsecurities = Creditrequestsecurity::where('envelope', $envelope)->get();
		foreach ($creditrequestsecurities as $security) {
			$security->status = Input::get('event');
			if (Input::get('event') == 'signing_complete') {
				$security->authcode = null;
			}
			$security->save();
			$creditrequest = Creditrequest::find($security->creditrequest_id);
			$creditrequest->updatestatus();
		}
		if (Input::get('event') == 'signing_complete') {
				Processsecuritiessignature::dispatch($envelope);
			}				
		if (Auth::guest()) {
			if (Input::get('event') == 'signing_complete') {
				return view('message')->with('title', 'Sign document')->with('message', 'Thank you for signing the document(s)');		
			} else {
				return view('message')->with('title', 'Sign document')->with('message', 'Signing not complete. Please try again.');		
			}				
		} else {
			return redirect('/creditrequests/view/' . $creditrequest->id);
		}			
	}
	
	public function signing111111111($code) {
		$creditrequests = Creditrequest::where('personalguaranteecode', $code)
		->orWhere('corporateguaranteecode', $code)
		->orWhere('promissarynotecode', $code)
		->get();
		if ($creditrequests->count() == 0) {
			return view('message')->with('title', 'Sign document')->with('message', 'This link has expired');		
		} else {
			$creditrequest = $creditrequests->first();
			if ($creditrequest->personalguaranteecode == $code) {
				return redirect('/cr/signature/pg/' . $creditrequest->id);
			} elseif ($creditrequest->corporateguaranteecode == $code) {
				return redirect('/cr/signature/cg/' . $creditrequest->id);
			} elseif ($creditrequest->promissarynotecode == $code) {
				return redirect('/cr/signature/pn/' . $creditrequest->id);
			}
		}
	}
	
	private function getCreditRequest($id) {
		$creditrequest = Creditrequest::with('busrefs', 'balancesheets','attachments', 'incomestatements')->find($id);
		$creditrequest->incomestatements = $creditrequest->incomestatements->sortBy('order');
		$creditrequest->balancesheets = $creditrequest->balancesheets->sortBy('order');
		$creditrequest->bankStatements = $creditrequest->attachments->where('attachmenttype_id', 6)->all();
		$creditrequest->financials = $creditrequest->attachments->where('attachmenttype_id', 8)->all();
		return $creditrequest;
	}

	public function markChequeAsRecieved($id) {
		$securityCheque = Creditrequestsecurity::where('id', $id)->whereIn('securitytype_id', [4, 6, 7])->firstOrFail();
		$securityCheque->status = 'signing_complete';
		$securityCheque->save();
		if ($securityCheque->securitytype_id == Securitytype::SECURITY_CHEQUE) {
			ProcessSecurityChequeSap::dispatch($securityCheque);
		}
		// Check if all securities completed and update company credit check
		if ($securityCheque->creditrequest->isSecuritesCompleted()) {
			$securityCheque->creditrequest->approveCredit();
			$securityCheque->creditrequest->updatestatus();
		}
	}
}
