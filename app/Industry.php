<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    public function companies()
    {
		return $this->belongsToMany('App\Company')->withTimestamps();
    }
}
