<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Gate;
use Input;
use View;

use DocuSign\eSign as docusignclient;

use App\Attachment;
use App\City;
use App\Company;
use App\Country;
use App\Currency;
use App\Incoterm;
use App\Materialgroup;
use App\Paymentterm;
use App\Permission;
use App\Purchaseorder;
use App\Purchaseorderitem;
use App\Role;
use App\Shippingaddress;
use App\Unit;
use App\Vendor;

use App\Jobs\Processpocredit;
use App\Jobs\Processdelivery;
use App\Jobs\Processdeliverysignature;
use App\Brand;
use App\Helpers\RightSignatureHelper;
use App\Attachmenttype;
use App\Companytype;
use OwenIt\Auditing\Models\Audit;
use App\Status;

class transactioncontroller extends Controller
{	
	public function pending() {
		if(Gate::any(['cr_ap', 'fi_ar', 'fi_ap']))
			return redirect('/purchaseorders');

		//customer POs
		$customerPOs = Auth::user()->pospendingcustomer();
		//vendor POs
		$vendorPOs = Auth::user()->pospendingvendor();
		$purchaseorders = $customerPOs->merge($vendorPOs);

		// Pending Quotations
		$quotations = Auth::user()->quotationsPending();

		return View('purchaseorders.search')
		->with('pendingPosTitle', 'Pending POs')
		->with('pendingQuotationsTitle', 'Pending Quotations')
		->with('hideconditions', true)
		->with('purchaseorders', $purchaseorders)
		->with('quotations', $quotations);
	}
	
}
