<?php

namespace App\Http\Controllers;

use App\Country;
use App\Companytype;
use App\Company;
use App\City;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SapCustomerReport;
use Gate;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\SapVendorReport;
use Illuminate\Http\Request;


class DataReportingController extends Controller
{
	public function index()
	{
		return Redirect("/data-reporting/statement-of-account/");
	}

	public function searchCompanies(Request $request)
	{
		$countries = Country::where('allowed', 1)->orderBy('countryname')->get();
		$companytypes = Companytype::where('active', 1)->orderBy('id')->get();
		$roles = Auth::User()->roles;
		$companyzero = $roles->where('company_id', '0');
		if ($companyzero->count() > 0) {
			$query = Company::with('paymentterms')->orderBy('companyname', 'asc');
			// Get vendors only
			if(Gate::allows('fi_ap') && Gate::denies('fi_ar')) {
				$query = $query->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
			} elseif(Gate::allows('fi_ar') && Gate::denies('fi_ap')) {
				$query = $query->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			}
		} else {
			$roles = $roles->pluck('id');
			$query = Company::whereHas('roles', function ($q) use ($roles) {
				$q->whereIn('roles.id', $roles);
			})->orderBy('companyname', 'asc');
		}
		$query->where('companyname','LIKE',"%$request->q%");
		$companies = $query->get();

		return $companies;
	}

	public function outstanding($companyId = null)
	{
		if ($companyId == null && Gate::any(['pt_as', 'po_rc', 'fi_vw', 'fi_ar', 'fi_ap']))
			return View('data_reporting.outstanding_with_search')
				->with('title', "Statement of Outstanding");

		if ($companyId == null) {
			$error = $this->checkFiIntegration();
			if($error)
				return $error;
		}
		
		$company = Auth::user()->getBuyerCompany(); 
		if(!$company)
			$company = Auth::user()->getSupplierCompany();
		
		if ($companyId != null) {
			$company = Company::find($companyId);
		}
		return View('data_reporting.outstanding')->with('title', "Statement of Outstanding")
			->with('company', $company);
	}

	public function outstandingPartialLoad($companyId, $owner = false)
	{
		$company = Company::where('id', $companyId)->firstOrFail();
		$customerItems = null;
		$vendorItems = null;
		if(!$owner) {
			$canView = $this->canView($company, $owner);
			if(!$canView)
				abort(403);
		}
		if($company->isCustomer()) {
			$args = [
				'COMPANYCODE' => env("SAP_COMPANY_CODE"),
				'CUSTOMER' => $company->sapnumber,
				'DATE_TO' => date("Ymd")
			];
			// Get customer report
			$report = new SapCustomerReport($args);
			$customerItems = $report->getItems();
		}

		if($company->isVendor()) {
			$args = [
				'COMPANYCODE' => env("SAP_COMPANY_CODE"),
				'VENDOR' => $company->sapvendornumber,
				'KEYDATE' => date("Ymd")
			];
			// Get vendor report
			$report = new SapVendorReport($args);
			$vendorItems = $report->getItems();
		}

		
		return View('data_reporting.outstanding_common')->with('title', "Statement of Outstanding Payments")
			->with('customerItems', $customerItems)
			->with('vendorItems', $vendorItems)
			->render();
	}

	public function statementOfAccount()
	{
		if (Gate::any(['pt_as', 'po_rc', 'fi_vw', 'fi_ar', 'fi_ap']))
			return View('data_reporting.statement_of_account_with_search')
				->with('title', "Account Status");

		$error = $this->checkFiIntegration();
		if($error)
			return $error;

		$company = Auth::user()->getBuyerCompany(); 
		if(!$company)
			$company = Auth::user()->getSupplierCompany();

		return View('data_reporting.statement_of_account')->with('title', "Account Status")
			->with('company', $company);
	}

	public function statementOfAccountPartialLoad($companyId, $owner = false)
	{
		$company = Company::where('id', $companyId)->firstOrFail();
		if(!$owner) {
			$canView = $this->canView($company, $owner);
			if(!$canView)
				abort(403);
		}

		if($company->isCustomer()) {
			$args = [
				'COMPANYCODE' => env("SAP_COMPANY_CODE"),
				'CUSTOMER' => $company->sapnumber,
				'DATE_TO' => date("Ymd")
			];
			// Get customer report
			$customerReport = new SapCustomerReport($args);
		}

		if($company->isVendor()) {
			$args = [
				'COMPANYCODE' => env("SAP_COMPANY_CODE"),
				'VENDOR' => $company->sapvendornumber,
				'KEYDATE' => date("Ymd")
			];
			// Get vendor report
			$vendorReport = new SapVendorReport($args);
		}

		if($company->isCustomer() && $company->isVendor()) { 
			$latestTransactions = Auth::user()->posPending(5);
			
			if(count($latestTransactions) < 1) {
				$latestTransactions = Auth::user()->getAllPOs(5);
			}
		} elseif($company->isCustomer()) {
			// Get latest transactions (Pending POs or Latest POs)
			$latestTransactions = Auth::user()->pospendingcustomer(5);

			if(count($latestTransactions) < 1) {
				$latestTransactions = Auth::user()->getAllCustomerPOs(5);
			}
		} elseif($company->isVendor()) {
			// Get latest transactions (Pending POs or Latest POs)
			$latestTransactions = Auth::user()->pospendingVendor(5);

			if(count($latestTransactions) < 1) {
				$latestTransactions = Auth::user()->getAllVendorPOs(5);
			}
		}

		if(count($latestTransactions) > 5) {
			$latestTransactions = array_slice($latestTransactions->toArray(), -1, 1, true);
		}

		return View('data_reporting.statement_of_account_common')->with('title', "Account Status")
			->with('company', $company)
			->with('latestTransactions', $latestTransactions)
			->with('customerReport', isset($customerReport) ? $customerReport : null)
			->with('vendorReport', isset($vendorReport) ? $vendorReport : null)
			->render();
	}

	private function canView($company, $owner) 
	{
		if($company->isCustomer() && $company->isVendor())
			return true;
		elseif($company->isCustomer())
			return !$owner && Gate::any(['fi_ar', 'fi_vw', 'cr_ap', 'pt_as', 'po_rc']);
		else
			return !$owner && Gate::any(['fi_ap', 'fi_vw', 'cr_ap', 'pt_as', 'po_rc']);
	}

	function checkFiIntegration()
	{
		if (!Auth::user()->hasBuyerCompany() && !Auth::user()->hasSupplierCompany()) {
			return view('message', [
				'title' => 'Account summary',
				'message' => "Cannot show account summary",
				'description' => "No company is defined yet",
				'error' => true,
			]);
		} elseif (!Auth::user()->hasReadyBuyerCompany() && !Auth::user()->hasReadySupplierCompany()) {
			$reason = '';			
			if (!Auth::user()->hasReadyBuyerCompany()) {
				$company = Auth::user()->getBuyerCompany();
				if ($company) {
					if (!$company->confirmed) {
						$reason = 'Your company is not confirmed yet';
					} elseif (!$company->customer_signed) {
						$reason = 'Your company contract is not signed yet';
					}
				}
			} else {
				$company = Auth::user()->getSupplierCompany();
				if ($company) {
					if (!$company->confirmed) {
						$reason = 'Your company is not confirmed yet';
					} elseif (!$company->vendor_signed) {
						$reason = 'Your company contract is not signed yet';
					}
				}
			}
			return view('message', [
				'title' => 'Account summary',
				'message' => "Cannot show account summary",
				'description' => $reason,
				'error' => true,
			]);
		} else {
			$company = Auth::user()->getBuyerCompany();
			if (!$company)
				$company = Auth::user()->getSupplierCompany();

			if ($company->isCustomer() && !$company->sapnumber) {
				return view('message', [
					'title' => 'Account summary',
					'message' => "Cannot show account summary",
					'description' => "Your company FI integration is not yet complete, please try again later",
					'error' => true,
				]);
			} elseif ($company->isVendor() && !$company->sapvendornumber) {
				return view('message', [
					'title' => 'Account summary',
					'message' => "Cannot show account summary",
					'description' => "Your company FI integration is not yet complet, please try again later",
					'error' => true,
				]);
			}
		}

		return false;
	}
}
