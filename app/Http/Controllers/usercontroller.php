<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Auth;
use DB;
use Gate;
use Input;

use App\Company;
use App\Role;
use App\User;

use App\Jobs\Processregistration;
use App\Phone;

class usercontroller extends Controller
{
    public function view($id)
    {	
		$companies = Auth::User()->companypermissions(['us_cr', 'us_ch', 'us_vw']);
		$user = User::with('roles')->find($id);
		if (Auth::User()->isSysadmin) {
			$user->roles = $user->roles->whereIn('company_id', 0);
		} else {
			$user->roles = $user->roles->whereIn('company_id', $companies->pluck('id'));
		}
		return view('users/manage',[
			'title' => 'View user',
			'user' => $user,
			'mode' => 'v',
			'companies' => $companies->pluck('companyname', 'id')
		]);
    }
	
	public function assign($id)
    {	
		return $this->manage($id, true);
    }
	
	public function manage($id = '', $assign = false)
    {
		if ($id != '') {
			$companies = Auth::User()->companypermissions(['us_cr']);
			if (Auth::user()->isSysadmin) {
				$company = new Company;
				$company->id = 0;
				$company->companyname =  config('app.companyname');
				$companies = collect([$company]);
			}
			$roles = Role::where('active', 1)->where('company_id', $companies->first()->id)->get();
			$user = User::with('roles')->find($id);
			if ($assign) {
				return view('users/manage')->with('user', $user)->with('title', 'Assign roles to user')->with('assign', '1')
				->with('companies', $companies->pluck('companyname', 'id'))->with('roles', $roles->pluck('rolename', 'id'));;
			} else {
				return view('users/manage')->with('user', $user)->with('title', 'Change user')
				->with('companies', $companies->pluck('companyname', 'id'))->with('roles', $roles->pluck('rolename', 'id'));;
			}			
		} else {
			$companies = Auth::User()->companypermissions(['us_cr']);
			if ($companies->count() == 0 && !Auth::user()->isSysadmin) {
				abort(403,'You cannot add a user now. You need to create a company first.');
			}
			if (Auth::user()->isSysadmin) {
				$roles = Role::where('active', 1)->where('company_id', 0)->get();
				$company = new Company;
				$company->id = 0;
				$company->companyname =  config('app.companyname');
				$companies = collect([$company]);
			} else {
				$roles = Role::where('active', 1)->where('company_id', $companies->first()->id)->get();
			}
			return view('users/manage')->with('title', 'Add User')
			->with('companies', $companies->pluck('companyname', 'id'))->with('roles', $roles->pluck('rolename', 'id'));
		}
    }

	public function roles($id)
	{		
		$user = User::with('roles', 'branches')->find($id);
		if (Gate::denies('access-user', $user)) {
			abort(401,'You are not authorized to access this user');
		}		
		$branches = Auth::user()->branches()->lists('branchname', 'branch_id');					
		$roles = Role::where('client_id', Auth::user()->client_id)->lists('rolename', 'id');			
		$companies = Auth::user()->companies('ro_us',1);
		//echo $companies->first()->id;
		$firstid =  $companies->first()->id;
		$companies = $companies->lists('companyname', 'id');		
		//DB::enableQueryLog();
		$firstcompanybranches = Branch::with('company')->whereHas('users', function($q) use($firstid)
			{
				$q->where('user_id', Auth::user()->id);
			})->where('branches.company_id', $firstid)->get()->lists('branchname', 'id');
		//var_dump( DB::getQueryLog());	
		return view('users.assign')->with('user', $user)->with('branchname', '')->with('companies', $companies)
		->with('branches', $branches)->with('roles', $roles)->with('title', 'Assign Users')->with('firstcompanybranches', $firstcompanybranches);
	}
	
	 public function searchstart() {
		 return $this->search();
	 }
	
	public function search($startsearch = true)
	{
		$companies = Auth::User()->companypermissions(['us_cr', 'us_ch', 'us_vw', 'us_as']);
		if (Auth::User()->isSysadmin) {
			$query = User::with('roles')->whereHas('roles', function ($query) use ($companies) {
				$query->where('company_id', 0);
			})->orderBy('created_at');
		} else {
			$query = User::with('roles')->whereHas('roles', function ($query) use ($companies) {
				$query->whereIn('company_id', $companies->pluck('id'));
			})->orderBy('created_at');
		}		
		if (Input::get('name') != '') {
			$query = $query->where('name', 'like', '%' . Input::get('name') . '%');
		}							
		if (Input::get('email') != '') {
			$query = $query->where('email', 'like', '%' . Input::get('email') . '%');
		}		
		if (Input::get('active') == 'on') {
			$query = $query->where('active', '=', '1');
		}		
		$printerfreindly = Input::get('printerfreindly');
		if ($startsearch) {
			$users = $query->get();
			return view('users.search')->with('title', 'Users')->with('hideconditions', true)->with('active', Input::get('active'))->with('users', $users);
		} else {
			return view('users.search')->with('title', 'Users')->with('hideconditions', true)->with('active', Input::get('active'));
		}			
	}

	public function save(Request $request, $id = '')
	{
		$rules = [
			'email' => ['required', 'max:60', 'email', Rule::unique('users', 'email')->ignore($id)],
			'name' => 'required|max:60',
			'title' => 'required|max:60',
			'password' => 'min:5|max:15|confirmed|required_with:password_confirmation',
			'rolecount' => 'required|integer|min:1',
		];
		$customMessages = [
			'rolecount.required' => 'You should choose one permission at least to save',
			'rolecount.min' => 'You should choose one permission at least to save',
			'rolecount.integer' => 'You should choose one permission at least to save',
			'title.required' => 'Title is required',
			'title.max' => 'Title should not exceed 60 characters in length',
			'email.required' => 'Email address is required',
			'email.max' => 'Email address should not exceed 60 characters in length',
			'email.email' => 'Must be a valid email address',
			'email.unique' => 'This email is already used',
		];

		$this->validate($request, $rules, $customMessages);

		if (Input::get('id') == '') {
			$user = new User;
			$user->created_by = Auth::user()->id;
			$user->tenant_id = Auth::user()->tenant_id;
			$user->email_token = str_random(20);
			$user->password = bcrypt(Input::get('password'));
			if (Auth::User()->isSysadmin) {
				$user->verified = 1;
			}
		} else {
			$user = User::find(Input::get('id'));
		}
		$user->updated_by = Auth::user()->id;
		$user->name = Input::get('name');
		$user->title = Input::get('title');
		$user->email = Input::get('email');
		if (Input::has('active')) {
			$user->active = true;
		} else {
			$user->active = false;
		}
		$user->save();
		if (Input::get('id') == '') {
			// Save phone record
			$phone = new Phone();
			$phone->user_id = $user->id;
			$phone->phone = "XXX";
			$phone->code = $this->generatePIN();
			$phone->save();
			
			Processregistration::dispatch($user);
		}
		//$user->roles()->detach();
		if (Input::has('del')) {
			$i = 0;
			foreach (Input::get('del') as $del) {
				if (Input::get('del')[$i] == '1') {
					$user->roles()->detach(Input::get('roleid')[$i]);
				} else {
					if (Input::get('newrow')[$i] == '1') {
						$user->roles()->attach(Input::get('roleid')[$i]);
					}
				}
				$i++;
			}
		}
		return $this->view($user->id);
	}	
	
	public function activate($id)
	{
		$user = User::find($id);
		if (Gate::denies('access-user', $user)) {
			abort(401,'You are not authorized to access this user');
		}
		$user->active = 1;
		$user->save();
		return Redirect::to('users');
	}

	public function deactivate($id)
	{
		$user = User::find($id);
		if (Gate::denies('access-user', $user)) {
			abort(401,'You are not authorized to access this user');
		}
		$user->active = 0;
		$user->save();
		return Redirect::to('users');
	}

	function generatePIN($digits = 4)
	{
		$i = 0; //counter
		$pin = ""; //our default pin is blank.
		while ($i < $digits) {
        //generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return $pin;
	}
}
