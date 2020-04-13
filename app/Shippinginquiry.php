<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shippinginquiry extends Model
{
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function purchaseorder()
    {
        return $this->belongsTo('App\Purchaseorder');
    }
}
