<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;

use App\Attachment;
use App\Creditrequest;
use App\Creditrequestsecurity;

class attachmentcontroller extends Controller
{	
	public function upload(Request $request) {	
		$path = $request->file('attach')->store('uploads/' . date('Y') . '/' . date('m'));
		$attachment  = new Attachment;
		$attachment->path = $path;
		$attachment->created_by = Auth::user()->id;
		$attachment->updated_by = Auth::user()->id;
		$attachment->description = 'Security check';
		if ($request->has('cr_id')) {
			$attachment->attachable_type = 'creditrequest';			
			$attachment->attachable_id = $request->input('cr_id');
			$attachment->attachmenttype_id = 7; //security check
			$attachment->filename = $request->input('filename');
		}
		$attachment->save();
		if ($request->has('cr_id')) {
			$creditrequest = Creditrequest::find($request->input('cr_id'));
			$creditrequestsecurities = Creditrequestsecurity::where('creditrequest_id', $creditrequest->id)->where('securitytype_id', 4)->get();
			foreach ($creditrequestsecurities as $security) {
				$security->document  = $request->input('filename');
				$security->status = 'signing_complete';
				$security->save();
			}
			$creditrequest->updatestatus();
		}
		return $attachment->id;
	}
}
