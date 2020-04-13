<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use Input;
use View;

use App\City;
use App\Company;
use App\Country;
use App\Incoterm;
use App\Pickupaddress;

class pickupaddresscontroller extends Controller
{
	public function view($id)
    {
		$pickupaddress = Pickupaddress::find($id);
		return View::make('pickupaddresses/manage')->with('title', 'View Pickup Address')->with('mode', 'v')
		->with('pickupaddress', $pickupaddress);		
    }
	
	public function manage(Request $request, $id = '')
	{
		$companies = Company::with('pickupaddresses')->whereIn('id', Auth::user()->companypermissions(['co_cr', 'co_ch'])->pluck('id'))->get();
		$activecompanies = $companies->whereIn('companytype_id', [2,3]);
		if ($activecompanies->count() == 0) {
			return view('message', [
				'title' => 'Address',
				'message' => 'Cannot create a new pickup address.',
				'description' => 'No suppliers defined',
				'home_link' => true,
				'error' => true
			]);
		}
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
		$incoterms = Incoterm::where('active', 1)->orderBy('name')->get();
		if ($id != '') {
			$pickupaddress = Pickupaddress::find($id);
			return view::make('pickupaddresses/manage')->with('title', 'Change Address')
			->with('companies', $activecompanies)
			->with('pickupaddress', $pickupaddress)
			->with('incoterms', $incoterms->pluck('name', 'id')->all())
			->with('countries', $countries->pluck('countryname', 'id'))->with('cities',$cities->pluck('cityname', 'id'));
		} else {			
			return view::make('pickupaddresses/manage')->with('title', 'New Address')
			->with('companies', $activecompanies)
			->with('incoterms', $incoterms->pluck('name', 'id')->all())
			->with('countries', $countries->pluck('countryname', 'id')->all())->with('cities',$cities->pluck('cityname', 'id'));
		}			
	}
	
	public function save(Request $request, $id = 0)
	{
		//$phoneRegex = "/^\+\d+(-| )?\d+(-| )?\d+(-| )?\d+(-| )?\d+$/";
		$phoneRegex = "/^[\+|\(|\)|\d|\- ]*$/";
		$rules = [
			'partyname' => 'required|max:180',
			'address' => 'required|max:180',
			'phone' => ['max:20', 'required', "regex:$phoneRegex"],
			'fax' => ['required', "regex:$phoneRegex"],
			'email' => 'nullable|email',
			'po_box' => 'max:60',
        ];
		$messages = [
			'partyname.required' => 'Please provide party name',
			'partyname.max' => 'Party name should not exceed 180 characters',
			'address.required' => 'Please provide address',
			'address.max' => 'Address should not exceed 180 characters',
			'phone.required' => 'Please provide phone',
			'phone.max' => 'Phone should not exceed 60 characters',
			'fax.required' => 'Please provide fax',
			'fax.max' => 'Fax should not exceed 60 characters',
			'po_box.max' => 'PO Box should not exceed 60 characters',
		];		
		$this->validate($request, $rules, $messages);
		if ($id == '') {
			$pickupaddress = new Pickupaddress;
			$pickupaddress->created_by = Auth::user()->id;			
		} else {
			$pickupaddress = Pickupaddress::find($id);			
		}	
		$pickupaddress->updated_by = Auth::user()->id;
		$pickupaddress->company_id = Input::get('company_id');
		$pickupaddress->partyname = Input::get('partyname');
		$pickupaddress->address = Input::get('address');
		$pickupaddress->phone = Input::get('phone');
		$pickupaddress->fax = Input::get('fax');
		$pickupaddress->email = Input::get('email');
		$pickupaddress->city_id = Input::get('city_id');
		$pickupaddress->po_box = Input::get('po_box');
		if (Input::has('cbdefault')) {
			$pickupaddress->default = true;
			DB::table('pickupaddresses')->where('company_id', Input::get('company_id'))->update(['default' => 0]);
		} else {
			$pickupaddress->default = false;
		}
		$pickupaddress->save();
		return redirect('/pickupaddresses/view/' . $pickupaddress->id);
	}
	
	public function list() 
	{
		//return Auth::user()->companypermissions(['po_cr'])->pluck('id');
		$companies = Company::with('pickupaddresses')->whereIn('id', Auth::user()->companypermissions(['vp_rl', 'vp_ch'])->pluck('id'))->orderBy('companyname')->get();
		$activecompanies = $companies->whereIn('companytype_id', [2,3]);
		return view::make('pickupaddresses.search')->with('title', 'Pickup addresses')
		->with('companies', $activecompanies);
	}
}
