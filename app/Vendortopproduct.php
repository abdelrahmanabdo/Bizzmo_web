<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendortopproduct extends Model
{
	protected $guarded = array();
	protected $table = 'companytopproducts';
	
    public function vendor()
	{
		return $this->belongsTo('App\Vendor');
	}
	
	public function revenue()
    {
        return $this->belongsTo('App\Range', 'topproductrevenue');
    }
	
}
