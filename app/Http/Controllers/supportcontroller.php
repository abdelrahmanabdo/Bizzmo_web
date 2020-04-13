<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Input;
use View;

use App\Status;
use App\Support;

class supportcontroller extends Controller
{	
	public function view($id)
    {	
		$support = Support::find($id);
		return view('support/manage')->with('support', $support)->with('mode','v')->with('title', 'View incident');
    }
	
	public function manage($id)
    {	
		$support = Support::find($id);
		if ($support->status_id == Status::SUPPORT_CLOSED) {
			return view('message',[
				'title' => 'Change incident',
				'message' => 'Cannot change. Incident is already closed.',
				'error' => true
			]);
		}
		return view('support/manage')->with('support', $support)->with('title', 'Change incident');
    }
	
	public function searchopen() {
		return $this->search(true, 20);
	}
	
	public function searchstart() {
		return $this->search(false);	
	}
	
	public function search($startsearch = true, $status = 0)
	{
		$query = Support::orderBy('id');
		if (Input::get('name') != '') {
			$query = $query->where('name', 'like', '%' . Input::get('name') . '%');
		}
		if (Input::get('company') != '') {
			$query = $query->where('company', 'like', '%' . Input::get('company') . '%');
		}
		if (Input::get('message') != '') {
			$query = $query->where('message', 'like', '%' . Input::get('message') . '%');
		}
		$statuses = Status::where('statustype', 'support');
		if ($startsearch) {
			if (Input::get('status_id') == Status::SUPPORT_OPEN || $status == Status::SUPPORT_OPEN)
				$query = $query->where('status_id', Status::SUPPORT_OPEN);
			else if (Input::get('status_id') == Status::SUPPORT_CLOSED || $status == Status::SUPPORT_CLOSED)
				$query = $query->where('status_id', Status::SUPPORT_CLOSED);
			else
				$query = $query->whereIn('status_id', [Status::SUPPORT_CLOSED, Status::SUPPORT_OPEN]);
			
			$supports = $query->get();
			return view('support.search', [
				'title' => 'Support',
				'supports' => $supports,
				'statuses' => $statuses->pluck('name', 'id')->all()
			]);
		}
		
		return view('support.search',[
			'title' => 'Support',
			'status' => '',
			'statuses' => $statuses->pluck('name', 'id')->all()
		]);		
	}
	
	public function close(Request $request, $id)
	{
		$rules = [
			'resolution' => 'required|max:190',
        ];
		$customMessages = [
			'resolution.required' => 'Resolution is required',
			'resolution.max' => 'Resolution should not be more than 190 characters',
		];
		$this->validate($request, $rules, $customMessages);
		$support = Support::find($id);
		$support->resolution = Input::get('resolution');
		$support->status_id = Status::SUPPORT_CLOSED;
		$support->updated_by = Auth::user()->id;
		$support->save();
		return redirect('/supports/view/' . $support->id);
	}
	
	public function save(Request $request)
	{
		$rules = [
			'name' => 'required|max:60',
			'title' => 'required|max:60',
			'email' => 'required|email|max:60',
			'company' => 'required|max:60',
			'message' => 'required|max:255',
        ];
		$this->validate($request, $rules);
		$support = new Support;
		$support->name = Input::get('name');
		$support->title = Input::get('title');
		$support->company = Input::get('company');
		$support->email = Input::get('email');
		$support->message = Input::get('message');
		$support->updated_by = 0; // Set value for avoiding mysql error 
		$support->save();
		return view('message', [
					'title' => 'Support request',
					'message' => 'We have received your support request',
					'description' => 'We will get back to you as soon as possible.',
					'home_link' => 'true'
				]);
	}

	public function getReportIssue() {
		return view('support.report_issue');
	}

	public function postReportIssue(Request $request) {
		$rules = [
			'title' => 'required|max:60',
			'order_number' => 'required|max:999999999|integer',
			'message' => 'required|max:255',
			'comp_acc_info' => 'required|max:255',
			'supp_acc_info' => 'required|max:255',
		];
		
		$customMessages = [
			'comp_acc_info.required' => 'Company account info is required',
			'supp_acc_info.required' => 'Supplier account info is required',
			'comp_acc_info.max' => 'Company account info should not be more than 255 characters',
			'supp_acc_info.max' => 'Supplier account info should not be more than 255 characters',
			'message.required' => 'Description is required',
			'message.max' => 'Description should not be more than 255 characters',
		];
		$this->validate($request, $rules, $customMessages);
		$support = new Support;
		$support->title = Input::get('title');
		$support->order_number = Input::get('order_number');
		$support->message = Input::get('message');
		$support->comp_acc_info = Input::get('comp_acc_info');
		$support->supp_acc_info = Input::get('supp_acc_info');
		$support->created_by = Auth::user()->id;
		$support->updated_by = 0; // Set value for avoiding mysql error 
		$support->save();
		return view('message', [
			'title' => 'Support request',
			'message' => 'We have received your reported issue',
			'description' => 'We will get back to you as soon as possible.',
			'home_link' => 'true'
		]);
	}

	public function getUserIssues() {
		$issues = Auth::user()->getIssues();
		return view('support.list_issues', [
			'title' => 'List issues',
			'issues' => $issues
		]);
	}

	public function viewUserIssue($id) {
		$issue = Support::findOrFail($id);
		return view('support.view', [
			'issue' => $issue
		]);
	}
}
