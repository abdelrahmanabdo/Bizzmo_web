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
use App\Roletype;
use App\Permission;

class rolecontroller extends Controller
{	
	public function searchstart() {
		return $this->search(false);
	}
	
	public function search($startsearch = true)
	{			
		$companies = Auth::User()->companypermissions(['ro_cr', 'ro_ch', 'ro_vw', 'ro_dl']);
		if (Auth::User()->isSysadmin) {
			$roleslist = Role::where('company_id', 0)->orderBy('rolename', 'asc')->get();
		} else {
			$roleslist = Role::whereIn('company_id', $companies->pluck('id'))->orderBy('rolename', 'asc')->get();
		}
		$showdetails = 0;
		if (Input::has('showdetails') || $startsearch == 0) {			
			$showdetails = 1;
		}
		if ($startsearch) {
			if (Auth::User()->isSysadmin) {
				$query = Role::with('permissions', 'users')->where('company_id', 0)->orderBy('rolename', 'asc');
			} else {
				$query = Role::with('permissions', 'users')->whereIn('company_id', $companies->pluck('id'))->orderBy('rolename', 'asc');
			}
			if (Input::get('role_id') != '0' && Input::get('role_id') != '') {
				$query = $query->where('roles.id', Input::get('role_id'));
			}
			if (Input::get('rolename') != '') {
				$query = $query->where('rolename', 'like', '%' . Input::get('rolename') . '%');
			}							
			if (Input::get('company_id') != '0' && Input::get('company_id') != '') {
				$query = $query->where('roles.company_id', Input::get('company_id'));
			}
			if (Input::get('user_id') != '0' && Input::get('user_id') != '') {
				$user_id = Input::get('user_id');
				$query = $query->whereHas('users', function ($q) use($user_id) {
					$q->where('role_user.user_id', $user_id);
				});
			}
			$roles = $query->get();
			return view('roles.search')->with('title', 'Roles')
			->with('showdetails', $showdetails)
			->with('roles', $roles)->with('roleslist', array('0' => 'All') + $roleslist->pluck('rolename', 'id')->all())
			->with('companies', array('0' => 'All') + $companies->pluck('companyname', 'id')->all());
		} else {
			return view('roles.search')->with('title', 'Roles')
			->with('showdetails', $showdetails)
			->with('roleslist', array('0' => 'All') + $roleslist->pluck('rolename', 'id')->all())
			->with('companies', array('0' => 'All') + $companies->pluck('companyname', 'id')->all());
		}
	}
	
	public function delete($id)
	{		
		$role = Role::find($id);
		$role->permissions()->detach();
		$role->users()->detach();
		$role->delete();
		return redirect('roles');
	}
	
	public function deletec($id)
	{			
		$role = Role::with('permissions', 'users')->find($id);
		$rolepermissions = Role::find($id)->permissions()->get();
		return view('roles.manage')->with('title', 'Delete Role')->with('mode','d')
		->with('role', $role)->with('rolepermissions',$rolepermissions);
	}
	
	public function view($id)
	{			
		$role = Role::with('permissions', 'users')->find($id);
		$companies = Auth::User()->companypermissions(['ro_vw']);		
		$rolepermissions = Role::find($id)->permissions()->get();
		return view('roles.manage')->with('title', 'View Role')->with('mode','v')
		->with('role', $role)->with('rolepermissions',$rolepermissions)->with('companies',$companies);
	}
	
	public function users($id)
	{			
		$role = Role::with('users')->find($id);
		$rolepermissions = Role::find($id)->permissions()->get();
		$users = User::where('client_id',Auth::user()->client_id)->get();
		return view('roles.users')->with('title', 'Role users')
		->with('role', $role)->with('rolepermissions',$rolepermissions)->with('users',$users);
	}
	
	public function assign(Request $request)
	{
		$role = Role::with('permissions')->find(Input::get('id'));
		$role->users()->detach();	
		$i = 0;
		if (Input::has('cbuser')) {
			foreach (Input::get('cbuser') as $user) {
				$user = User::find(Input::get('cbuser')[$i]);
				$role->users()->save($user);
				$i = $i + 1;
			}
		}
		return redirect()->action('rolecontroller@view', [$role->id]);
	}
	
	public function save(Request $request)
	{
		$rules = [
			'rolename' => 'required|max:60|unique:roles,rolename,' . Input::get('id') . ',id,company_id,' . Input::get('company_id'),
			'permissionnum' => 'required|numeric|min:1'
        ];
		$this->validate($request, $rules);
		if (Input::get('id') == '') {
			$role = new Role;
			$role->company_id = Input::get('company_id');
			$role->created_by = Auth::user()->id;
		} else {
			$role = Role::with('permissions')->find(Input::get('id'));			
			$role->permissions()->detach();
		}
		$role->updated_by = Auth::user()->id;
		$role->rolename = Input::get('rolename');		
		$role->save();
		if (Input::has('sbTarget')) {	
			$selectedstatus = array();
			foreach (Input::get('sbTarget') as $item) {				
				array_push($selectedstatus, $item);
			}
			$role->permissions()->attach($selectedstatus);
		}
		return redirect()->action('rolecontroller@view', [$role->id]);
	}
	
	public function create()
	{
		$companies = Auth::User()->companypermissions(['ro_cr']);
		if ($companies->count() == 0 && !Auth::user()->isSysadmin) {
			abort(403,'Cannot add a new role. You must create a company first');
		}
		$user = User::find(Auth::user()->id);
		if (Auth::user()->isSysadmin) {
			$permissions = Permission::where('module_id', 3)->where('active', true)->get();
		} else {
			if ($companies->first()->companytype_id == 1) {
				$permissions = Permission::where('module_id', 1)->where('active', true)->get();
			} else {
				$permissions = Permission::where('module_id', 2)->where('active', true)->get();
			}		
		}
		return view('roles.edit')->with('title', 'New role')->with('companies', $companies->pluck('companyname', 'id'))
		->with('permissions', $permissions);
	}
    
	public function manage($id = '')
    {	
		$companies = Auth::User()->companypermissions(['ro_ch']);
		if ($id != '') {
			$role = Role::with('permissions')->find($id);
			$rolepermissions = Role::find($id)->permissions()->get();
			//$permissions = Permission::where('active', true)->whereNotIn('id', $rolepermissions->pluck('id'))->get();			
			if (Auth::user()->isSysadmin) {
				$permissions = Permission::where('module_id', 3)->where('active', true)->whereNotIn('id', $rolepermissions->pluck('id'))->get();
			} else {
				if ($role->company->companytype_id == 1) {
					$permissions = Permission::where('module_id', 1)->where('active', true)->whereNotIn('id', $rolepermissions->pluck('id'))->get();
				} else {
					$permissions = Permission::where('module_id', 2)->where('active', true)->whereNotIn('id', $rolepermissions->pluck('id'))->get();
				}		
			}
			return view('roles.edit')->with('title', 'Edit Role')->with('companies', $companies->pluck('companyname', 'id'))
			->with('permissions', $permissions)->with('role',$role)->with('rolepermissions',$rolepermissions);
		} else {		 
			$permissions = Permission::where('active', true)->get();
			return view('roles.edit')->with('title', 'Create Role')->with('companies', $companies->pluck('companyname', 'id'))
			->with('permissions', $permissions);
		}
    }
	
	public function manage1($id = '')
    {
		$permissions = Permission::where('active', true)->get();
		 if ($id != '') {
			$role = Role::with('permissions')->find($id);
			$rolepermissions = Role::find($id)->permissions()->get();
			//print_r($rolepermissions);
			return view('roles.manage')->with('title', 'Edit Role')
			->with('permissions', $permissions)->with('role',$role)->with('rolepermissions',$rolepermissions);
		 } else {		 
			return view('roles.manage')->with('title', 'Create Role')
			->with('permissions', $permissions);
		}
    }
	
}
