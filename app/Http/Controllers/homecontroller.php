<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Gate;
use Input;
use Session;
use View;

use App\Actiontoken;
use App\Appointment;
use App\Creditrequest;
use App\Company;
use App\Support;
use App\Status;
use App\Creditstatus;
use App\Companytype;
use App\Shippingaddress;
use App\Shippinginquiry;

class homecontroller extends Controller     
{
	public function index(Request $request) 
	{
		//dd($request);
		//var_dump(Session::get('errors'));
		//die;
		$appointmentpending = [];
		$appointmentpendingconfirmation = [];
		$appointmentpendingvisit = [];
		$appointmentconfirmed = [];
		$appointmentPendingCompleteSiteVisit = [];
		$appointmenttoday = [];
		$appointmenttomorrow = [];
		$creditRequestPendingAction = [];
		$creditRequestPendingInfo = [];
		$inactivecompany = [];
		$unconfirmedcompany = [];
		$unsignedcompany = [];
		$posPendingVendor = [];
		$posPendingVendorInfo = [];
		$posPendingVendorAction = [];
		$posPendingCreditInfo = [];
		$posPendingCreditAction = [];
		$posPendingCustomerAction = [];
		$posPendingCustomerInfo = [];
		$creditrequestpendingcustomer = [];
		$creditRequestPendingCustomerInfo = [];
		$quotationsPendingVendorInfo = [];
		$quotationsPendingVendorAction = [];
		$quotationsPendingCustomer = [];
		$opensupport = [];
		$pendingvatexemptrequest = [];
		$shpinqsPending= [];
		if (!Auth::guest()) {
			$roles = Auth::User()->roles->pluck('id');

			$customerCompanies = Company::whereIn('companytype_id', [Companytype::BOTH_TYPE, Companytype::BUYER_TYPE])->whereHas('roles', function ($q) use($roles) {
				$q->whereIn('roles.id', $roles);
			})->get();

			$vendorCompanies = Company::whereIn('companytype_id', [Companytype::BOTH_TYPE, Companytype::SUPPLIER_TYPE])->whereHas('roles', function ($q) use($roles) {
				$q->whereIn('roles.id', $roles);
			})->get();

			//prepare the dates
			$date = date_create(date('Ymd'));
			$from = date_format($date,"Y-m-d");
			date_add($date,date_interval_create_from_date_string("1 days"));
			$to = date_format($date,"Y-m-d");	
			// appointments for credit approver
			if (Gate::allows('cr_ap') || Gate::allows('cr_of')) {
				$appointmentpendingconfirmation = Appointment::where('date', '>=', $from)->where('status_id', 1)->get();
				// show complete site visit for credit
				$appointmentPendingCompleteSiteVisit =  Appointment::where('date', '>=', $from)->where('status_id', 8)->get();
				$appointments = Appointment::whereBetween('date', array($from, $to))->where('status_id', 8)->get();
				// foreach ($appointments as $appointment) {
				// 	if ($appointment->date == date('Y-m-d')) {
				// 		$appointmenttoday[] = $appointment;
				// 	} else {
				// 		$appointmenttomorrow[] = $appointment;
				// 	}
				// }
				$creditRequestPendingAction = Creditrequest::where('creditstatus_id', Creditstatus::PENDING_CREDIT_DECISION)->get();
				$creditRequestPendingInfo = Creditrequest::where('creditstatus_id', Creditstatus::PENDING_RECEIPT_OF_SECURITIES)->get();
				if (Gate::allows('cr_ap')) {
					$pendingvatexemptrequest = Shippingaddress::where('vatexempt', 1)->get();
				}
			} else {
				if ($customerCompanies->count() > 0) {
					//appointments for customers
					$appointments = Auth::user()->pendingcustomerappointments();
					foreach ($appointments as $appointment) {
						if ($appointment->status_id == 1) {
							$appointmentpending[] = $appointment;
						} elseif(Gate::denies('cr_ap')) {
							$appointmentconfirmed[] = $appointment;
						}
					}
					$quotationsPendingCustomer = Auth::user()->quotationsPendingCustomer();
				}
			}
			if (Gate::allows('qu_cr') && $vendorCompanies->count() > 0) {
				$quotationsPendingVendor = Auth::user()->quotationsPendingVendor();
				foreach($quotationsPendingVendor as $quotation) {
					if($quotation->status_id == Status::QU_PENDING_BUYER_APPROVAL)
						$quotationsPendingVendorInfo[] = $quotation;
					else
						$quotationsPendingVendorAction[] = $quotation;
				}
			}
			if (Gate::allows('co_cr')) {
				$supplierCompany = Auth::User()->getSupplierCompany();
				$buyerCompany = Auth::User()->getBuyerCompany();

				if($supplierCompany && !$supplierCompany->active && $supplierCompany->confirmed) {
					$inactivecompany[] = $supplierCompany;
				} elseif($buyerCompany && !$buyerCompany->active && $buyerCompany->confirmed) {
					$inactivecompany[] = $buyerCompany;
				}
				
				if($supplierCompany && !$supplierCompany->confirmed) {
					$unconfirmedcompany[] = $supplierCompany;
				} elseif($buyerCompany && !$buyerCompany->confirmed) {
					$unconfirmedcompany[] = $buyerCompany;
				}
				if($supplierCompany && $supplierCompany->active && !$supplierCompany->vendor_signed) {
					$unsignedcompany[] = $supplierCompany;
				} elseif($buyerCompany && $buyerCompany->active && !$buyerCompany->customer_signed) {
					$unsignedcompany[] = $buyerCompany;
				}
			}
			if (Gate::allows('po_sc')) {
				$posPendingVendor = Auth::user()->pospendingvendor();
				$pospendingcustomer = Auth::user()->pospendingcustomer();
				foreach($pospendingcustomer as $po) {
					if($po->status_id == Status::PO_PENDING_SUPPLIER_APPROVAL || $po->status_id == Status::PO_PENDING_CREDIT_DECISION) {
						$posPendingCustomerInfo[] = $po;
					} else {
						$posPendingCustomerAction[] = $po;
					}
				}
				
				foreach($posPendingVendor as $po) {
					if($po->status_id == Status::PO_PENDING_BUYER_POD_SIGNATURE) {
						$posPendingVendorInfo[] = $po;
					} else {
						$posPendingVendorAction[] = $po;
					}
				}
			}
			if (Gate::allows('po_rc')) {
				$pospendingcredit = Auth::user()->pospendingcredit($includePendingSuppApproval = true);
				foreach($pospendingcredit as $po) {
					if ($po->status_id == Status::PO_PENDING_SUPPLIER_APPROVAL)
						$posPendingCreditInfo[] = $po;
					else
						$posPendingCreditAction[] = $po;
				}
			}
			if (Gate::allows('cr_cr') || Gate::allows('cr_ch') || Gate::allows('cr_vw')) {
				$creditrequestpendingcustomer = Auth::user()->creditrequestpendingcustomer();
				$creditRequestPendingCustomerInfo = Auth::user()->creditRequestPendingCustomerInfo();
			}
			if (Gate::allows('su_ch')) {
				$opensupport = Support::where('status_id', Status::SUPPORT_OPEN)->get();
			}
		}
		
		$id = $request->id;
		$token = $request->token;
		if ($id && $token) {
			session(['id' => $id, 'token' => $token]);			
		} else {
			session(['id' => '', 'token' => '']);			
		}
		if (session('id') != '' && session('token') != '') {
			$actiontokens = Actiontoken::where('id', session('id'))->where('token', session('token'))->get();			
			if ($actiontokens->count() == 0) {
				session(['id' => '', 'token' => '']);			
				return view('message',[
					'title' => 'Invalid token',
					'message' => 'Cannot deregister. Supplied token is invalid.',
					'error' => true,
					'home_link' => 'true'
				]);
			} else {
				$company = Company::find($actiontokens->first()->object_id);
				$company->deregister(session('id'), session('token'));
			}
		}
		$user = Auth::user();
		if(isset($user) && isset($user->companies->first()->id))
			$shpinqsPending = Shippinginquiry::where('company_id', $user->companies->first()->id)->get();
		return view('home',[
			'errors' => Session::get('errors') ? Session::get('errors') : collect([]),
			'appointmenttoday' => $appointmenttoday,
			'appointmenttomorrow' => $appointmenttomorrow,
			'appointmentpending' => $appointmentpending,
			'appointmentconfirmed' => $appointmentconfirmed,
			'appointmentpendingconfirmation' => $appointmentpendingconfirmation,
			'appointmentPendingCompleteSiteVisit' => $appointmentPendingCompleteSiteVisit,
			// 'appointmentpendingvisit' => $appointmentpendingvisit,
			'inactivecompany' => $inactivecompany,
			'unconfirmedcompany' => $unconfirmedcompany,
			'unsignedcompany' => $unsignedcompany,
			'creditRequestPendingAction' => $creditRequestPendingAction,
			'creditRequestPendingInfo' => $creditRequestPendingInfo,
			'posPendingCustomerInfo' => $posPendingCustomerInfo,
			'posPendingCustomerAction' => $posPendingCustomerAction,
			'posPendingVendorAction' => $posPendingVendorAction,
			'posPendingVendorInfo' => $posPendingVendorInfo,
			'posPendingCreditInfo' => $posPendingCreditInfo,
			'posPendingCreditAction' => $posPendingCreditAction,
			'creditrequestpendingcustomer' => $creditrequestpendingcustomer,
			'creditRequestPendingCustomerInfo' => $creditRequestPendingCustomerInfo,
			'opensupport' => $opensupport,
			'quotationsPendingVendorAction' => $quotationsPendingVendorAction,
			'quotationsPendingVendorInfo' => $quotationsPendingVendorInfo,			
			'quotationsPendingCustomer' => $quotationsPendingCustomer,
			'pendingvatexemptrequest' => $pendingvatexemptrequest,
			'shpinqsPending'=> $shpinqsPending
		]);
		
	}

	/**
     * Home page Search in products and people 
     */
    public function home_search ($query){
		//get Products 
		$products = \App\Product::where('name','like','%'.$query.'%')
								->select('id','name','price','offer','currency_id','category_id')
								->with(['images','currency','productcategory'])
								->get();

		//get Perople 
		$people = \App\User::where('name','like','%'.$query.'%')
							->select('name')
							->get();

		//get Companies
		$companies = \App\Company::where('companyname','like','%'.$query.'%')
								 ->select('companyname')
								 ->with('companytype')->get();

		return response()->json(array('products'=> $products , 'people' => $people , 'companies' => $companies));
	}
	


}
