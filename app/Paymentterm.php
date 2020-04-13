<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentterm extends Model
{
	
	public function companies()
    {
        return $this->belongsToMany('App\Company')->withPivot('buyup')->withTimestamps();
    }
	
}
