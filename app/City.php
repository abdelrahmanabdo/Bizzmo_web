<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function company()
    {
        return $this->hasMany('App\Company');
    }
	
	public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
