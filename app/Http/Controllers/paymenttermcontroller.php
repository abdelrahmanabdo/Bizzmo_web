<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;

use App\Paymentterm;

class paymenttermcontroller extends Controller
{
	public function index($id = '')
    {
		$paymentterms = Paymentterm::all();	
		 if ($id != '') {		 
			$paymentterm = Paymentterm::find($id);
			return View::make('paymentterms/manage')->with('title', 'Manage Paymentterms')->with('paymentterm', $paymentterm)->with('paymentterms', $paymentterms);
		 } else {		 
			return View::make('paymentterms/manage')->with('title', 'Manage Paymentterms')->with('paymentterms', $paymentterms);
		}
    }

	public function save(Request $request, $id = 0)
	{
		$rules = [
			'name' => ['required', 'max:60', Rule::unique('paymentterms')->ignore($id, 'id')],
			'buyup' => 'required|numeric',
        ];
		$this->validate($request, $rules);
		if (Input::get('id') == '') {
			$paymentterm = new Paymentterm;
			$paymentterm->created_by = Auth::user()->id;
		} else {
			$paymentterm = Paymentterm::find(Input::get('id'));			
		}	
		$paymentterm->updated_by = Auth::user()->id;		
		$paymentterm->name = Input::get('name');
		$paymentterm->buyup = Input::get('buyup');
		if (Input::has('active')) {
			$paymentterm->active = true;
		} else {
			$paymentterm->active = false;
		}
		$paymentterm->save();
		return redirect('/paymentterms');
	}

	
}
