<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Port_code extends Model
{
    //

    public function country()
    {
        return $this->belongsTo('App\Country', 'CountryCode', 'isocode');
    }
}
