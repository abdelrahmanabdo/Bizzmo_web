<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pickupaddress extends Model
{
	protected $guarded = array();
	
	public function purchaseorder()
    {
        return $this->belongsTo('App\Purchaseorder');
    }
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function city()
    {
        return $this->belongsTo('App\City');
    }
	
}
