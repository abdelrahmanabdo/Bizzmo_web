<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;

use App\Http\Requests\storecompanyrequest;

use App\Attachmenttype;
use App\City;
use App\Company;
use App\Companyattachment;
use App\Companyowner;
use App\Companybeneficial;
use App\Companydirector;
use App\Companytopproduct;
use App\Companytopcustomer;
use App\Companytype;
use App\Country;
use App\Paymentterm;
use App\Permission;
use App\Range;
use App\Role;
use App\Shippingaddress;
use App\Vendor;

use App\Jobs\Processcompany;

class companycontroller extends Controller
{
    public function view(Request $request, $id) {
		$company = Company::with(['companyowners' => function ($q) {
			$q->where('active', 1)
			->orderBy('ownername', 'desc');
		}])->with('companyowners.attachments', 'companydirectors.attachments', 'companytopproducts', 'companytopcustomers', 'country', 'city', 'creditrequests')->find($id);
		$company->incorporated = date("j/n/Y",strtotime($company->incorporated));
		$tradeattachment = $company->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $company->attachments->where('attachmenttype_id', 9)->first();
		
		if ($request->wantsJson()) {
			return $company;
		} else {
			return view('companies.manage')->with('title', 'View company')->with('mode', 'v')->with('company', $company)
			->with('tradeattachment', $tradeattachment)->with('assocattachment', $assocattachment);
		}
	}
	
	public function select() {
		return view('companies.select')->with('title', 'Select company type');
	}
	
	public function confirm($id) {
		$company = Company::findOrFail($id);
		if ($company->confirmed == 1) {
			return view('message')->with('title', 'Confirm company')->with('message', 'Cannot confirm. Company is already confirmed.');
		}
		$company->confirmed = 1;
		$company->active = 1;
		$company->save();
		Processcompany::dispatch($company);
		$tradeattachment = $company->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $company->attachments->where('attachmenttype_id', 9)->first();
		return view('companies.manage')->with('title', 'View company')->with('mode', 'v')->with('company', $company)
		->with('tradeattachment', $tradeattachment)->with('assocattachment', $assocattachment);
	}
	
	public function attachstart($id) {
		$company = Company::find($id);
		$company->incorporated = date("j/n/Y",strtotime($company->incorporated));
		$attachmenttypes = Attachmenttype::where('module_id', 1)->get();
		$attachments = Companyattachment::where('company_id', $id)->get();
		return view('companies.attach')->with('title', 'Company attachments')->with('company', $company)
		->with('attachmenttypes', $attachmenttypes->pluck('name', 'id'))->with('attachments', $attachments);
	}
		
	public function attach(Request $request, $id) {
		$rules = [
			'attachmenttype_id' => 'required|min:1',
			'attachmentdescription' => 'required|max:100',
			'attachment' => 'required|file|mimes:pdf|max:2048',
        ];
		$this->validate($request, $rules);
		$path = $request->file('attachment')->store('images');
		$companyattachment = new Companyattachment;
		$companyattachment->company_id = $id;
		$companyattachment->attachmenttype_id  = Input::get('attachmenttype_id');
		$companyattachment->attachmentname  = Input::get('attachmentdescription');
		$companyattachment->path  = $path;
		$companyattachment->save();
		return $this->attachstart($id);
	}
	
	public function attachment($id) {
		$companyattachment = Companyattachment::find($id);
		return view('companies.attachment')->with('title', 'View company attachments')->with('companyattachment', $companyattachment);
	}
	
	public function manage($id = '')
    {	
	if ($id != '') {
		$company = Company::with('attachments', 'companyowners', 'companyowners.attachments')->find($id);
		$company->incorporated = date("j/n/Y",strtotime($company->incorporated));
		$tradeattachment = $company->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $company->attachments->where('attachmenttype_id', 9)->first();
		$countries = Country::where('active', 1)->orWhere('id', $company->country_id)->orderBy('countryname')->get();
		$cities = City::where('country_id', $company->country_id)->where('active', 1)->orWhere('id', $company->city_id)->orderBy('cityname', 'asc')->get();
		$employees = Range::where('active', 1)->where('rangetype', 'personel')->orWhere('id', $company->employees)->orderBy('id')->get();
		$percentages = Range::where('active', 1)->where('rangetype', 'percent10')->orderBy('id')->get();
		return view::make('companies/manage')->with('company', $company)->with('title', 'Edit company')
		->with('tradeattachment', $tradeattachment)->with('assocattachment', $assocattachment)
		->with('employees',$employees->pluck('name', 'id'))->with('percentages',$percentages)->with('arrpercentages',$percentages->pluck('name', 'id'))
		->with('countries',$countries->pluck('countryname', 'id'))->with('cities',$cities->pluck('cityname', 'id'));
	} else {
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
		$employees = Range::where('active', 1)->where('rangetype', 'personel')->orderBy('id')->get();
		$percentages = Range::where('active', 1)->where('rangetype', 'percent10')->orderBy('id')->get();
	    return view::make('companies/manage')->with('title', 'Create company')
		->with('employees',$employees->pluck('name', 'id'))->with('percentages',$percentages)->with('arrpercentages',$percentages->pluck('name', 'id'))
		->with('countries',$countries->pluck('countryname', 'id'))->with('cities',$cities->pluck('cityname', 'id'));
	}
    }
	
	public function save(storecompanyrequest $request, $id = 0) {		 
		if ($id == 0) {
			$company = new Company;
			$company->created_by = Auth::user()->id;
			$company->companytype_id = 1;
			$company->tenant_id = Auth::user()->tenant_id;
			//$shippingaddress = new Shippingaddress;
			//$shippingaddress->address = Input::get('address');
			//$shippingaddress->district = Input::get('district');
			//$shippingaddress->city_id = Input::get('city_id');
		} else {
			$company = Company::find($id);			
		}
		$company->companyname = Input::get('companyname');
		$company->address = Input::get('address');
		$company->district = Input::get('district');
		$company->city_id = Input::get('city_id');
		$company->country_id = Input::get('country_id');
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
		$company->save();
		//Default payment terms
		if ($id == 0) {
			$company->paymentterms()->attach(1, ['buyup' => Paymentterm::find(1)->buyup, 'active' => 1]);			
			//$shippingaddress->company_id = $company->id;
			//$shippingaddress->save();
		}
		//Default role
		if ($id == 0) {
			$role = new Role(array('rolename' => $company->companyname . ' Admin', 'company_id' => $company->id, 'active' => 1, 'systemrole' => 1, 'created_by' => Auth::User()->id, 'updated_by' => Auth::User()->id));
			$role->save();
			$role->permissions()->attach(Permission::where('module_id', '1')->pluck('id'));
			$role->users()->attach(Auth::User()->id);
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
			DB::table('attachments')->where('attachable_id', $company->id)->where('attachmenttype_id', '9')
			->where('attachable_type', 'company')->where('id', '<>', Input::get('assocattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('assocattachid'))->update(['attachable_type' => 'company', 'attachable_id' => $company->id, 
			'description' => 'Articles of association', 'attachmenttype_id' => 9, 'filename' => Input::get('assocfile')]);
		}
		//Company sub data
		$i = 0;
		if (Input::has('ownerid')) {
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
						DB::table('attachments')->where('attachable_id', $companyowner->id)
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
						DB::table('attachments')->where('attachable_id', $companybeneficial->id)
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
		$i = 0;
		if (Input::has('directorid')) {
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
			foreach (Input::get('topproductid') as $item) {
				if ($item == '' && Input::get('topproductdel')[$i] == '') {
					$companytopproduct  = new Companytopproduct(array('topproductname' => Input::get('topproductname')[$i], 'topproductrevenue'=> Input::get('topproductrevenue')[$i]));
					$company->companytopproducts()->save($companytopproduct);
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
		$i = 0;
		if (Input::has('topcustomerid')) {
			foreach (Input::get('topcustomerid') as $item) {
				if ($item == '' && Input::get('topcustomerdel')[$i] == '') {
					$companytopcustomer  = new Companytopcustomer(array('topcustomername' => Input::get('topcustomername')[$i]));
					$company->companytopcustomers()->save($companytopcustomer);
				} elseif ($item != '') {
					if (Input::get('topcustomerdel')[$i] == '') {
						$companytopcustomer = Companytopcustomer::find($item);
						$companytopcustomer->topcustomername = Input::get('topcustomername')[$i];
						//$companytopcustomer->topcustomerrevenue = Input::get('topcustomerrevenue')[$i];
						$companytopcustomer->save();
					} else {
						$companytopcustomer = Companytopcustomer::destroy($item);
					}
				}
				$i++;
			}
		}
		//return $this->view($company->id);
		if ($request->wantsJson()) {
			$company->load('country', 'city', 'companyowners', 'companybeneficials', 'companydirectors', 'attachments');
			return $company;
		} else {
			return redirect('/companies/view/' . $company->id);
		}
		
	}
	
	public function paymenttermsview($companyid, $mode = '') {
		$company = Company::with('paymentterms')->find($companyid);
		$paymentterms = Paymentterm::all();
		return view('companies.paymentterms')->with('title', 'Assign payment terms')->with('company', $company)->with('mode', 'v')
		->with('paymentterms', $paymentterms->pluck('name', 'id'));
	}
	
	public function paymentterms($companyid, $mode = '') {
		$company = Company::with('paymentterms')->find($companyid);
		if ($company->creditlimit == 0) {
			return view('message')->with('title', 'Assign payment terms')->with('message', 'Company has no credit limit. Cannot assign payment terms other than cash.');
		}
		$paymentterms = Paymentterm::all();
		return view('companies.paymentterms')->with('title', 'Assign payment terms')->with('company', $company)
		->with('paymentterms', $paymentterms->pluck('name', 'id'));
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
	
	public function searchunconfirmed(Request $request) {
		return $this->search($request, true, true);
	}
	
	public function searchstart(Request $request) {
		return $this->search($request, false);
	}
	
	public function search(Request $request, $startsearch = true, $unconfirmed = false)
    {
		$countries = Country::where('active', 1)->orderBy('countryname')->get();		
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
			$query = $query->where('companytype_id', '=', Input::get('companytype_id'));
		}
		if (Input::has('active')) {
			$query = $query->where('active', '1');
			$active = 1;
		} else {
			$active = 0;
		}		
		if ($startsearch) {
			$title = 'Search companies';
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
		$arrcompanies = Auth::user()->companies->pluck('id');
		$companies = Company::with('vendors')->whereIn('id', $arrcompanies)->where('companytype_id', 1)->where('active', 1)->get();
		$vendors = Vendor::where('companytype_id', 2)->where('active', 1)->get();
		return View('companies.mysuppliers')->with('title', 'My suppliers')->with('company_id', $id)
		->with('companies', $companies)->with('arrcompanies', $companies->pluck('companyname', 'id'))->with('arrvendors', $vendors->pluck('companyname', 'id'));
	}
	
	public function mysupplierssave() {
		if (Input::has('del')) {
			$i = 0;
			foreach (Input::get('del') as $del) {
				$company = Company::find(Input::get('companyid')[$i]);
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
		$arrcompanies = Auth::user()->companies->pluck('id');
		$companies = Company::with('vendors')->whereIn('id', $arrcompanies)->where('companytype_id', 1)->where('active', 1)->get();
		return View('companies.mysuppliers')->with('title', 'My suppliers')
		->with('companies', $companies)->with('mode', 'v');
	}
	
	public function dataAjax(Request $request)
    {
		$arrcompanies = Auth::user()->companies->pluck('id');
    	$data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = DB::table("companies")
            		->select("id","companyname")
            		->where('companyname','LIKE',"%$search%")
					->where('companytype_id', 2)
					->where('active', 1)
					->whereNotIn('id', $arrcompanies)
            		->get();
        }
        return response()->json($data);
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
		$address = Shippingaddress::with('city', 'city.country')->find($id);
		return $address;
	}
	
	public function getbuyup(Request $request) 
	{
		$paymentterm = Company::find(Input::get('company_id'))->paymentterms()->where('paymentterm_id', Input::get('paymentterm_id'));
		return $paymentterm->first()->pivot->buyup;
	}
	
	public function roles(Request $request) 
	{
		$roles = Role::where('company_id', Input::get('company_id'))->get();
		return $roles;
	}
}
