<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
// use Faker\Provider\hr_HR\Company;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'isAdmin', 'active', 'verified', 'email_token', 'created_by', 'updated_by', 'title'
    ];

	protected $appends = array('companies');
	
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function roles()
	{
		return $this->belongsToMany('App\Role')->withTimestamps();
	}
	
	public function verified()
	{
		$this->verified = 1;
		$this->active = 1;
		$this->email_token = null;
		$this->save();
	}

	public function getCompaniesAttribute() {
		
		$roles = $this->roles->pluck('id');
		$companys = Company::whereHas('roles', function ($q) use($roles) {
				$q->whereIn('roles.id', $roles);
		})->get();
		return $companys;
	}
	
	public function permissions($company_id = 0)
	{
		if ($company_id == 0) {
			$roles = $this->roles()->get()->pluck('id');
		} else {			
			$roles = $this->roles()->where('company_id', $company_id)->get()->pluck('id');			
		}
		//DB::enableQueryLog();
		$permissions = Permission::whereHas('roles', function ($query) use ($roles) {
			$query->whereIn('permission_role.role_id', $roles);
		})->get();
		//var_dump( DB::getQueryLog());
		//var_dump($permissions->count());
		//die;
		return $permissions;
	}
	
	 public function chats()
	{
		return $this->belongsToMany('App\Chat')->withPivot('unread')->withTimestamps();
	}
	
	//takes an array of permission names, return the companys that the user have with any of those permissions
	public function companypermissions($permissions)
	{
		$roles = Role::whereHas('permissions', function ($qq) use($permissions)
		{
			$qq->whereIn('name', $permissions);
		})->whereHas('users', function($q)
			{
				$q->where('user_id', $this->id);
			})
		->pluck('id');
		//DB::enableQueryLog();
		$companys = Company::whereHas('roles', function ($q) use ($roles) {
			$q->whereIn('id', $roles);
		})->orderBy('companyname', 'asc')->get();
		//var_dump( DB::getQueryLog());
		return $companys;
	}
	
	public function pendingcustomerappointments() {
		$date=date_create(date('Ymd'));
		$from = date_format($date,"Y-m-d");
		$companies = $this->companypermissions(['cr_vw', 'cr_ch']);
		$appointments = Appointment::where('date', '>=', $from)->whereIn('status_id', [1, 8])->whereIn('company_id', $companies->pluck('id'))->get();		
		return $appointments;
	}
	
	public function quotationsPendingVendor() {
		$vendors = $this->companypermissions(['vp_ap']);
		return $this->quotationsQuery('vendor_id', $vendors, [Status::QU_PENDING_SUPPLIER_SUBMITTAL, Status::QU_PENDING_BUYER_APPROVAL]);
	}

	public function quotationsPendingCustomer() {
		$buyers = $this->companypermissions(['cq_vw', 'cq_ch']);
		return $this->quotationsQuery('company_id', $buyers, [Status::QU_PENDING_BUYER_APPROVAL]);
	}
	
	private function quotationsQuery($column, $companies, $statuses) {
		return Quotation::with('quotationitems', 'company', 'vendor', 'status', 'attachments')
			->whereIn($column, $companies->pluck('id'))->whereIn('status_id', $statuses)
			->orderBy('id', 'desc')->get();
	}
	
	public function pospendingvendor($limit = null) {
		$vendors = $this->companypermissions(['vp_ap']);
		
		if($limit)
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('vendor_id', $vendors->pluck('id'))->whereIn('status_id', [7,15])->orderBy('id', 'desc')->take($limit)->get();
		else
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('vendor_id', $vendors->pluck('id'))->whereIn('status_id', [7,15])->get();
		
		return $purchaseorders;
	}
	
	public function pospendingcustomer($limit = null) {
		$companies = $this->companypermissions(['po_vw', 'po_ch']);
		
		if($limit)
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->whereIn('status_id', [4,7,13,15])->orderBy('id', 'desc')->take($limit)->get();
		else
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->whereIn('status_id', [4,7,13,15])->orderBy('id', 'desc')->get();
		
		return $purchaseorders;
	}
	
	public function quotationsPending($limit = null) {
		$roles = Auth::User()->roles;
		$companyzero = $roles->where('company_id', '0');
		if ($companyzero->count() > 0) {
			$companies = Company::orderBy('companyname', 'asc');
			$buyers = $companies->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			$suppliers = $companies->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
		} else {
			$companies = Auth::user()->companypermissions(['qu_cr', 'qu_ch', 'cq_vw', 'cq_ch']);
			$buyers = $companies->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
			$suppliers = $companies->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
		}
		$query = Quotation::orderBy('number', 'asc');


		$query = $query->where(function ($q) use ($buyers, $suppliers) {
			if ($buyers->count() > 0 && $suppliers->count() > 0) {
				$q->whereIn('company_id', $buyers->pluck('id'))
					->orWhereIn('vendor_id', $suppliers->pluck('id'));
			} elseif ($buyers->count() > 0) {
				$q->whereIn('company_id', $buyers->pluck('id'));
			} elseif ($suppliers->count() > 0) {
				$q->whereIn('vendor_id', $suppliers->pluck('id'));
			}
		});

		$query = $query->whereIn('status_id', [23, 24]);

		return $query->get();
	}

	public function posPending($limit = null) {
		$companies = $this->companypermissions(['po_vw', 'po_ch']);
		$vendors = $this->companypermissions(['vp_ap']);
		
		if($limit)
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->orWhereIn('vendor_id', $vendors->pluck('id'))->whereIn('status_id', [4, 7, 13])->orderBy('id', 'desc')->take($limit)->get();
		else
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->orWhereIn('vendor_id', $vendors->pluck('id'))->whereIn('status_id', [4, 7, 13])->get();
		
		return $purchaseorders;
	}

	public function getAllCustomerPOs($limit = null) {
		$companies = $this->companypermissions(['po_vw', 'po_ch']);
		
		if($limit)
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->orderBy('id', 'desc')->take($limit)->get();
		else
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->get();
		
		return $purchaseorders;
	}

	public function getAllVendorPOs($limit = null) {
		$vendors = $this->companypermissions(['vp_ap']);
		
		if($limit)
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('vendor_id', $vendors->pluck('id'))->orderBy('id', 'desc')->take($limit)->get();
		else
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('vendor_id', $vendors->pluck('id'))->get();
		
		return $purchaseorders;
	}

	public function getAllPOs($limit = null) {
		$companies = $this->companypermissions(['po_vw', 'po_ch']);
		$vendors = $this->companypermissions(['vp_ap']);
		
		if($limit)
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->orWhereIn('vendor_id', $vendors->pluck('id'))->orderBy('id', 'desc')->take($limit)->get();
		else
			$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->whereIn('company_id', $companies->pluck('id'))->orWhereIn('vendor_id', $vendors->pluck('id'))->get();
		
		return $purchaseorders;
	}
	
	public function pospendingcredit($includePendingSuppApproval = false) {
		$companies = $this->companypermissions(['po_rc']);
		$purchaseorders = Purchaseorder::with('purchaseorderitems', 'company', 'vendor', 'status', 'attachments')->orderBy('id', 'desc');
		if($includePendingSuppApproval)
			return $purchaseorders->whereIn('status_id', [4,7])->get();
		else
			return $purchaseorders->where('status_id', 4)->get();
	}
	public function creditRequestPendingCustomerInfo() {
		$companies = $this->companypermissions(['cr_cr', 'cr_ch', 'cr_vw']);
		$creditrequests =$this->creditRequestQuery([Creditstatus::PENDING_CREDIT_DECISION])->whereIn('company_id', $companies->pluck('id'))->get();
		return $creditrequests;
	}

	public function creditrequestpendingcustomer() {
		$companies = $this->companypermissions(['cr_cr', 'cr_ch', 'cr_vw']);
		$statuses = [Creditstatus::PENDING_RECEIPT_OF_SECURITIES, Creditstatus::CONDITIONAL_APPROVAL, Creditstatus::SCHEDULE_SITE_VISIT];
		$creditrequests = $this->creditRequestQuery($statuses)->whereIn('company_id', $companies->pluck('id'))->get();
		return $creditrequests;
	}

	private function creditRequestQuery($requestedStatuses) {
		return Creditrequest::whereIn('creditstatus_id', $requestedStatuses);
	}
	public function phone() {
		return Phone::where('user_id', $this->id)->get()->last();
	}

	public function sendPasswordResetNotification($token)
	{
		Mail::send('emails.reset-password', ['token' => $token], function($message) {
			$message->subject('Reset Password');
			$message->to($this->email);
		});
	}

	// Get reported issues for a user
	public function getIssues() {
		$userIds = [$this->id, $this->tenant_id];

		// Get users created by current one
		$referencedUsers = User::where('tenant_id', $this->id)->pluck('id')->toArray();
		if(!empty($referencedUsers))
			$userIds = array_unique(array_merge($referencedUsers, $userIds));

		$uniqueUserIds = array_unique($userIds); // Remove duplicates

		return Support::whereIn('created_by', $uniqueUserIds)->get();
	}

	public function hasReadyBuyerCompany() {
		//return $this->buyerCompanyQuery()->where(['confirmed' => 1,'active' => 1, 'customer_signed' => 1])->count() > 0;
		return $this->buyerCompanyQuery()->where(['confirmed' => 1, 'customer_signed' => 1])->count() > 0;
	}

	public function hasBuyerCompany() {
		return $this->buyerCompanyQuery()->count() > 0;
	}

	public function getBuyerCompany() {
		return $this->buyerCompanyQuery()->first();
	}

	private function buyerCompanyQuery() {
		$roles = $this->roles->pluck('id');

		$query = Company::whereHas('roles', function ($q) use($roles) {
			$q->whereIn('roles.id', $roles);
		})->whereIn('companytype_id', [Companytype::BUYER_TYPE, Companytype::BOTH_TYPE]);
		return $query;
	}
	
	public function hasReadySupplierCompany() {
		//return $this->supplierCompanyQuery()->where(['confirmed' => 1,'active' => 1, 'vendor_signed' => 1])->count() > 0;
		return $this->supplierCompanyQuery()->where(['confirmed' => 1, 'vendor_signed' => 1])->count() > 0;
	}

	public function hasSupplierCompany() {
		return $this->supplierCompanyQuery()->count() > 0;
	}

	public function getSupplierCompany() {
		return $this->supplierCompanyQuery()->first();
	}

	private function supplierCompanyQuery() {
		$roles = $this->roles->pluck('id');

		$query = Company::whereHas('roles', function ($q) use($roles) {
			$q->whereIn('roles.id', $roles);
		})->whereIn('companytype_id', [Companytype::SUPPLIER_TYPE, Companytype::BOTH_TYPE]);
		return $query;
	}

	//Get company_id 
	public function getCompanyId () {
		return  Company::whereTenantId($this->id)->value('id');
	}

	//Get company_id 
	public function getCompanyName () {
		return  Company::whereTenantId($this->id)->value('companyname');
	}

	//Check if user has a Company profile
	public function hasProfile(){
		return CompanyProfile::whereCompanyId($this->getCompanyId())->exists();
	}
	//Get Company Profile
	public function getProfile () {
		return  CompanyProfile::whereCompanyId($this->getCompanyId())->with('getCountry','getCity')->first();
	}

	//Get company industries
	public function getCompanyIndustries(){
		if(Company::find($this->getCompanyId())){
			return Company::find($this->getCompanyId())->industries()->get();
		}else {
			return [];
		}
	}



}
