<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditrequestbalancesheet extends Model
{
	protected $guarded = array();
	protected $table = 'balancesheets';
	
    public function creditrequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
	
	public function balancesheetitem()
    {
        return $this->belongsTo('App\Balancesheetitem');
    }
}
