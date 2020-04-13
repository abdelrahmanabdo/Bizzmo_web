<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = "inquiry";
    protected $fillable = ['product_id','supplier_id','buyer_id','qty','price','discount','discount_value','status'];
    
    public function product (){
        return $this->belongsTo('\App\Product','product_id');
    }

    public function supplier () {
        return $this->belongsTo('\App\Company','supplier_id');

    }
}
