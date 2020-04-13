<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;
use Gate;

use App\Attachmenttype;
use App\City;;
use App\Vendor;
use App\Vendorattachment;
use App\Vendortopproduct;
use App\Vendortopcustomer;
use App\Country;
use App\Permission;
use App\Range;
use App\Role;

class vendorcontroller extends Controller
{
    public function view($id) {
		$vendor = Vendor::with('attachments')->find($id);
		$vendor->incorporated = date("j/n/Y",strtotime($vendor->incorporated));
		$tradeattachment = $vendor->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $vendor->attachments->where('attachmenttype_id', 9)->first();
		return view('vendors.manage')->with('title', 'View vendor')->with('mode', 'v')->with('vendor', $vendor)
		->with('tradeattachment', $tradeattachment)->with('assocattachment', $assocattachment);
	}
	
	public function confirm($id) {
		$vendor = Vendor::findOrFail($id);
		$tradeattachment = $vendor->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $vendor->attachments->where('attachmenttype_id', 9)->first();
		$vendor->confirmed = 1;
		$vendor->active = 1;
		$vendor->save();
		return view('vendors.manage')->with('title', 'View vendor')->with('mode', 'v')->with('vendor', $vendor)
		->with('tradeattachment', $tradeattachment)->with('assocattachment', $assocattachment);
	}
	
	public function attachstart($id) {
		$vendor = Vendor::find($id);
		$vendor->incorporated = date("j/n/Y",strtotime($vendor->incorporated));
		$attachmenttypes = Attachmenttype::where('module_id', 1)->get();
		$attachments = Vendorattachment::where('vendor_id', $id)->get();
		return view('vendors.attach')->with('title', 'Vendor attachments')->with('vendor', $vendor)
		->with('attachmenttypes', $attachmenttypes->pluck('name', 'id'))->with('attachments', $attachments);
	}
		
	public function attach(Request $request, $id) {
		$rules = [
			'attachmenttype_id' => 'required|min:1',
			'attachmentdescription' => 'required|max:100',
			'attachment' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
		$this->validate($request, $rules);
		$path = $request->file('attachment')->store('images');
		$vendorattachment = new Vendorattachment;
		$vendorattachment->vendor_id = $id;
		$vendorattachment->attachmenttype_id  = Input::get('attachmenttype_id');
		$vendorattachment->attachmentname  = Input::get('attachmentdescription');
		$vendorattachment->path  = $path;
		$vendorattachment->save();
		return $this->attachstart($id);
	}
	
	public function attachment($id) {
		$vendorattachment = Vendorattachment::find($id);
		return view('vendors.attachment')->with('title', 'View vendor attachments')->with('vendorattachment', $vendorattachment);
	}
	
	public function manage($id = '')
    {	
	if ($id != '') {
		$vendor = Vendor::find($id);
		$vendor->incorporated = date("j/n/Y",strtotime($vendor->incorporated));
		$tradeattachment = $vendor->attachments->where('attachmenttype_id', 5)->first();
		$assocattachment = $vendor->attachments->where('attachmenttype_id', 9)->first();
		$countries = Country::where('active', 1)->orWhere('id', $vendor->country_id)->orderBy('countryname')->get();
		$cities = City::where('country_id', $vendor->country_id)->where('active', 1)->orWhere('id', $vendor->city_id)->orderBy('cityname')->get();
		$employees = Range::where('active', 1)->where('rangetype', 'personel')->orWhere('id', $vendor->employees)->orderBy('id')->get();
		$percentages = Range::where('active', 1)->where('rangetype', 'percent10')->orderBy('id')->get();
	    return view::make('vendors/manage')->with('vendor', $vendor)->with('tradeattachment', $tradeattachment)->with('assocattachment', $assocattachment)
		->with('employees',$employees->pluck('name', 'id'))->with('percentages',$percentages)->with('arrpercentages',$percentages->pluck('name', 'id'))
		->with('countries',$countries->pluck('countryname', 'id'))->with('cities',$cities->pluck('cityname', 'id'));
	} else {
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname')->get();
		$employees = Range::where('active', 1)->where('rangetype', 'personel')->orderBy('id')->get();
		$percentages = Range::where('active', 1)->where('rangetype', 'percent10')->orderBy('id')->get();
	    return view::make('vendors/manage')
		->with('employees',$employees->pluck('name', 'id'))->with('percentages',$percentages)->with('arrpercentages',$percentages->pluck('name', 'id'))
		->with('countries',$countries->pluck('countryname', 'id'))->with('cities',$cities->pluck('cityname', 'id'));
	}
    }
	
	public function save(Request $request, $id = 0) {
		$rules = [
            'companyname' => ['required', 'max:60', Rule::unique('companies')->ignore($id, 'id')],
			'address' => 'required|max:60',
			'district' => 'required|max:60',
			'phone' => 'required|max:60',
			'fax' => 'required|max:60',
			'pobox' => 'max:60',
			'email' => 'required|max:60|email',
			'license' => 'required|max:60',
			'tradefile' => 'required',
			'assocfile' => 'required',
			'tax' => 'required|max:60',
			'incorporated' => 'required|date_format:j/n/Y',
			'operating' => 'required|max:120',
			'employees' => 'required|integer',
			'website' => 'max:60',
			'accountname' => 'required|max:60',
			'bankname' => 'required|max:60',
			'accountnumber' => 'required|max:60',
			'iban' => 'required|max:60',
			'routingcode' => 'required|max:60',
			'swift' => 'required|max:60',
			'topproductname.*' => 'required|max:60',
			'topproductrevenue.*' => 'required|numeric',
			'topproductcount' => 'required|integer|min:1',
			'topcustomername.*' => 'required|max:60',
			'topcustomercount' => 'required|integer|min:1',
        ];
		$customMessages = [
			'topcustomercount.required' => 'At least one customer must be entered',
			'topcustomercount.integer' => 'At least one customer must be entered',
			'topcustomercount.min' => 'At least one customer must be entered',
		];
		
		$niceNames = [
			'topcustomername.*' => 'Customer name'
		]; 
	
		$this->validate($request, $rules, [], $niceNames);		 
		 
		if ($id == 0) {
			$vendor = new Vendor;
			$vendor->created_by = Auth::user()->id;
			$vendor->companytype_id = 2;
			$vendor->tenant_id = Auth::user()->tenant_id;
		} else {
			$vendor = Vendor::find($id);			
		}
		$vendor->companyname = Input::get('companyname');
		$vendor->address = Input::get('address');
		$vendor->district = Input::get('district');
		$vendor->city_id = Input::get('city_id');
		$vendor->country_id = Input::get('country_id');
		$vendor->email = Input::get('email');
		$vendor->phone = Input::get('phone');
		$vendor->fax = Input::get('fax');
		$vendor->pobox = Input::get('pobox');
		$vendor->license = Input::get('license');
		$vendor->tax = Input::get('tax');
		$date = date_create_from_format("j/n/Y",Input::get('incorporated'));
		$vendor->incorporated = $date->format('Y-m-d');
		$vendor->employees = Input::get('employees');
		$vendor->operating = Input::get('operating');		
		$vendor->website = Input::get('website');
		$vendor->accountname = Input::get('accountname');
		$vendor->bankname = Input::get('bankname');
		$vendor->accountnumber = Input::get('accountnumber');
		$vendor->iban = Input::get('iban');
		$vendor->routingcode = Input::get('routingcode');
		$vendor->swift = Input::get('swift');
		$vendor->updated_by = Auth::user()->id;
		$vendor->save();
		//Default role
		if ($id == 0) {
			$role = new Role(array('rolename' => $vendor->companyname . ' Admin', 'company_id' => $vendor->id, 'active' => 1, 'systemrole' => 1, 'created_by' => Auth::User()->id, 'updated_by' => Auth::User()->id));
			$role->save();
			$role->permissions()->attach(Permission::where('module_id', '2')->pluck('id'));
			$role->users()->attach(Auth::User()->id);
		}
		//trade license attachment
		if (Input::get('tradefile') <> '' && Input::get('tradeattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $vendor->id)->where('attachmenttype_id', '5')
			->where('attachable_type', 'vendor')->where('id', '<>', Input::get('tradeattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('tradeattachid'))->update(['attachable_type' => 'vendor', 'attachable_id' => $vendor->id, 
			'description' => 'Trade license', 'attachmenttype_id' => 5, 'filename' => Input::get('tradefile')]);
		}
		//asrticle of assoc attachment
		if (Input::get('assocfile') <> '' && Input::get('assocattachid') <> '') {
			DB::table('attachments')->where('attachable_id', $vendor->id)->where('attachmenttype_id', '9')
			->where('attachable_type', 'vendor')->where('id', '<>', Input::get('assocattachid'))->delete();
			DB::table('attachments')->where('id', Input::get('assocattachid'))->update(['attachable_type' => 'vendor', 'attachable_id' => $vendor->id, 
			'description' => 'Articles of association', 'attachmenttype_id' => 9, 'filename' => Input::get('assocfile')]);
		}
		//Vendor sub data
		$i = 0;
		if (Input::has('topproductid')) {
			foreach (Input::get('topproductid') as $item) {
				if ($item == '' && Input::get('topproductdel')[$i] == '') {
					$vendortopproduct  = new Vendortopproduct(array('topproductname' => Input::get('topproductname')[$i], 'topproductrevenue'=> Input::get('topproductrevenue')[$i]));
					$vendor->vendortopproducts()->save($vendortopproduct);
				} elseif ($item != '') {
					if (Input::get('topproductdel')[$i] == '') {
						$vendortopproduct = Vendortopproduct::find($item);
						$vendortopproduct->topproductname = Input::get('topproductname')[$i];
						$vendortopproduct->topproductrevenue = Input::get('topproductrevenue')[$i];
						$vendortopproduct->save();
					} else {
						$vendortopproduct = Vendortopproduct::destroy($item);
					}
				}
				$i++;
			}
		}
		$i = 0;
		if (Input::has('topcustomerid')) {
			foreach (Input::get('topcustomerid') as $item) {
				if ($item == '' && Input::get('topcustomerdel')[$i] == '') {
					$vendortopcustomer  = new Vendortopcustomer(array('topcustomername' => Input::get('topcustomername')[$i]));
					$vendor->vendortopcustomers()->save($vendortopcustomer);
				} elseif ($item != '') {
					if (Input::get('topcustomerdel')[$i] == '') {
						$vendortopcustomer = Vendortopcustomer::find($item);
						$vendortopcustomer->topcustomername = Input::get('topcustomername')[$i];
						//$vendortopcustomer->topcustomerrevenue = Input::get('topcustomerrevenue')[$i];
						$vendortopcustomer->save();
					} else {
						$vendortopcustomer = Vendortopcustomer::destroy($item);
					}
				}
				$i++;
			}
		}
		return redirect('/vendors/view/' . $vendor->id);
	}

	public function searchunconfirmed() {
		// redirect user to their created company 
		if (Gate::allows('co_cr')) {
			$userCompany = Auth::user()->companies->first();
			if (!$userCompany->confirmed && $userCompany->iscomplete)
					return redirect('/companies/view/' . $userCompany->id);

			if (!$userCompany->iscomplete)
				return redirect('/companies/' . $userCompany->id);
			
			abort(403);
		}
		return $this->search(true, true);
	}
	
	public function searchstart() {
		return $this->search(false);
	}
	
	public function search($startsearch = true, $unconfirmed = false)
    {
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname')->get();
		$roles = Auth::User()->roles;
		$companyzero = $roles->where('company_id', '0');
		if ($companyzero->count() > 0 ) {
			$query = Vendor::where('companytype_id', '2')->orderBy('companyname', 'asc');
		} else {
			$roles = $roles->pluck('id');
			$query = Vendor::where('companytype_id', '2')->whereHas('roles', function ($q) use($roles) {
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
		}
		if (Input::has('active')) {
			$query = $query->where('active', '1');
			$active = 1;
		} else {
			$active = 0;
		}		
		if ($startsearch) {
			$title = 'Search vendors';
			if ($unconfirmed) {
				$title = 'Unconfirmed vendors';
				$query = $query->where('confirmed', 0);
			}
			//DB::enableQueryLog();
			$vendors = $query->get();
			//var_dump( DB::getQueryLog());
			return View('vendors.search')->with('title', $title)
			->with('active',$active)
			->with('cities', array('0' => 'All') + $cities->pluck('cityname', 'id')->all())
			->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
			->with('unconfirmed', $unconfirmed)
			->with('vendors', $vendors);
		} else {
			return View('vendors.search')->with('title', 'Search vendors')
			->with('active',$active)
			->with('cities', array('0' => 'All') + $cities->pluck('cityname', 'id')->all())
			->with('countries', array('0' => 'All') + $countries->pluck('countryname', 'id')->all())
			->with('unconfirmed', $unconfirmed);
		}
    }
}
