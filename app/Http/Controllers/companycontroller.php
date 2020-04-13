<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;
use Log;
use Gate;
use App\Http\Requests\storecompanyrequest;
use Illuminate\Support\Str;

use App\Actiontoken;
use App\Attachmenttype;
use App\Brand;
use App\Buyertype;
use App\City;
use App\Company;
use App\Companyattachment;
use App\Companyowner;
use App\Companybeneficial;
use App\Companydirector;
use App\Companytopproduct;
use App\Companytopcustomer;
use App\Companytopsupplier;
use App\Companytype;
use App\Country;
use App\Currency;
use App\Module;

use App\Deliverytype;
use App\Industry;
use App\Paymentterm;
use App\Permission;
use App\Pickupaddress;
use App\Range;
use App\Role;
use App\Shippingaddress;
use App\Suppliertype;
use App\Vendor;
use App\CompanyProfile;
use App\Jobs\ProcessDeregisterCompany;
use App\Jobs\Processcompany;
use App\Jobs\ProcessSendContract;
use App\Traits\UploadTrait;

use App\Jobs\Processvendor;

class companycontroller extends Controller
{
	use UploadTrait;	

	public function changes(Request $request, $id) {
		return $this->view($request, $id, 'h');
	}
	
    public function view(Request $request, $id, $mode = 'v') {
		$company = Company::with(['companyowners' => function ($q) {
			$q->where('active', 1)
			->orderBy('ownername', 'desc');
		}])->with('companyProfile' , 'companyowners.attachments', 'companydirectors.attachments', 'companytopproducts', 'companytopcustomers', 'companytopsuppliers', 'country', 'city', 'creditrequests')->findOrFail($id);
		$company->incorporated = date("j/n/Y",strtotime($company->incorporated));
		$tradeattachment = $company->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $company->attachments->where('attachmenttype_id', 27)->first();
		$signidattachment = $company->attachments->where('attachmenttype_id', 28)->first();
		$signpptattachment = $company->attachments->where('attachmenttype_id', 30)->first();
		$signvisaattachment = $company->attachments->where('attachmenttype_id', 29)->first();
		$taxAttachment = $company->attachments->where('attachmenttype_id', Attachmenttype::TAX_CERTIFICATE)->first();
		$buyerContract = $company->attachments->where('attachmenttype_id', Attachmenttype::BUYER_CONTRACT)->first();
		$supplierContract = $company->attachments->where('attachmenttype_id', Attachmenttype::SUPPLIER_CONTRACT)->first();
		$actiontokens = Actiontoken::where('action', 'deregister')->where('object_id', $id)->whereDate('expiry', '>', date('Y-m-d'))->get();
		$pendingunregister = false;
		if ($actiontokens->count() > 0) {
			$pendingunregister = true;
		}
		
		if ($request->wantsJson()) {
			return $company;
		} else {
			return view('companies.manage')
			->with('title', 'View company')
			->with('mode', $mode)
			->with('company', $company)
			->with('onetab', 0)
			->with('pendingunregister', $pendingunregister)
			->with('tradeattachment', $tradeattachment)
			->with('assocattachment', $assocattachment)
			->with('buyerContract', $buyerContract)
			->with('supplierContract', $supplierContract)
			->with('taxAttachment', $taxAttachment)
			->with('signidattachment', $signidattachment)
			->with('signpptattachment', $signpptattachment)
			->with('signvisaattachment', $signvisaattachment);
		}
	}

	/**
	 * List of all companies
	 */
	public function get_companies_list(){
		$companies = Company::with('companyProfile')->paginate(12);
		return view('companies.companies')->with('companies',$companies)->with('title','Compnies list');
	}
	/**
	 * Show profile page
	 */
	public function profile_view (Request $request, $id){
		$company = Company::with(['companyowners' => function ($q) {
				$q->where('active', 1)
				->orderBy('ownername', 'desc');
			}])->with('companyProfile' , 'companyowners.attachments', 
					  'companydirectors.attachments', 'companytopproducts', 'companytopcustomers', 'companytopsuppliers', 'country', 'city', 'creditrequests')->findOrFail($id);
		if(isset($request->mode)){
			$profile = CompanyProfile::whereCompanyId($id)->first();
		}
		$mode = $request->mode ?? 'view';
		
		return view('companies.profile', compact('company' , 'profile' , 'mode'));
	}

	/**
	 * 
	 * Edit profile
	 */
	public function profile_edit (Request $request){
			$data = $request->except('_token');
			if($request->file('logo')){
				$image = $request->file('logo');
				$name = Str::slug($request->input('name')).'_'.time();
				$folder = 'images/company/' . date('Y') . '/' . date('m').'/';
				$filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
				$this->uploadOne($image, $folder, 'public', $name);
				$data['logo'] = $filePath ;
			}
			if($request->file('cover')){
				$image = $request->file('cover');
				$name = Str::slug($request->input('name')).'_'.time();
				$folder = 'images/company/' . date('Y') . '/' . date('m').'/';
				$filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
				$this->uploadOne($image, $folder, 'public', $name);
				$data['cover']= $filePath;
			}
			if(CompanyProfile::whereCompanyId($request->company_id)->exists()){
			   $profile = CompanyProfile::whereCompanyId($request->company_id)->first();
			   $profile->update($data);
			}else{
				$profile = new CompanyProfile();
				$profile->create($data);
			}
			return back();

	}
	
	public function select1() {
		return redirect('/companies/create/1');
	}
	
	public function select() {
		return view('companies.select')->with('title', 'Select company type');
	}
	
	public function deregisterrequest($id) {
		//delete old tokens for the same company
		DB::table('actiontokens')->where('action', 'deregister')->where('object_id', $id)->delete();
		//create new token
		$date=date_create(date('Y-m-d H:i:s'));
		$date = new \DateTime('+7 day');
		$actiontoken = New Actiontoken;
		$actiontoken->action = 'deregister';
		$actiontoken->token = str_random(20);
		$actiontoken->object_id = $id;
		$actiontoken->expiry = $date->format('Y-m-d H:i:s');
		$actiontoken->save();
		ProcessDeregisterCompany::dispatch($actiontoken);
		return redirect('/companies/view/' . $id);
	}
	
	public function confirm($id) {
		$company = Company::findOrFail($id);
		if ($company->confirmed == 1) {
			return view('message',[
				'title' => 'Already confirmed',
				'message' => 'Cannot confirm. Company is already confirmed.',
				'error' => true
			]);
		}
		$company->confirmed = 1;
		$company->active = 1;
		$company->save();
		ProcessSendContract::dispatch($company);
		if ($company->companytype_id == 1 || $company->companytype_id == 3) {
			//Processcompany::dispatch($company);
		}
		if ($company->companytype_id == 2 || $company->companytype_id == 3) {
			//Processvendor::dispatch($company);
		}
		$tradeattachment = $company->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $company->attachments->where('attachmenttype_id', 27)->first();
		$taxAttachment = $company->attachments->where('attachmenttype_id', Attachmenttype::TAX_CERTIFICATE)->first();
		
		// Save shipping address
		if($company->shippingaddresses->count() == 0 && $company->isCustomer()) {
			$shippingaddress = new Shippingaddress;
			$shippingaddress->company_id = $company->id;
			$shippingaddress->partyname =$company->companyname;
			$shippingaddress->address =$company->address;
			$shippingaddress->po_box =$company->pobox;
			$shippingaddress->phone =$company->phone;
			$shippingaddress->fax =$company->fax;
			$shippingaddress->email =$company->email;
			$shippingaddress->city_id = $company->city->id;
			$shippingaddress->city_name = $company->city->cityname;
			$shippingaddress->country_name = $company->country->countryname;
			$shippingaddress->delivery_address =$company->address;
			$shippingaddress->delivery_city_id = $company->city->id;
			$shippingaddress->incoterm_id = 1;
			$shippingaddress->default = 1;
			$shippingaddress->created_by = Auth::user()->id;
			$shippingaddress->updated_by = Auth::user()->id;
			$shippingaddress->save();
		}
		
		// Save pickup address
		if($company->pickupaddresses->count() == 0 && $company->isVendor()) {
			$pickupaddress = new Pickupaddress;
			$pickupaddress->company_id = $company->id;
			$pickupaddress->partyname =$company->companyname;
			$pickupaddress->address =$company->address;
			$pickupaddress->po_box =$company->pobox;
			$pickupaddress->phone =$company->phone;
			$pickupaddress->fax =$company->fax;
			$pickupaddress->email =$company->email;
			$pickupaddress->city_id = $company->city->id;
			$pickupaddress->default = 1;
			$pickupaddress->created_by = Auth::user()->id;
			$pickupaddress->updated_by = Auth::user()->id;
			$pickupaddress->save();
		}
		$confirmMessage = 'Company confirmed successfully. The contract has been sent to ' . $company->signatoryemail . ' to be signed.';
		return view('companies.manage', [
			'title' => 'View company',
			'mode' => 'c',
			'company' => $company,
			'onetab' => 0,
			'tradeattachment' => $tradeattachment,
			'assocattachment' => $assocattachment,
			'taxAttachment' => $taxAttachment,
			'confirmMessage' => $confirmMessage
		]);
	}

	public function manage(Request $request, $id = '', $tab = '')
	{
		$module = Module::find(Module::FREIGHT_FORWARDER);
		$showFF = $module->active;
		$industries = Industry::where('active', 1)->get();
		$brands = Brand::where('active', 1)->get();
		$buyertypes = Buyertype::orderBy('name')->get();
		$suppliertypes = Suppliertype::orderBy('name')->get();
		if ($id != '') {

			if (strpos($request->path(), 'company') !== false) {
				$onetab = 1;
			} else {
				$onetab = 0;
			}
			$company = Company::with('attachments', 'companyowners', 'companyowners.attachments')->findOrFail($id);
			
			if(($company->customer_signed || $company->customer_signed) && !Gate::allows('cr_ap')) {
				return view('message', [
					'title' => 'Edit Company',
					'message' => 'Cannot edit company data',
					'description' => 'If you want to change company data, please sent an email to bizzmo@bizzmo.com',
					'error' => true
				]);
			}

			$oldCountry = $request->old('country_id');
			if (!empty($oldCountry) && $oldCountry != $company->country_id)
				$cities = City::where('country_id', $oldCountry)->orderBy('cityname', 'asc')->get();
			else
				$cities = City::where('country_id', $company->country_id)->where('active', 1)->orWhere('id', $company->city_id)->orderBy('cityname', 'asc')->get();
			$company->incorporated = date("j/n/Y", strtotime($company->incorporated));
			$tradeattachment = $company->attachments->where('attachmenttype_id', 5)->first();
			$assocattachment = $company->attachments->where('attachmenttype_id', 27)->first();
			$signidattachment = $company->attachments->where('attachmenttype_id', 28)->first();
			$signpptattachment = $company->attachments->where('attachmenttype_id', 30)->first();
			$signvisaattachment = $company->attachments->where('attachmenttype_id', 29)->first();
			$taxAttachment = $company->attachments->where('attachmenttype_id', Attachmenttype::TAX_CERTIFICATE)->first();
			$currencies = Currency::whereIn('id', [1, 2])->orderBy('name')->get();
			$countries = Country::where('active', 1)->orderBy('countryname')->get(['countryname', 'id' ,'allowed' , 'isocode']);
			$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();		
			$allcountries = Country::orderBy('countryname')->get();			
			$initialCountryId = $countries->firstWhere('isocode', 'AE')->id;
			$employees = Range::where('active', 1)->where('rangetype', 'personel')->orWhere('id', $company->employees)->orderBy('id')->get();
			$percentages = Range::where('active', 1)->where('rangetype', 'percent10')->orderBy('id')->get();

			return view::make('companies/manage', [
				'company' => $company,
				'title' => 'Edit company',
				'onetab' => $onetab,
				'signidattachment' => $signidattachment,
				'signpptattachment' => $signpptattachment,
				'signvisaattachment' => $signvisaattachment,
				'tradeattachment' => $tradeattachment,
				'assocattachment' => $assocattachment,
				'taxAttachment' => $taxAttachment,
				'brandsarr' => $brands->pluck('name', 'id'),
				'brands' => $brands->pluck('name', 'id'),
				'currencies' => $currencies->pluck('name', 'id'),
				'employees' => $employees->pluck('name', 'id'),
				'percentages' => $percentages,
				'arrpercentages' => $percentages->pluck('name', 'id'),
				'countries' => $countries,
				'allcountries' => $allcountries,
				'initial_country_id' => $initialCountryId,
				'cities' => $cities->pluck('cityname', 'id'),
				'buyertypes' => $buyertypes,
				'showFF' => $showFF,
				'suppliertypes' => $suppliertypes,
				'industries' => $industries->pluck('name', 'id'),
			]);
		} else {
			$roles = Auth::User()->roles->pluck('id');
			$query = Company::where('confirmed', '0')->whereHas('roles', function ($q) use ($roles) {
				$q->whereIn('roles.id', $roles);
			});
			$companies = $query->get();
			foreach ($companies as $company) {
				if (!$company->iscomplete) {
					return view('message', [
						'title' => 'Incomplete company data',
						'message' => 'Cannot create a new company.',
						'description' => 'There is a company with incomplete data.',
						'company_id' => $company->id,
						'error' => true
					]);
				}
			}
			$countries = Country::orderBy('countryname')->get(['countryname', 'id' ,'allowed' , 'isocode']);
			$initialCountryId = $countries->firstWhere('isocode', 'AE')->id;
			$cities = City::where('country_id', $initialCountryId)->where('active', 1)->orderBy('cityname', 'asc')->get();
			$currencies = Currency::whereIn('id', [1, 2])->orderBy('name')->get();
			$employees = Range::where('active', 1)->where('rangetype', 'personel')->orderBy('id')->get();
			$percentages = Range::where('active', 1)->where('rangetype', 'percent10')->orderBy('id')->get();
			return view::make('companies/manage', [
				'title' => 'Create company',
				'onetab' => 0,
				'currencies' => $currencies->pluck('name', 'id'),
				'employees' => $employees->pluck('name', 'id'),
				'percentages' => $percentages,
				'arrpercentages' => $percentages->pluck('name', 'id'),
				'industries' => $industries->pluck('name', 'id'),
				'brands' => $brands,
				'brandsarr' => $brands->pluck('name', 'id'),
				'countries' => $countries,
				'initial_country_id' => $initialCountryId,
				'cities' => $cities->pluck('cityname', 'id'),
				'showFF' => $showFF,
				'buyertypes' => $buyertypes,
				'supplierpes' => $suppliertypes
			]);
		}
	}
	
	public function save(storecompanyrequest $request, $id = 0, $tab = '') {
		$hasCompany = Auth::user()->companies->count();
		if ($hasCompany && !$id)
			abort(404);

		// Set company type id
		// if (Auth::user()->hasBuyerCompany()) {
		// 	$companytype_id = Companytype::SUPPLIER_TYPE;
		// 	if ($id && (Auth::user()->getBuyerCompany()->id == $id)) {
		// 		$companytype_id = $this->getCompanyTypeIdFromInput();
		// 	}
		// }
		// else {
		// 	$companytype_id = $this->getCompanyTypeIdFromInput();
		// }
		$companytype_id = $this->getCompanyTypeIdFromInput();
		
		if ($id == 0) {
			$company = new Company;
			$company->created_by = Auth::user()->id;
			$company->tenant_id = Auth::user()->tenant_id;
			$basicinfo = 0;
			$authsignatory = 0;
			$shareholders = 0;
		} else {
			$company = Company::find($id);			
			//$basicinfo = 1;
			//$shareholders = 1;
			if ($companytype_id != $company->companytype_id) {
				switch ($companytype_id) {
					case '1':
						if ($company->companytype_id == 2) {
							$company->shareholders = 0;
							$company->beneficialowners = 0;
							$company->directors = 0;
							$company->business = 0;
						}
						break;
					case '2':
						if ($company->companytype_id == 1) {
							$company->banks = 0;
							$company->business = 0;
						}
						break;
					case '3':
						if ($company->companytype_id == 1) {
							$company->banks = 0;
							$company->business = 0;
						} else {
							$company->shareholders = 0;
							$company->beneficialowners = 0;
							$company->directors = 0;
							$company->business = 0;
						}
						break;
					case '4':
						if ($company->companytype_id == 1) {
							$company->banks = 0;
							$company->business = 0;
						}
						break;
				}
			}
		}
		$company->companyname = Input::get('companyname');
		$company->accountname = Input::get('companyname');
		$company->address = Input::get('address');
		$company->district = Input::get('district');
		$company->city_id = Input::get('city_id');
		$company->country_id = Input::get('country_id');
		$company->currency_id = Input::get('currency_id');
		$company->email = Input::get('email');
		$company->phone = Input::get('phone');
		$company->fax = Input::get('fax');
		$company->pobox = Input::get('pobox');
		$company->license = Input::get('license');
		$company->tax = Input::get('tax');
		$date = date_create_from_format("j/n/Y",Input::get('incorporated'));
		$company->incorporated = $date->format('Y-m-d');
		$company->employees = Input::get('employees');
		$company->operating = Input::get('operating');
		$company->website = Input::get('website');
		$company->updated_by = Auth::user()->id;
		$company->companytype_id = $companytype_id;
		$company->basicinfo = 1;
		if ($companytype_id == 1)
			$company->banks = 1;
		
		if ($companytype_id == 2 || $companytype_id == 4) {
			$company->shareholders = 1;
			$company->beneficialowners = 1;
			$company->directors = 1;
		}
		if (!Gate::allows('cr_ap')) {
			$company->active = 0;
			$company->confirmed = 0;
		}
		$company->save();
		$company->industries()->sync($request->input('industries'));
		$basicinfo = 1;
		//Default payment terms
		if ($id == 0) {
			$company->paymentterms()->attach(1, ['buyup' => Paymentterm::find(1)->buyup, 'active' => 1]);
			if ($companytype_id == 2 || $companytype_id == 3) {
				$company->deliverytypes()->attach(1);
			}
		}
		//Default role
		if ($id == 0) {
			$commonRoles = [
				["role" => "Admin", "permissions" => ["buyer" => [1, 2, 3, 4, 5, 6, 9, 10, 11, 17, 22, 23, 46, 47, 48], "supplier" => [12, 13, 14, 15, 16, 18, 24, 37, 38, 39, 45], "users" => [25, 26, 27, 31]]],
				["role" => "Finance", "permissions" => ["buyer" => [11, 42], "supplier" => [12, 43], "users" => []]],
			];
			$buyerRoles = [
				["role" => "Purchaser", "permissions" => [9, 10, 11, 46, 47]],
				["role" => "Purchasing manager", "permissions" => [22]],
			];
			$supplierRoles = [
				["role" => "Salesman", "permissions" => [12, 37, 38, 39]],
				["role" => "Sales manager", "permissions" => [23, 45]],
			];
							
			foreach ($commonRoles as $commonRole) {
				$role = new Role(array('rolename' => $commonRole["role"], 'company_id' => $company->id, 'active' => 1, 'systemrole' => 1, 'created_by' => Auth::User()->id, 'updated_by' => Auth::User()->id));
				$role->save();
				if ($company->companytype_id == 1 || $company->companytype_id == 3) {
					$role->permissions()->attach($commonRole["permissions"]["buyer"]);
				}
				if ($company->companytype_id == 2 || $company->companytype_id == 3) {
					$role->permissions()->attach($commonRole["permissions"]["supplier"]);
				}
				if ($company->companytype_id == 4) {
					$role->permissions()->attach($commonRole["permissions"]["supplier"]);
				}
				$role->permissions()->attach($commonRole["permissions"]["users"]);
				if($commonRole["role"] == "Admin")
					$role->users()->attach(Auth::User()->id);
			}

			if ($company->isCustomer()) {
				foreach ($buyerRoles as $buyerRole) {
					$role = new Role(array('rolename' => $buyerRole["role"], 'company_id' => $company->id, 'active' => 1, 'systemrole' => 1, 'created_by' => Auth::User()->id, 'updated_by' => Auth::User()->id));
					$role->save();
					$role->permissions()->attach($buyerRole["permissions"]);
				}
			} 
			
			if ($company->isVendor()) {
				foreach ($supplierRoles as $supplierRole) {
					$role = new Role(array('rolename' => $supplierRole["role"], 'company_id' => $company->id, 'active' => 1, 'systemrole' => 1, 'created_by' => Auth::User()->id, 'updated_by' => Auth::User()->id));
					$role->save();
					$role->permissions()->attach($supplierRole["permissions"]);
				}
			}	
			
			if ($company->isForwarder()) {
				foreach ($supplierRoles as $supplierRole) {
					$role = new Role(array('rolename' => $supplierRole["role"], 'company_id' => $company->id, 'active' => 1, 'systemrole' => 1, 'created_by' => Auth::User()->id, 'updated_by' => Auth::User()->id));
					$role->save();
					$role->permissions()->attach($supplierRole["permissions"]);
				}
			}
		}
		//trade license attachment
		if (Input::get('tradefile') <> '' && Input::get('tradeattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '5')
			->where('attachable_type', 'company')->where('id', '<>', Input::get('tradeattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('tradeattachid'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
			'description' => 'Trade license', 'attachmenttype_id' => 5, 'filename' => Input::get('tradefile')]);
		}
		//asrticle of assoc attachment
		if (Input::get('assocfile') <> '' && Input::get('assocattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '27')
			->where('attachable_type', 'company')->where('id', '<>', Input::get('assocattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('assocattachid'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
			'description' => 'Articles of association', 'attachmenttype_id' => 27, 'filename' => Input::get('assocfile')]);
		}

		// Tax certificate attachment
		if (Input::get('taxFile') <> '' && Input::get('taxAttachId') <> '') {
			DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', Attachmenttype::TAX_CERTIFICATE)
			->where('attachable_type', 'company')->where('id', '<>', Input::get('taxAttachId'))->delete();
			DB::table('attachments')->where('id', Input::get('taxAttachId'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
			'description' => 'Tax Certificate', 'attachmenttype_id' => Attachmenttype::TAX_CERTIFICATE, 'filename' => Input::get('taxFile')]);
		}

		//Company sub data
		$i = 0;
		if (Input::has('ownerid')) {
			$company->shareholders = 1;
			if (Input::has('cbsame')) {
				$company->sameowner = 1;
				$company->beneficialowners = 1;				
			} else {
				$company->sameowner = 0;
			}
			if (!Gate::allows('cr_ap')) {
				$company->confirmed = 0;
			}
			$company->save();
			foreach (Input::get('ownerid') as $item) {
				$owner = 0;
				if ($item == '' && Input::get('ownerdel')[$i] == '') {
					$companyowner  = new Companyowner(array('ownername' => Input::get('ownername')[$i], 'owneremail'=> Input::get('owneremail')[$i], 'ownerphone'=> Input::get('ownerphone')[$i], 'ownershare'=> Input::get('ownershare')[$i]));
					$company->companyowners()->save($companyowner);
					$owner = $companyowner->id;
				} elseif ($item != '') {
					if (Input::get('ownerdel')[$i] == '') {
						$companyowner = Companyowner::find($item);
						$companyowner->ownername = Input::get('ownername')[$i];
						$companyowner->owneremail = Input::get('owneremail')[$i];
						$companyowner->ownerphone = Input::get('ownerphone')[$i];
						$companyowner->ownershare = Input::get('ownershare')[$i];
						$companyowner->save();	
						$owner = $companyowner->id;						
					} else {
						$companyowner = Companyowner::destroy($item);
						DB::table('attachments')->where('attachable_id', $item)
						->where('attachable_type', 'companyowner')->delete();
					}
				}
				
				if ($owner != 0) {
					if (Input::get('owneridattachid')[$i] != '') {
						// delete existing attachments and add new one
						DB::table('attachments')->where('attachable_id', $companyowner->id)
						->where('attachable_type', 'companyowner')->where('attachmenttype_id', 1)->delete();
						DB::table('attachments')->where('id', Input::get('owneridattachid')[$i])->update(['attachable_type' => 'companyowner', 'attachable_id' => $companyowner->id, 
						'description' => 'Owner ID ' . Input::get('owneridfile')[$i], 'attachmenttype_id' => 1, 'filename' => Input::get('owneridfile')[$i]]);
					} else if (Input::get('owneridfile')[$i] == '') {
						//no attachment of type ID
						DB::table('attachments')->where('attachable_id', $companyowner->id)
						->where('attachable_type', 'companyowner')->where('attachmenttype_id', 1)->delete();
					}
					if (Input::get('ownerpptattachid')[$i] != '') {
						DB::table('attachments')->where('attachable_id', $companyowner->id)
						->where('attachable_type', 'companyowner')->where('attachmenttype_id', 9)->delete();
						DB::table('attachments')->where('id', Input::get('ownerpptattachid')[$i])->update(['attachable_type' => 'companyowner', 'attachable_id' => $companyowner->id, 
						'description' => 'Owner passport ' . Input::get('ownerpptfile')[$i], 'attachmenttype_id' => 9, 'filename' => Input::get('ownerpptfile')[$i]]);
					} else if (Input::get('ownerpptfile')[$i] == '') {
						DB::table('attachments')->where('attachable_id', $companyowner->id)
						->where('attachable_type', 'companyowner')->where('attachmenttype_id', 9)->delete();
					}
					if (Input::get('ownervisaattachid')[$i] != '') {
						DB::table('attachments')->where('attachable_id', $companyowner->id)
						->where('attachable_type', 'companyowner')->where('attachmenttype_id', 2)->delete();
						DB::table('attachments')->where('id', Input::get('ownervisaattachid')[$i])->update(['attachable_type' => 'companyowner', 'attachable_id' => $companyowner->id, 
						'description' => 'Owner visa ' . Input::get('ownervisafile')[$i], 'attachmenttype_id' => 2, 'filename' => Input::get('ownervisafile')[$i]]);
					} else if (Input::get('ownervisafile')[$i] == '') {
						DB::table('attachments')->where('attachable_id', $companyowner->id)
						->where('attachable_type', 'companyowner')->where('attachmenttype_id', 2)->delete();
					}
				}
				$i++;
			}
		}
		$i = 0;
		if (Input::has('beneficialid')) {
			$company->beneficialowners = 1;
			if (!Gate::allows('cr_ap')) {
				$company->confirmed = 0;
			}
			$company->save();
			foreach (Input::get('beneficialid') as $item) {
				$beneficial = 0;
				if ($item == '' && Input::get('beneficialdel')[$i] == '') {
					$companybeneficial  = new Companybeneficial(array('beneficialname' => Input::get('beneficialname')[$i], 'beneficialemail'=> Input::get('beneficialemail')[$i], 'beneficialphone'=> Input::get('beneficialphone')[$i], 'beneficialshare'=> Input::get('beneficialshare')[$i]));
					$company->companybeneficials()->save($companybeneficial);
					$beneficial = $companybeneficial->id;
				} elseif ($item != '') {
					if (Input::get('beneficialdel')[$i] == '') {
						$companybeneficial = Companybeneficial::find($item);
						$companybeneficial->beneficialname = Input::get('beneficialname')[$i];
						$companybeneficial->beneficialemail = Input::get('beneficialemail')[$i];
						$companybeneficial->beneficialphone = Input::get('beneficialphone')[$i];
						$companybeneficial->beneficialshare = Input::get('beneficialshare')[$i];
						$companybeneficial->save();	
						$beneficial = $companybeneficial->id;						
					} else {
						$companybeneficial = Companybeneficial::destroy($item);
						DB::table('attachments')->where('attachable_id', $item)
						->where('attachable_type', 'companybeneficial')->delete();						
					}
				}
				
				if ($beneficial != 0) {
					if (Input::get('beneficialidattachid')[$i] != '') {
						// delete existing attachments and add new one
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
						->where('attachable_type', 'companybeneficial')->where('attachmenttype_id', 11)->delete();
						DB::table('attachments')->where('id', Input::get('beneficialidattachid')[$i])->update(['attachable_type' => 'companybeneficial', 'attachable_id' => $companybeneficial->id, 
						'description' => 'Beneficial owner ID ' . Input::get('beneficialidfile')[$i], 'attachmenttype_id' => 11, 'filename' => Input::get('beneficialidfile')[$i]]);
					} else if (Input::get('beneficialidfile')[$i] == '') {
						//no attachment of type ID
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
						->where('attachable_type', 'companybeneficial')->where('attachmenttype_id', 11)->delete();
					}
					if (Input::get('beneficialpptattachid')[$i] != '') {
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
						->where('attachable_type', 'companybeneficial')->where('attachmenttype_id', 13)->delete();
						DB::table('attachments')->where('id', Input::get('beneficialpptattachid')[$i])->update(['attachable_type' => 'companybeneficial', 'attachable_id' => $companybeneficial->id, 
						'description' => 'Beneficial owner passport ' . Input::get('beneficialpptfile')[$i], 'attachmenttype_id' => 13, 'filename' => Input::get('beneficialpptfile')[$i]]);
					} else if (Input::get('beneficialpptfile')[$i] == '') {
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
						->where('attachable_type', 'companybeneficial')->where('attachmenttype_id', 13)->delete();
					}
					if (Input::get('beneficialvisaattachid')[$i] != '') {
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
						->where('attachable_type', 'companybeneficial')->where('attachmenttype_id', 12)->delete();
						DB::table('attachments')->where('id', Input::get('beneficialvisaattachid')[$i])->update(['attachable_type' => 'companybeneficial', 'attachable_id' => $companybeneficial->id, 
						'description' => 'Beneficial owner visa ' . Input::get('beneficialvisafile')[$i], 'attachmenttype_id' => 12, 'filename' => Input::get('beneficialvisafile')[$i]]);
					} else if (Input::get('beneficialvisafile')[$i] == '') {
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
						->where('attachable_type', 'companybeneficial')->where('attachmenttype_id', 12)->delete();
					}
				}
				$i++;
			}
		}
		if ($company->companybeneficials->count() == 0 ) {
			$company->sameowner = 1;			
		} else {
			$company->sameowner = 0;			
		}
		if (!Gate::allows('cr_ap')) {
			$company->confirmed = 0;
		}
		$company->save;
		$i = 0;
		if (Input::has('directorid')) {
			$company->directors = 1;
			if (!Gate::allows('cr_ap')) {
				$company->confirmed = 0;
			}
			$company->save();
			foreach (Input::get('directorid') as $item) {
				$director = 0;
				if ($item == '' && Input::get('directordel')[$i] == '') {
					$companydirector  = new Companydirector(array('directorname' => Input::get('directorname')[$i], 'directoremail'=> Input::get('directoremail')[$i], 'directorphone'=> Input::get('directorphone')[$i], 'directortitle'=> Input::get('directortitle')[$i]));
					$company->companydirectors()->save($companydirector);
					$director = $companydirector->id;
				} elseif ($item != '') {
					if (Input::get('directordel')[$i] == '') {
						$companydirector = Companydirector::find($item);
						$companydirector->directorname = Input::get('directorname')[$i];
						$companydirector->directoremail = Input::get('directoremail')[$i];
						$companydirector->directorphone = Input::get('directorphone')[$i];
						$companydirector->directortitle = Input::get('directortitle')[$i];
						$companydirector->save();
						$director = $companydirector->id;
					} else {
						$companydirector = Companydirector::destroy($item);
					}
				}
				if ($director != 0) {
					if (Input::get('directoridattachid')[$i] != '') {
						// delete existing attachments and add new one
						DB::table('attachments')->where('attachable_id', $companydirector->id)
						->where('attachable_type', 'companydirector')->where('attachmenttype_id', 3)->delete();
						DB::table('attachments')->where('id', Input::get('directoridattachid')[$i])->update(['attachable_type' => 'companydirector', 'attachable_id' => $companydirector->id, 
						'description' => 'Director ID ' . Input::get('directoridfile')[$i], 'attachmenttype_id' => 3, 'filename' => Input::get('directoridfile')[$i]]);
					} else if (Input::get('directoridfile')[$i] == '') {
						//no attachment of type ID
						DB::table('attachments')->where('attachable_id', $companydirector->id)
						->where('attachable_type', 'companydirector')->where('attachmenttype_id', 3)->delete();
					}
					if (Input::get('directorpptattachid')[$i] != '') {
						DB::table('attachments')->where('attachable_id', $companydirector->id)
						->where('attachable_type', 'companydirector')->where('attachmenttype_id', 10)->delete();
						DB::table('attachments')->where('id', Input::get('directorpptattachid')[$i])->update(['attachable_type' => 'companydirector', 'attachable_id' => $companydirector->id, 
						'description' => 'Director passport ' . Input::get('directorpptfile')[$i], 'attachmenttype_id' => 10, 'filename' => Input::get('directorpptfile')[$i]]);
					} else if (Input::get('directorpptfile')[$i] == '') {
						DB::table('attachments')->where('attachable_id', $companydirector->id)
						->where('attachable_type', 'companydirector')->where('attachmenttype_id', 10)->delete();
					}
					if (Input::get('directorvisaattachid')[$i] != '') {
						DB::table('attachments')->where('attachable_id', $companydirector->id)
						->where('attachable_type', 'companydirector')->where('attachmenttype_id', 4)->delete();
						DB::table('attachments')->where('id', Input::get('directorvisaattachid')[$i])->update(['attachable_type' => 'companydirector', 'attachable_id' => $companydirector->id, 
						'description' => 'Director visa ' . Input::get('directorvisafile')[$i], 'attachmenttype_id' => 4, 'filename' => Input::get('directorvisafile')[$i]]);
					} else if (Input::get('directorvisafile')[$i] == '') {
						DB::table('attachments')->where('attachable_id', $companydirector->id)
						->where('attachable_type', 'companydirector')->where('attachmenttype_id', 4)->delete();
					}
				}
				$i++;
			}
		}
		$i = 0;
		if (Input::has('topproductid')) {			
			if (!Gate::allows('cr_ap')) {
				$company->confirmed = 0;
			}
			$company->save();
			foreach (Input::get('topproductid') as $item) {
				if ($item == '' && Input::get('topproductdel')[$i] == '') {
					if (Input::get('topproductrevenue')[$i] != '') {
						$companytopproduct  = new Companytopproduct(array('topproductname' => Input::get('topproductname')[$i], 'topproductrevenue'=> Input::get('topproductrevenue')[$i]));
						$company->companytopproducts()->save($companytopproduct);
					}
				} elseif ($item != '') {
					if (Input::get('topproductdel')[$i] == '') {
						$companytopproduct = Companytopproduct::find($item);
						$companytopproduct->topproductname = Input::get('topproductname')[$i];
						$companytopproduct->topproductrevenue = Input::get('topproductrevenue')[$i];
						$companytopproduct->save();
					} else {
						$companytopproduct = Companytopproduct::destroy($item);
					}
				}
				$i++;
			}
		}
		$topcustomer = 0;
		$topsupplier = 0;
		$i = 0;
		if (Input::has('topcustomerid')) {
			$topcustomer = 1;
			foreach (Input::get('topcustomerid') as $item) {
				if ($item == '' && Input::get('topcustomerdel')[$i] == '') {
					if (Input::get('topcustomername')[$i] != '') {
						$companytopcustomer  = new Companytopcustomer(array('topcustomername' => Input::get('topcustomername')[$i], 'country_id' => Input::get('topcustomercountry')[$i], 'buyertype_id' => Input::get('topcustomertype')[$i]));
						$company->companytopcustomers()->save($companytopcustomer);
					}
				} elseif ($item != '') {
					if (Input::get('topcustomerdel')[$i] == '') {
						
						$companytopcustomer = Companytopcustomer::find($item);
						if (Input::get('topcustomername')[$i] == '') {
							$companytopcustomer = Companytopcustomer::destroy($item);
						} else {
							$companytopcustomer->topcustomername = Input::get('topcustomername')[$i];
							$companytopcustomer->country_id = Input::get('topcustomercountry')[$i];
							$companytopcustomer->buyertype_id = Input::get('topcustomertype')[$i];
							$companytopcustomer->save();
						}
					} else {
						$companytopcustomer = Companytopcustomer::destroy($item);
					}
				}
				$i++;
			}
		}
		$i = 0;
		if (Input::has('topsupplierid')) {
			$topsupplier = 1;
			foreach (Input::get('topsupplierid') as $item) {
				if ($item == '' && Input::get('topsupplierdel')[$i] == '') {
					if (Input::get('topsuppliername')[$i] != '') {
						$companytopsupplier  = new Companytopsupplier(array('topsuppliername' => Input::get('topsuppliername')[$i], 'suppliertype_id' => Input::get('topvendortype')[$i]));
						$company->companytopsuppliers()->save($companytopsupplier);
					}
				} elseif ($item != '') {
					if (Input::get('topsupplierdel')[$i] == '') {
						$companytopsupplier = Companytopsupplier::find($item);
						if (Input::get('topsuppliername')[$i] == '') {
							$companytopsupplier = Companytopsupplier::destroy($item);
						} else {
							$companytopsupplier->topsuppliername = Input::get('topsuppliername')[$i];
							$companytopsupplier->suppliertype_id = Input::get('topvendortype')[$i];
							$companytopsupplier->save();
						}
					} else {
						$companytopsupplier = Companytopsupplier::destroy($item);
					}
				}
				$i++;
			}
		}
		
		$company->business = 0;
		if ($companytype_id == 1 && $topsupplier == 1) {
			$company->business = 1;
		} else if (($companytype_id == 2 || $companytype_id == 4) && $topcustomer == 1) {
			$company->business = 1;
		} else if ($companytype_id == 3 && $topcustomer == 1 && $topsupplier == 1) {
			$company->business = 1;
		}
		$company->save();
		
		if (Input::get('signatoryname') != '') {
			$company->authsignatory = 1;
			$company->signatoryname = Input::get('signatoryname');
			$company->signatorydesignation = Input::get('signatorydesignation');
			$company->signatoryemail = Input::get('signatoryemail');
			$company->signatoryphone = Input::get('signatoryphone');
			$company->save();
			//auth signatory id attachment
			if (Input::get('signidfile') == '' && Input::get('signidattachid') == '') {
				DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '28')
				->where('attachable_type', 'company')->delete();
			} else if (Input::get('signidfile') != '' && Input::get('signidattachid') != '') {
				DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '28')
				->where('attachable_type', 'company')->where('id', '<>', Input::get('signidattachid'))->delete();
				DB::table('attachments')->where('id', Input::get('signidattachid'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
				'description' => 'Authorized signatory ID', 'attachmenttype_id' => 28, 'filename' => Input::get('signidfile')]);
			}
			//auth signatory passport attachment
			if (Input::get('signpptfile') == '' && Input::get('signpptattachid') == '') {
				DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '30')
				->where('attachable_type', 'company')->where('id', '<>', Input::get('signpptattachid'))->delete();
			} else if (Input::get('signpptfile') != '' && Input::get('signpptattachid') != '') {
				DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '30')
				->where('attachable_type', 'company')->where('id', '<>', Input::get('signpptattachid'))->delete();
				DB::table('attachments')->where('id', Input::get('signpptattachid'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
				'description' => 'Authorized signatory passport', 'attachmenttype_id' => 30, 'filename' => Input::get('signpptfile')]);
			}
			//auth signatory visa attachment
			if (Input::get('signvisafile') <> '' && Input::get('signvisaattachid') == '') {
				DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '29')
				->where('attachable_type', 'company')->where('id', '<>', Input::get('signvisaattachid'))->delete();
			} else if (Input::get('signvisafile') != '' && Input::get('signvisaattachid') != '') {
				DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '29')
				->where('attachable_type', 'company')->where('id', '<>', Input::get('signvisaattachid'))->delete();
				DB::table('attachments')->where('id', Input::get('signvisaattachid'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
				'description' => 'Authorized signatory visa', 'attachmenttype_id' => 29, 'filename' => Input::get('signvisafile')]);
			}
		}
		
		if (Input::get('bankname') != '') {
			$company->banks = 1;
			$company->accountname = Input::get('accountname');
			$company->bankname = Input::get('bankname');
			$company->accountnumber = Input::get('accountnumber');
			$company->iban = Input::get('iban');
			$company->routingcode = Input::get('routingcode');
			$company->swift = Input::get('swift');
			if (!Gate::allows('cr_ap')) {
				$company->confirmed = 0;
			}
			$company->save();
		}
		//return $this->view($company->id);
		if ($request->wantsJson()) {
			$company->load('country', 'city', 'companyowners', 'companybeneficials', 'companydirectors', 'attachments');
			return $company;
		} else {
				if (Input::get('newtab') == '') {
					if ($company->iscomplete) {
						return redirect('/companies/view/' . $company->id);
					} else {
						return redirect('/companies/' . $company->id);
					}					
				} else {
					return redirect('/companies/' . $company->id . '/' . Input::get('newtab'));
				}
		}
		
	}

	private function getCompanyTypeIdFromInput() {
		$companytype = Input::get('companytype_id');
			if (count($companytype) > 1)
				return '3';
			else 
				return $companytype[0];
	}
	
	public function paymenttermsview($companyid, $mode = '') {
		$company = Company::with('paymentterms')->find($companyid);
		$paymentterms = Paymentterm::all();
		return view('companies.paymentterms')->with('title', 'Assign payment terms')->with('company', $company)->with('mode', 'v')
		->with('paymentterms', $paymentterms->pluck('name', 'id'));
	}
	
	public function paymentterms($companyid, $mode = '') {
		$company = Company::with('paymentterms')->find($companyid);		
		$paymentterms = Paymentterm::where('active', 1)->get();
		return view('companies.paymentterms',[
			'title' => 'Assign payment terms',
			'company' => $company,
			'paymentterms' => $paymentterms->pluck('name', 'id')
		]);
	}
	
	public function savepaymentterms(Request $request, $companyid) {
		$rules = [
			'buyup.*' => 'required|numeric|min:0',
        ];
		$this->validate($request, $rules);
		if (Input::has('pt_id')) {
			$company = Company::find($companyid);
			$i = 0;
			$company->paymentterms()->detach();
			foreach (Input::get('pt_id') as $item) {
				echo $item, Input::get('pt_id')[$i], Input::get('paymenttermdel')[$i], '<br>';
				
				
				if (Input::get('paymenttermdel')[$i] == '') {					
					$company->paymentterms()->attach(Input::get('pt_id')[$i], ['buyup' => Input::get('buyup')[$i], 'active' => 1]);
				} else {
					$company->paymentterms()->detach(Input::get('pt_id')[$i]);
				}
				
				
				$i++;
			}
		}
		return redirect('/companies/paymenttermsview/' . $companyid);
	}
	
	public function supplierdeliveryview($companyid, $mode = '') {
		$company = Company::with('deliverytypes')->find($companyid);
		//$paymentterms = Paymentterm::all();
		$deliverytypes = Deliverytype::where('active', 1)->get();
		return view('companies.deliverytypes')->with('title', 'Assign delivery types')->with('company', $company)->with('mode', 'v')
		->with('deliverytypes', $deliverytypes->pluck('name', 'id'));
	}
	
	public function supplierdelivery($companyid, $mode = '') {
		$company = Company::with('deliverytypes')->find($companyid);
		if ($company->companytype_id == 1) {
			return view('message',[
				'title' => 'Assign delivery types',
				'message' => 'Company is a buyer. Cannot assign a delivery type.',
				'error' => true
			]);
		}
		$deliverytypes = Deliverytype::where('active', 1)->get();
		return view('companies.deliverytypes',[
			'title' => 'Assign delivery types',
			'company' => $company,
			'deliverytypes' => $deliverytypes->pluck('name', 'id')
		]);
	}
	
	public function savesupplierdelivery(Request $request, $companyid) {
		if (Input::has('dt_id')) {
			$company = Company::find($companyid);
			$i = 0;
			$company->deliverytypes()->detach();
			foreach (Input::get('dt_id') as $item) {
				echo $item, Input::get('dt_id')[$i], Input::get('deliverytypedel')[$i], '<br>';
				
				if (Input::get('deliverytypedel')[$i] == '') {					
					$company->deliverytypes()->attach(Input::get('dt_id')[$i]);
				} else {
					$company->deliverytypes()->detach(Input::get('dt_id')[$i]);
				}
				$i++;
			}
		}
		return redirect('/companies/supplierdeliveryview/' . $companyid);
	}
	
	public function supplierpaymenttermsview($companyid, $mode = '') {
		$company = Company::with('paymentterms')->find($companyid);
		$paymentterms = Paymentterm::all();
		return view('companies.supplierpaymentterms')->with('title', 'View supplier payment terms')->with('company', $company)->with('mode', 'v')
		->with('paymentterms', $paymentterms->pluck('name', 'id'));
	}
	
	public function supplierpaymentterms($companyid, $mode = '') {
		$company = Company::find($companyid);		
		$paymentterms = Paymentterm::all();
		return view('companies.supplierpaymentterms',[
			'title' => 'Assign supplier payment terms',
			'company' => $company,
			'paymentterms' => $paymentterms->pluck('name', 'id')
		]);
	}
	
	public function savesupplierpaymentterms(Request $request, $companyid) {
		$company = Company::find($companyid);
		$i = 0;
		$company->supplierterm_id = Input::get('supplierterm_id');
		$company->save();
		return redirect('/companies/supplierpaymenttermsview/' . $companyid);
	}
	
	public function activate(Request $request, $id) {
		$company = Company::find($id);
		$company->active = 1;
		$company->save();
		return redirect('/companies/view/' . $company->id);
	}
	
	public function deactivate(Request $request, $id) {
		$company = Company::find($id);
		$company->active = 0;
		$company->save();
		return redirect('/companies/view/' . $company->id);
	}
	
	public function searchunconfirmed(Request $request) {
		return $this->search($request, true, true);
	}
	
	public function searchstart(Request $request) {
		if(Gate::allows('co_cr'))
			abort(404);

		return $this->search($request);
	}
	
	public function search(Request $request, $startsearch = true, $unconfirmed = false)
    {
		if(Gate::allows('co_cr'))
			abort(404);

		$countries = Country::where('allowed', 1)->orderBy('countryname')->get();		
		$companytypes = Companytype::where('active', 1)->orderBy('id')->get();
		$roles = Auth::User()->roles;		
		$companyzero = $roles->where('company_id', '0');
		if ($companyzero->count() > 0 ) {
			$query = Company::with('paymentterms')->orderBy('companyname', 'asc');
		} else {
			$roles = $roles->pluck('id');
			$query = Company::whereHas('roles', function ($q) use($roles) {
			$q->whereIn('roles.id', $roles);
		})->orderBy('companyname', 'asc');
		}
		if (Input::get('companyname') != '') {
			$query = $query->where('companyname', 'like', '%' . Input::get('companyname') . '%');
		}
		if (Input::get('city_id') != '0' && Input::get('city_id') != '') {
			$query = $query->where('city_id', '=', Input::get('city_id'));
		}
		if (Input::get('country_id') != '0' && Input::get('country_id') != '') {
			$query = $query->where('country_id', '=', Input::get('country_id'));
			$cities = City::where('country_id', Input::get('country_id'))->where('active', 1)->orderBy('cityname')->get();
		} else {
			$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname')->get();
		}
		if (Input::get('companytype_id') != '0' && Input::get('companytype_id') != '') {
			if (Input::get('companytype_id') == 1) {
				$query = $query->whereIn('companytype_id', [1,3]);
			} elseif (Input::get('companytype_id') == 2) {
				$query = $query->whereIn('companytype_id', [2,3]);
			} else {
				$query = $query->where('companytype_id', '=', Input::get('companytype_id'));
			}			
		}
		if (Input::has('active')) {
			$query = $query->where('active', '1');
			$active = 1;
		} else {
			$active = 0;
		}		
		if ($startsearch) {
			$title = 'Companies';
			if ($unconfirmed) {
				$title = 'Unconfirmed companies';
				$query = $query->where('confirmed', 0);
			}
			//DB::enableQueryLog();			
			$companies = $query->get();
			//var_dump( DB::getQueryLog());
			if ($request->wantsJson()) {
				return $companies;
			} else {
				return View('companies.search')->with('title', $title)
				->with('active',$active)->with('companytypes', array('0' => 'All') + $companytypes->pluck('name', 'id')->all())
				->with('cities', array('0' => 'All') + $cities->pluck('cityname', 'id')->all())
				->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
				->with('unconfirmed', $unconfirmed)
				->with('companies', $companies);
			}
		} else {
			return View('companies.search')->with('title', 'Search companies')
			->with('active',$active)->with('companytypes', array('0' => 'All') + $companytypes->pluck('name', 'id')->all())
			->with('cities', array('0' => 'All') + $cities->pluck('cityname', 'id')->all())
			->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
			->with('unconfirmed', $unconfirmed);
		}
    }
	
	public function mysuppliers($id = 0) {
		// $hasReadyCompany = Auth::user()->hasReadyBuyerCompany();
		$buyerCompany = Auth::user()->getBuyerCompany();

		if($buyerCompany) {
			if ($id && $id != $buyerCompany->id)
				abort(404);
		}

		// if (!$hasReadyCompany)
		// 	return view('message',[
		// 		'title' => 'Manage Suppliers',
		// 		'message' => 'Cannot manage suppliers',
		// 		'description' => __('messages.eligcomp'),
		// 		'error' => true
		// 	]);

		if (!$id)
			return redirect('/companies/mysuppliers/' . $buyerCompany->id);
		
		// $buyerCompany = Auth::user()->getBuyerCompany();
		// $vendors = Vendor::where('companytype_id', Companytype::SUPPLIER_TYPE)->where('active', 1)->get();
		$companies = Company::with('vendors')->where('companytype_id', '!=', Companytype::SUPPLIER_TYPE)->where(['id' => $buyerCompany->id, 'active' => 1])->get();

		// $arrcompanies = Auth::user()->companies->pluck('id');
		return View('companies.mysuppliers',[
			'title' => 'My suppliers',
			'buyer_company' => $buyerCompany,
			// 'vendors' => $vendors
		]);
	}

	public function mybuyers($id = 0) {
		$company = Auth::user()->getSupplierCompany();

		if($company && $id && $id != $company->id)
				abort(404);

		if (!$id)
			return redirect('/companies/mybuyers/' . $company->id);

		return View('companies.mybuyers',[
			'title' => 'My buyers',
			'company' => $company
		]);
	}
	
	public function mysupplierssave() {
		$company = Auth::user()->getBuyerCompany();
		if (Input::has('del')) {
			$i = 0;
			$del_items = Input::get('del');		
			foreach ($del_items as $del) {
				if (Input::get('del')[$i] == '1') {
					$company->vendors()->detach(Input::get('vendorid')[$i]);
				} else {
					if (Input::get('newrow')[$i] == '1') {
						$company->vendors()->attach(Input::get('vendorid')[$i]);
					}
				}
				$i++;
			}
		}
		
		return redirect('/companies/mysuppliers/' . $company->id);
	}
	
	public function savesuppliersajax() {
		$buyerCompany = Auth::user()->getBuyerCompany();
		if (Input::has('del')) {
			$i = 0;
			foreach (Input::get('del') as $del) {
				if (Input::get('del')[$i] == '1') {
					$buyerCompany->vendors()->detach(Input::get('vendorid')[$i]);
				} else {
					if (Input::get('newrow')[$i] == '1') {
						$exist = false;
						foreach ($buyerCompany->vendors as $vendor) {
							if($vendor->id == Input::get('vendorid')[$i])
								$exist = true;
						}
						if (!$exist)
							$buyerCompany->vendors()->attach(Input::get('vendorid')[$i]);
					}
				}
				$i++;
			}
		}
		// $arrcompanies = Auth::user()->companies->pluck('id');
		// $companies = Company::with('vendors')->whereIn('id', $arrcompanies)->where('companytype_id', 1)->where('active', 1)->get();
		return response()->json($buyerCompany->refresh()->vendors);
	}

	public function dataAjax(Request $request)
	{
		$arrcompanies = Auth::user()->companies->pluck('id');
		$data = [];
		$search = $request->q;
		$data = DB::table("companies")
			->select("id", "companyname")
			->where('companyname', 'LIKE', "%$search%")
			->where('companytype_id', '<>', 1)
			->where('vendor_signed', 1)
			->where('active', 1)
			->whereNotIn('id', $arrcompanies)
			->get();
		return response()->json($data);
	}

	public function searchBuyers(Request $request)
	{
		$arrcompanies = Auth::user()->companies->pluck('id');
		
		$search = $request->q;
		$data = DB::table("companies")
			->select("id", "companyname")
			->where('companyname', 'LIKE', "%$search%")
			->whereIn('companytype_id', [1,3])
			->where('customer_signed', 1)
			->where('active', 1)
			->whereNotIn('id', $arrcompanies)
			->get();

		return response()->json($data);
	}
	
	public function searchSuppliers(Request $request)
	{
		$search = $request->q;
		$data = DB::table("companies")
			->select("id", "companyname")
			->where('companyname', 'LIKE', '%' . $search . '%')
			->whereIn('companytype_id', [2, 3])
			->where('vendor_signed', 1)
			->where('active', 1)
			->get();

		return response()->json($data);
	}
	
	public function SuppliersList(Request $request)
	{
		$search = Input::get('search');
		$perPage = 15;
		if (Input::get('page')) {
			$page = Input::get('page');
		} else {
			$page = 1;
		}
		//$pos = Purchaseorder::paginate(15);
		$companies = Company::where('companyname', 'like', '%' . Input::get('search') . '%')
		->whereIn('companytype_id', [2, 3])
		->where('vendor_signed', 1)
		->where('active', 1)
		->paginate($perPage,['*'],'page',$page);
		return view('test.new', ['companies' => $companies]);
		//DB::table($table)->paginate($perPage,['*'],'page',$page);
	}



	
	public function myBuyersSave()
	{
		$company = Auth::user()->getSupplierCompany();
		if (Input::has('del')) {
			$i = 0;
			$del_items = Input::get('del');
			foreach ($del_items as $del) {
				if (Input::get('del')[$i] == '1') {
					$company->buyers()->detach(Input::get('buyerid')[$i]);
				} else {
					if (Input::get('newrow')[$i] == '1') {
						$company->buyers()->attach(Input::get('buyerid')[$i]);
					}
				}
				$i++;
			}
		}

		return redirect('/companies/mybuyers/' . $company->id);
	}

	public function savebuyersajax() {
		$company = Auth::user()->getSupplierCompany();
		if (Input::has('del')) {
			$i = 0;
			foreach (Input::get('del') as $del) {
				if (Input::get('del')[$i] == '1') {
					$company->buyers()->detach(Input::get('buyerid')[$i]);
				} else {
					if (Input::get('newrow')[$i] == '1') {
						$exist = false;
						foreach ($company->buyers as $buyer) {
							if($buyer->id == Input::get('buyerid')[$i])
								$exist = true;
						}
						if (!$exist)
							$company->buyers()->attach(Input::get('buyerid')[$i]);
					}
				}
				$i++;
			}
		}

		return response()->json($company->refresh()->vendors);
	}
	
	public function companyshipaddr(Request $request) 
	{
		$addresses = Company::find(request("company_id"))->getSortedAddresses();

		$addressesList = [];
		foreach ($addresses as $address) {
			$inco = $address->incoterm->name;
			$addressesList[] = [
				'id' => $address->id,
				'name' => "$address->partyname ($inco) - $address->address"
			];
		}

		//\Log::debug(print_r($addressesList, 1));

		return [
			'addresses' => $addressesList,
			'paymentTerms' => Company::find(Input::get('company_id'))->paymentterms()->pluck('paymentterms.name', 'paymentterms.id')
		];
	}
	
	public function companypickupaddr(Request $request) 
	{
		$addresses = Company::find(request("company_id"))->getSortedPickupAddresses();

		$addressesList = [];
		foreach ($addresses as $address) {
			$addressesList[] = [
				'id' => $address->id,
				'name' => "$address->partyname - $address->address",
				'city' => $address->city->cityname,
				'country' => $address->city->country->countryname,
				'address' => $address->address,
				'partyname' => $address->partyname,
			];
		}

		//\Log::debug(print_r($addressesList, 1));

		return [
			'addresses' => $addressesList,
			'deliverytypes' => Company::find(Input::get('company_id'))->deliverytypes()->pluck('deliverytypes.name', 'deliverytypes.id')
		];
	}
	
	public function shippingaddress($shippingaddress = '') 
	{
		if ($shippingaddress == '') {
			$shippingaddress = Input::get('shippingaddress');
		}
		$address = Shippingaddress::where('address', $shippingaddress)->get();
		return $address->first();
	}
	
	public function shippingaddressdata($id = '') 
	{
		if ($id == '') {
			$id = Input::get('shippingaddress_id');
		}
		$address = Shippingaddress::with('company', 'city', 'city.country', 'incoterm')->find($id);
		return $address;		
	}
	
	public function pickupaddressdata($id = '') 
	{
		if ($id == '') {
			$id = Input::get('pickupaddress_id');
		}
		$address = Pickupaddress::with('company', 'city', 'city.country')->find($id);
		return response()->json($address);
	}
	
	public function getbuyup(Request $request) 
	{
		$paymentterm = Company::find(Input::get('company_id'))->paymentterms()->where('paymentterm_id', Input::get('paymentterm_id'));
		return $paymentterm->first()->pivot->buyup;
	}

	public function getVAT(Request $request) 
	{
		$countryId = Input::get('country_id');
		
		if ($countryId == 0)
			return 0;
		else {
			$company = Company::find(Input::get('company_id'));
			return $company->vat;
		}
	}
	
	public function roles(Request $request) 
	{
		$roles = Role::where('company_id', Input::get('company_id'))->get();
		return $roles;
	}
}
