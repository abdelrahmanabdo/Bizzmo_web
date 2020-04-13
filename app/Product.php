<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	
	//protected $guarded = [];
	
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function productcondition()
    {
        return $this->belongsTo('App\Status' , 'condition');
    }

    public function productcategory()
    {
        return $this->belongsTo('App\Productcategory', 'category_id');
    }
    
    

	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
	
    public function attributes()
    {
        return $this->belongsToMany('App\Productattribute', 'productattributevalues')->where('system','1')->withPivot('value')->withTimestamps();
    }

    public function customAttributes()
    {
        return $this->belongsToMany('App\Productattribute', 'productattributevalues')->where('system','0')->withPivot('value')->withTimestamps();
    }

    public function attributeValues(){
        return $this->hasMany('App\Productattributevalues', 'productattributevalues')->withPivot('value')->withTimestamps();

    }
	public function getCondition() {
        return Status::where('statustype', 'productcondition')->where('active', 1)->where('id', $this->condition)->first()['name'];
    }

    public function getCurrency(){
        return \App\Currency::whereId($this->currency_id)->value('abbreviation');
    }
    
    public function images(){
        return $this->hasMany('\App\ProductImage');
    }

}
