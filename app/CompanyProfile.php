<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $table = "company_profile";
    protected $fillable = ['company_id' , 'address' , 'pobox' , 'country' , 'city' , 'tel','fax' , 'employees_number', 'customers_number' , 'email', 'logo' , 'cover' , 'overview'];

    public function company()
	{
		return $this->belongsTo('App\Company');
    }
    
    public function getCountry()
    {
        return $this->belongsTo('App\Country' ,'country');
    }

    public function getCity()
    {
        return $this->belongsTo('App\City','city');
    }
}
