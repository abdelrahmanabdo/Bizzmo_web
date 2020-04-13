<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Gate;
use Input;
use View;

use App\Company;
use App\Country;
use App\Permission;
use App\Role;

use App\Helpers\SapConnection;
use App\Helpers\SapCustomerReport;

use App\Jobs\Processmanuallimit;

class creditcontroller extends Controller
{	
	public function companies()
	{
		if (Gate::any(['cr_ap', 'fi_ar'])) {
			return View('credit.status_with_search')->with('title', "Credit Check");
		} else {
			$buyerCompany = Auth::user()->getBuyerCompany();
			if (!$buyerCompany) {
				return view('message',[
					'title' => 'Credit check',
					'message' => 'Cannot check credit',
					'description' => __('messages.noBuyerCompany', ['context' => 'credit check']),
					'error' => true,
				]);
			}

			if (!$buyerCompany->active) {
				return view('message',[
					'title' => 'Credit check',
					'message' => 'Cannot check credit',
					'description' => __('messages.compBuyerNotActive'),
					'error' => true,
				]);
			}
			return redirect('credit/company/' . $buyerCompany->id);
		}

		$activecompanies = $companies->where('active', 1);
		if ($activecompanies->count() == 0 ) {
			return view('message',[
				'title' => 'Credit check',
				'message' => 'Cannot check credit',
				'description' => __('messages.eligcomp'),
				'error' => true,
			]);
		}
		return view::make('credit.list')->with('title', 'Choose company')
		->with('companies', $activecompanies);
	}

	public function searchCompanies(Request $request) 
	{
		$query = Company::whereIn('companytype_id', [1,3]);
		$query->where('companyname','LIKE',"%$request->q%");
		return $query->get();
	}

	public function creditStatus($id)
	{
		$company = Company::find($id);

		return view::make('credit.status')->with('title', 'Credit check')
			->with('company', $company);
	}

	public function changelimit(Request $request) {
		$company_id = Input::get('company_id');
		return redirect('/credit/change/' . $company_id);
	}
	
	public function change($id) {
		$company = Company::find($id);
		return view::make('credit.change')->with('title', 'Change Credit Limit')
			->with('company', $company);
	}
	
	public function savelimit(Request $request, $id) {
		$rules = [
			'newlimit' => 'required|numeric',
        ];
		$messages = [
			'newlimit.required' => 'Credit limit is required',
			'newlimit.numeric' => 'Credit limit must be a number',
		];		
		$this->validate($request, $rules, $messages);
		$company = Company::find($id);
		$company->creditlimit = Input::get('newlimit');
		$company->save();
		Processmanuallimit::dispatch($company);
		return view::make('credit.change')->with('title', 'Change Credit Limit')
			->with('mode', 'v')
			->with('company', $company);
	}
	
	public function creditStatusPartialLoad($id, $owner = false)
	{
		$company = Company::find($id);
		if(!$owner && !Gate::any(['fi_ar', 'cr_ap']))
			abort(403);

		$args = [
			'COMPANYCODE' => env("SAP_COMPANY_CODE"),
			'CUSTOMER' => $company->sapnumber,
			'DATE_TO' => date("Ymd")
		];
		// Get customer report
		$report = new SapCustomerReport($args);

		return view::make('credit.status_table')->with('title', 'Credit check')
			->with('company', $company)
			->with('report', $report)
			->render();
	}
}
