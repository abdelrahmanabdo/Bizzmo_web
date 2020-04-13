<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as response;

use App\Jobs\ProcessFetchContract;
use App\Jobs\ProcessUpdateCompanySignedField;
use DB;

class AdobeSignController extends Controller {
	
	// Webhook for agreement events
	public function contract(Request $request) {
		if($request['status'] && $request['documentKey']) {
			$agreementId = $request['documentKey'];
            DB::table('attachments')->where('envelope', $request['documentKey'])->update(['status' => $request['status']]);

			if ($request['status'] == 'SIGNED') {
				ProcessUpdateCompanySignedField::dispatch($agreementId);
				ProcessFetchContract::dispatch($agreementId);
			}
		}
		return response()->json(['response' => 'Ok']);
	}
}

