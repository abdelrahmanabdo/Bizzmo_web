<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialgroup extends Model
{
	public function purchaseorderitem()
    {
        return $this->hasMany('App\Purchaseorderitem');
    }
}
