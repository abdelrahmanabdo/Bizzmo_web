<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function company()
    {
        return $this->hasMany('App\Company');
    }
	
	public function cities()
    {
        return $this->hasMany('App\City');
    }
}
