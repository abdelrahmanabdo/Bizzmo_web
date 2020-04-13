<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;

use App\Materialgroup;

class materialgroupcontroller extends Controller
{
	public function index($id = '')
    {
		$materialgroups = Materialgroup::all();	
		 if ($id != '') {		 
			$materialgroup = Materialgroup::find($id);
			return View::make('materialgroups/manage')->with('title', 'Manage material groups')->with('materialgroup', $materialgroup)->with('materialgroups', $materialgroups);
		 } else {		 
			return View::make('materialgroups/manage')->with('title', 'Manage Materialgroups')->with('materialgroups', $materialgroups);
		}
    }

	public function save(Request $request, $id = 0)
	{
		$rules = [
			'name' => ['required', 'max:60', Rule::unique('materialgroups')->ignore($id, 'id')],
			'description' => 'required|max:60',
        ];
		$customMessages = [
			'name.required' => 'Material group name is required',
			'name.max' => 'Material group name should not be more than 60 characters',
			'name.unique' => 'Material group name is already used',
			'description.required' => 'Material group description is required',
			'description.max' => 'Material group description should not be more than 60 characters',
		];
		$this->validate($request, $rules, $customMessages);
		if (Input::get('id') == '') {
			$materialgroup = new Materialgroup;
			$materialgroup->created_by = Auth::user()->id;
		} else {
			$materialgroup = Materialgroup::find(Input::get('id'));			
		}	
		$materialgroup->updated_by = Auth::user()->id;		
		$materialgroup->name = Input::get('name');
		$materialgroup->description = Input::get('description');
		if (Input::has('active')) {
			$materialgroup->active = true;
		} else {
			$materialgroup->active = false;
		}
		$materialgroup->save();
		return redirect('/materialgroups');
	}
	
	public function activate($id)
	{
		$materialgroup = Materialgroup::find($id);
		if (Gate::denies('access-materialgroup', $materialgroup)) {
			abort(401,'You are not authorized to access this materialgroup');
		}
		$materialgroup->active = 1;
		$materialgroup->save();
		return Redirect::to('materialgroups');
	}

	public function deactivate($id)
	{
		$materialgroup = Materialgroup::find($id);
		if (Gate::denies('access-materialgroup', $materialgroup)) {
			abort(401,'You are not authorized to access this materialgroup');
		}
		$materialgroup->active = 0;
		$materialgroup->save();
		return Redirect::to('materialgroups');
	}

}
