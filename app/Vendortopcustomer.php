<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendortopcustomer extends Model
{
	protected $guarded = array();
	protected $table = 'companytopcustomers';
	
    public function vendor()
	{
		return $this->belongsTo('App\Vendor');
	}
}
