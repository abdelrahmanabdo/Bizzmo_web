<?php

namespace App;
use \App\Productattribute ;
use Illuminate\Database\Eloquent\Model;

class Productattributevalues extends Model
{
    // public function product()
    // {
    //     return $this->belongsTo('App\Product');
    // }

    public function attribute_id ($attribute) {
        return Productattribute::where('attribute','like','%'.$attribute.'%')->value('id');
    }
	
}
