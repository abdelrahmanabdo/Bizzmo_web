<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Companytopproduct extends Model implements AuditableContract
{
	use Auditable;
	protected $guarded = array();
	
    public function company()
	{
		return $this->belongsTo('App\Company');
	}
	
	public function revenue()
    {
        return $this->belongsTo('App\Range', 'topproductrevenue');
    }
	
	public function brand()
    {
        return $this->belongsTo('App\Brand', 'topproductname');
    }
}
