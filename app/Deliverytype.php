<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliverytype extends Model
{
	
	public function companies()
    {
        return $this->belongsToMany('App\Company')->withTimestamps();
    }
	
}
