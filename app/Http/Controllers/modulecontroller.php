<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;

use App\Company;
use App\Module;
use App\Permission;

class modulecontroller extends Controller
{
	public function permissions()
    {
		$company = Company::find(Input::get('company_id'));
		if ($company->companytype_id == 1) {
			$permissions = Permission::where('module_id', 1)->where('active', true)->get();
		 } else {		 
			$permissions = Permission::where('module_id', 2)->where('active', true)->get();
		}
		return $permissions->pluck('display_name', 'id');
    }

	
}
