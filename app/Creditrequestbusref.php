<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditrequestbusref extends Model
{
	protected $guarded = array();
	
    public function creditrequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
	
	public function yearsnum()
    {
        return $this->belongsTo('App\Range', 'busreflength');
    }
}
