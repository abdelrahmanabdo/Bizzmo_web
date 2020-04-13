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
use App\Shippingaddress;

class shippingaddresscontroller extends Controller
{
	public function view($id)
    {
		$shippingaddress = Shippingaddress::find($id);
		return View::make('shippingaddresses/manage')->with('title', 'View Shipping Address')->with('mode', 'v')
		->with('shippingaddress', $shippingaddress);		
    }
	
	public function manage(Request $request, $id = '')
	{
		$companies = Company::with('shippingaddresses')->whereIn('id', Auth::user()->companypermissions(['co_cr', 'co_ch'])->pluck('id'))->get();
		$activecompanies = $companies->whereIn('companytype_id', [1,3]);
		if ($activecompanies->count() == 0) {
			return view('message', [
				'title' => 'Address',
				'message' => 'Cannot create a new shipping address.',
				'description' => 'No buyers defined',
				'home_link' => true,
				'error' => true
			]);
		}
		$countries = Country::where('active', 1)->orderBy('countryname')->get();
		$cities = City::where('country_id', $countries->first()->id)->where('active', 1)->orderBy('cityname', 'asc')->get();
		$incoterms = Incoterm::where('active', 1)->orderBy('name')->get();
		if ($id != '') {
			$shippingaddress = Shippingaddress::find($id);
			return view::make('shippingaddresses/manage')->with('title', 'Change Address')
			->with('companies', $activecompanies)
			->with('shippingaddress', $shippingaddress)
			->with('incoterms', $incoterms->pluck('name', 'id')->all())
			->with('countries', $countries->pluck('countryname', 'id')->all())->with('cities',$cities->pluck('cityname', 'id'));
		} else {			
			return view::make('shippingaddresses/manage')->with('title', 'New Address')
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
			'partyname' => 'required|max:120',
			'address' => 'required|max:180',
			'delivery_address' => 'required|max:180',
			'phone' => ['max:20', 'required', "regex:$phoneRegex"],
			'fax' => ['required', "regex:$phoneRegex"],
			'email' => 'nullable|email',
			'po_box' => 'max:60',
			'country_name' => 'required_if:country_id,0|max:60',
			'city_name' => 'required_if:country_id,0|max:60',
        ];
		$messages = [
			'partyname.required' => 'Please provide party name',
			'partyname.max' => 'Party name should not exceed 120 characters',
			'address.required' => 'Please provide address',
			'address.max' => 'Address should not exceed 180 characters',
			'delivery_address.required' => 'Please provide delivery address',
			'delivery_address.max' => 'Delivery address should not exceed 180 characters',
			'phone.required' => 'Please provide phone',
			'phone.max' => 'Phone should not exceed 60 characters',
			'fax.required' => 'Please provide fax',
			'fax.max' => 'Fax should not exceed 60 characters',
			'po_box.max' => 'PO Box should not exceed 60 characters',
			'country_name.required_if' => 'Please provide country name',
			'country_name.max' => 'PO Box should not exceed 60 characters',
			'city_name.required_if' => 'Please provide city name',
			'city_name.max' => 'PO Box should not exceed 60 characters',
		];		
		$this->validate($request, $rules, $messages);
		if ($id == '') {
			$shippingaddress = new Shippingaddress;
			$shippingaddress->created_by = Auth::user()->id;			
		} else {
			$shippingaddress = Shippingaddress::find($id);			
		}	
		$shippingaddress->updated_by = Auth::user()->id;
		$shippingaddress->company_id = Input::get('company_id');
		$shippingaddress->partyname = Input::get('partyname');
		$shippingaddress->address = Input::get('address');
		$shippingaddress->phone = Input::get('phone');
		$shippingaddress->fax = Input::get('fax');
		$shippingaddress->email = Input::get('email');
		$shippingaddress->city_id = Input::get('city_id');
		$shippingaddress->city_name = Input::get('city_name');
		$shippingaddress->country_name = Input::get('country_name');
		$shippingaddress->delivery_address = Input::get('delivery_address');
		$shippingaddress->delivery_city_id = Input::get('delivery_city_id');
		$shippingaddress->incoterm_id = Input::get('incoterm_id');
		$shippingaddress->po_box = Input::get('po_box');

		if (Input::has('cbvat')) {
			$shippingaddress->vatexempt = true;
		} else {
			$shippingaddress->vatexempt = false;
		}
		if (Input::has('cbdefault')) {
			$shippingaddress->default = true;
			DB::table('shippingaddresses')->where('company_id', Input::get('company_id'))->update(['default' => 0]);
		} else {
			$shippingaddress->default = false;
		}
		$shippingaddress->save();
		return redirect('/shippingaddresses/view/' . $shippingaddress->id);
	}
	
	public function list() 
	{
		//return Auth::user()->companypermissions(['po_cr'])->pluck('id');
		$companies = Company::with('shippingaddresses')->whereIn('id', Auth::user()->companypermissions(['po_cr'])->pluck('id'))->orderBy('companyname')->get();
		$activecompanies = $companies->whereIn('companytype_id', [1,3]);
		return view::make('shippingaddresses.search')->with('title', 'Shipping addresses')
		->with('companies', $activecompanies);
	}
	
	public function vatexemptlist() 
	{
		$status = Input::get('status');
		$showcontrols = true;
		switch ($status) {
			case '':
				$query = Shippingaddress::with('company')->where('vatexempt', 1);
				break;
			case 0:
				$query = Shippingaddress::with('company')->where('vatexempt', 1);
				break;
			case 1:
				$query = Shippingaddress::with('company')->where('vat', 0)->whereNotNull('exempt_by');
				$showcontrols = false;
				break;
			case 2:
				$query = Shippingaddress::with('company')->where('vat', 1)->whereNotNull('exempt_by');
				$showcontrols = false;
				break;
		}
		
		if (Input::get('search') != '') {
			$query = $query->whereHas('company', function($q){
					$q->where('companyname', 'like', '%' . Input::get('search') . '%');
				});
				
			
		}
		
		$shippingaddresses = $query->get();
		return view::make('shippingaddresses.vatexempt')->with('title', 'VAT exempt requests')
		->with('shippingaddresses', $shippingaddresses)->with('showcontrols', $showcontrols);
	}
	
	public function vatapprove($id) 
	{
		$shippingaddresses = Shippingaddress::find($id);
		$shippingaddresses->vat = 0;
		$shippingaddresses->vatexempt = 0;
		$shippingaddresses->exempt_by = Auth::user()->id;
		$shippingaddresses->save();
		$shippingaddresses = Shippingaddress::where('vatexempt', 1)->get();		
		return view::make('shippingaddresses.vatexempt')->with('title', 'VAT exempt requests')
		->with('shippingaddresses', $shippingaddresses);
	}
	
	public function vatreject($id) 
	{
		$shippingaddresses = Shippingaddress::find($id);
		$shippingaddresses->vat = 1;
		$shippingaddresses->vatexempt = 0;
		$shippingaddresses->exempt_by = Auth::user()->id;
		$shippingaddresses->save();
		$shippingaddresses = Shippingaddress::where('vatexempt', 1)->get();		
		return view::make('shippingaddresses.vatexempt')->with('title', 'VAT exempt requests')
		->with('shippingaddresses', $shippingaddresses);
	}
}
