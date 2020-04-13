<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productattribute extends Model
{
    protected $fillable = ['attribute' , 'attribute_type', 'system' ,'active'];
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
	
}
