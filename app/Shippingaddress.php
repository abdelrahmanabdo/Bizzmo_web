<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shippingaddress extends Model
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
	
	public function deliverycity()
    {
        return $this->belongsTo('App\City', 'delivery_city_id');
    }
	
	public function incoterm()
    {
        return $this->belongsTo('App\Incoterm');
    }
}
