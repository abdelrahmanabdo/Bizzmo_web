<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	protected $table = 'companies';
	
	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
	
	public function employeenumber()
    {
        return $this->belongsTo('App\Range', 'employees');
    }
	
	public function vendortopproducts()
    {
        return $this->hasMany('App\Vendortopproduct', 'owner_company');
    }
	
	public function vendortopcustomers()
    {
        return $this->hasMany('App\Vendortopcustomer', 'owner_company');
    }
	
	public function deliverytypes()
    {
        return $this->belongsToMany('App\Deliverytype', 'company_deliverytype', 'company_id', 'deliverytype_id');
    }
	
	public function roles()
	{
		return $this->hasMany('App\Role', 'owner_company');
	}
	
	public function companytype()
    {
        return $this->belongsTo('App\Companytype');
    }
	
	public function country()
    {
        return $this->belongsTo('App\Country');
    }
	
	public function city()
    {
        return $this->belongsTo('App\City');
    }
	
	public function companies()
	{
		return $this->belongsToMany('App\Company', 'company_vendor', 'owner_company', 'favourite_company')->withTimestamps();
	}
	
	public function pickupaddresses()
    {
        return $this->hasMany('App\Pickupaddress', 'company_id');
    }
	
	public function getSortedPickupAddresses() {
		$sortedAddresses = $this->pickupaddresses()->orderBy('default', 'DESC')->get();
		return $sortedAddresses;
	}
}
