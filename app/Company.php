<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use DB;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Company extends Model implements AuditableContract
{
	use Auditable;	
	protected $appends = array('canrequestcredit', 'canincreasecredit', 'activeappointment', 'creditpos', 'iscomplete');
	
	public function companyattachments()
    {
        return $this->hasMany('App\Companyattachment');
	}
	
	public function companyProfile()
    {
        return $this->hasOne('App\CompanyProfile');
    }
	
	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
	
	public function shippingaddresses()
    {
        return $this->hasMany('App\Shippingaddress');
    }
	
	public function pickupaddresses()
    {
        return $this->hasMany('App\Pickupaddress');
    }
	
	public function employeenumber()
    {
        return $this->belongsTo('App\Range', 'employees');
    }
	
	public function creditrequests()
    {
        return $this->hasMany('App\Creditrequest');
    }
	
    public function companyowners()
    {
        return $this->hasMany('App\Companyowner');
    }
	
	public function companybeneficials()
    {
        return $this->hasMany('App\Companybeneficial');
    }
	
	public function companydirectors()
    {
        return $this->hasMany('App\Companydirector');
    }
	
	public function companytopproducts()
    {
        return $this->hasMany('App\Companytopproduct');
    }
	
	public function companytopcustomers()
    {
        return $this->hasMany('App\Companytopcustomer');
    }
	
	public function companytopsuppliers()
    {
        return $this->hasMany('App\Companytopsupplier');
    }
	
	public function industries()
    {
		return $this->belongsToMany('App\Industry')->withTimestamps();
    }
	
	public function roles()
	{
		return $this->hasMany('App\Role');
	}

	public function appointments()
	{
		return $this->hasMany('App\Appointment');
	}
	
	public function companytype()
    {
        return $this->belongsTo('App\Companytype');
    }
	
	public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
	
	public function country()
    {
        return $this->belongsTo('App\Country');
    }
	
	public function vendors()
	{
		return $this->belongsToMany('App\Vendor', 'company_vendor', 'owner_company', 'favourite_company')->withTimestamps();
	}
	
	public function buyers()
	{
		return $this->belongsToMany('App\Vendor', 'company_vendor', 'owner_company', 'favourite_company')->withTimestamps();
	}
	
	public function city()
    {
        return $this->belongsTo('App\City');
    }
	
	public function paymentterms()
	{
		return $this->belongsToMany('App\Paymentterm')->withPivot('buyup')->withPivot('active')->withTimestamps();
	}
	
	public function deliverytypes()
	{
		return $this->belongsToMany('App\Deliverytype')->withPivot('active')->withTimestamps();
	}
	
	public function vendorpaymentterm()
    {
        return $this->belongsTo('App\Paymentterm', 'supplierterm_id');
    }
	
	public function getCanrequestcreditAttribute() {
		$creditrequests = Creditrequest::where('company_id', $this->id)->whereIn('creditstatus_id', [1, 2, 4, 5])->get();
		if ($creditrequests->count() == 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getCanincreasecreditAttribute() {
		$creditrequests = Creditrequest::where('company_id', $this->id)->whereIn('creditstatus_id', [2, 4, 5, 6])->get();
		if ($creditrequests->count() == 0 && $this->creditrequests->count() > 0) {
			$lastrequest = $this->creditrequests->last();
			$datetime1 = date_create($lastrequest->approved_on);
			$today = new \DateTime();
			$interval = date_diff($datetime1, $today);
			if ($interval->days < 180) {
				return false;
			} else {
				return true;
			}			
		} else {
			return false;
		}
	}
	
	public function getIscompleteAttribute() {
		switch ($this->companytype_id) {
			case Companytype::BUYER_TYPE:
				return $this->basicinfo && $this->authsignatory && $this->shareholders && $this->beneficialowners && $this->directors && $this->business;
				
			case Companytype::SUPPLIER_TYPE:
				return $this->basicinfo && $this->authsignatory && $this->banks && $this->business;
			
			case Companytype::BOTH_TYPE:
				return $this->basicinfo && $this->authsignatory && $this->shareholders && $this->beneficialowners && $this->directors && $this->banks && $this->business;
			
			case Companytype::FF_TYPE:
				return $this->basicinfo && $this->authsignatory && $this->banks && $this->business;
		}
	}
	
	public function getCreditposAttribute() {
		$purchaseorders = Purchaseorder::where('company_id', $this->id)->whereIn('status_id', [4, 7, 15])->get();
		return $purchaseorders;
	}
	
	public function getActiveappointmentAttribute() {
		$date=date_create(date('Ymd'));
		$from = date_format($date,"Y-m-d");
		$appointments = Appointment::where('company_id', $this->id)
		->whereIn('status_id', [1,8])->where('date', '>=', $from)->get();
		if ($appointments->count() == 0) {
			return null;
		} else {
			return $appointments->first();
		}		
	}

	public function isCustomer() {
		return $this->companytype_id == 3 || $this->companytype_id == 1; // Both or customer
	}

	public function isVendor() {
		return $this->companytype_id == 3 || $this->companytype_id == 2; // Both or vendor
	}
	
	public function isForwarder() {
		return $this->companytype_id == 4; // Forwarder
	}
	
	public function getSortedAddresses() {
		$sortedAddresses = $this->shippingaddresses()->orderBy('default', 'DESC')->get();
		return $sortedAddresses;
	}
	
	public function getSortedPickupAddresses() {
		$sortedAddresses = $this->pickupaddresses()->orderBy('default', 'DESC')->get();
		return $sortedAddresses;
	}
	
	public function canCreateCreditRequest()
	{
		$error = null;
		if (!$this->active)
			$error = "Company is not active";
		elseif (!$this->confirmed)
			$error = "Company is not confirmed";
		elseif (!$this->customer_signed)
			$error = "Company's contract is not signed.";

		return [
			"canCreate" => $error ? false : true,
			"error" => $error
		];
	}

	public function canIncreaseCreditRequest()
	{
		$pendingCreditRequests = Creditrequest::where('company_id', $this->id)->whereIn('creditstatus_id', [2, 4, 5, 6])->get();
		$approvedCreditrequest = Creditrequest::where(['company_id' => $this->id,'creditstatus_id' => 1])->latest()->first();
		if(isset($approvedCreditrequest)) {
			$approvingDate = date_create($approvedCreditrequest->approved_on);
			$today = new \DateTime();
			$interval = date_diff($approvingDate, $today)->format("%a");
		}

		$error = null;
		if (!$this->active)
			$error = "Company is not active";
		elseif (!$this->confirmed)
			$error = "Company is not confirmed";
		elseif (!$this->customer_signed)
			$error = "Company's contract is not signed.";
		elseif ($pendingCreditRequests->count() > 0)
			$error = "There's a pending credit request for this company.";
		elseif ($this->creditlimit <= 0)
			$error = "The buyer does not have a credit limit";
		elseif (!$approvedCreditrequest)
			$error = "You don't have a credit to increase";
		elseif ($interval < 180)
			//$error = "Last credit limit was changed less than six months ago";

		return [
			"canIncrease" => $error ? false : true,
			"error" => $error
		];
	}
	
	public function deregister($id, $token)
	{
		session(['id' => '', 'token' => '']);
		$actiontokens = Actiontoken::where('id', $id)->where('token', $token)->get();
		if ($actiontokens->count() == 0) {
			return view('message',[
				'title' => 'Invalid token',
				'message' => 'Cannot deregister. Supplied token is invalid.',
				'error' => true,
				'home_link' => 'true'
			]);
		}
		if ($actiontokens->first()->expiry < date('Y-m-d H:i:s')) {
			return view('message',[
				'title' => 'Expired token',
				'message' => 'Cannot deregister. Supplied token has expired.',
				'error' => true,
				'home_link' => 'true'
			]);
		}
		$this->active = 0;
		$this->confirmed = 0;
		$this->customer_signed = 0;
		$this->vendor_signed = 0;
		$this->save();
		DB::table('attachments')
			->where('attachable_type', 'company')
			->where('attachable_id', $this->id)
			->where('attachmenttype_id', Attachment::SUPPLIER_CONTRACT)
			->update(['attachmenttype_id' => Attachment::SUPPLIER_OLDCONTRACT]);
		DB::table('attachments')
			->where('attachable_type', 'company')
			->where('attachable_id', $this->id)
			->where('attachmenttype_id', Attachment::BUYER_CONTRACT)
			->update(['attachmenttype_id' => Attachment::BUYER_OLDCONTRACT]);
		$actiontokens->first()->delete();		
		return view('message',[
			'title' => 'Company deregistered',
			'message' => 'Company deregistered. Click home to continue',
			'error' => false,
			'home_link' => 'true'
		]);
	}

	public function getCompanyUsers () {
		return \App\User::where('tenant_id',$this->tenant_id)->pluck('id');
	}
}
