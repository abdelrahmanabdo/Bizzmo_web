<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditrequestbankstatement extends Model
{
	protected $guarded = array();
	protected $table = 'bankstatements';
	
    public function creditrequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
}
