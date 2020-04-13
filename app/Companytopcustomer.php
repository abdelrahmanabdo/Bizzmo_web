<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Companytopcustomer extends Model implements AuditableContract
{
	use Auditable;
	protected $guarded = array();
	
    public function company()
	{
		return $this->belongsTo('App\Company');
	}
	
	public function country()
    {
        return $this->belongsTo('App\Country');
    }
	
	public function buyertype()
    {
        return $this->belongsTo('App\Buyertype');
    }
}
