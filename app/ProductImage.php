<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'productImages';
    protected $fillable = ['product_id' , 'image'];

    public function product(){
        return $this->belongsTo('\App\Product');
    }
}
