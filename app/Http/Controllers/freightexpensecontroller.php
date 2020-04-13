<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use View;

use App\Freightexpense;

class freightexpensecontroller extends Controller
{
	public function list() 
	{		
		$freightexpenses = Freightexpense::where('active', 1)->get();
		return $freightexpenses->pluck('name', 'id');
	}
}
