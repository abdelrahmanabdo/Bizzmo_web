<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use View;

use App\City;

class countrycontroller extends Controller
{
	public function cities($country_id = '') 
	{
		if ($country_id == '') {
			$country_id = Input::get('country_id');
		}
		$cities = City::where('active', 1)->where('country_id', $country_id)->orderBy('cityname', 'asc')->get();
		return $cities->pluck('cityname', 'id');
	}
}
