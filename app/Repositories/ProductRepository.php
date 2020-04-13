<?php

namespace App\Repositories;

use Auth;
use \App\Product;
use \App\ProductImage as Images;
use \App\Productattributevalues as Attributes;
use \App\Productattribute ;
use Illuminate\Support\Str;
use App\Traits\UploadTrait;

/**
 * Product Repository for adding new product
 */
class ProductRepository
{ 
	use UploadTrait;

    // Create new product
	public function create(array $data , array $files ) {
        //Product

        $product = new Product();
        //Add new product to product table
        $product->name        = $data['product_name'];
        $product->category_id = $data['product_category'];
        $product->subCategory_id = $data['product_subCategory'] ?? null;
        $product->description = $data['product_description'];
        $product->condition   = $data['product_status'];
        $product->price       = $data['product_price'];
        $product->currency_id = $data['product_currency'];
        $product->offer        = $data['product_price_offer'];
        $product->company_id  = \Auth::user()->getCompanyId() ;
        $product->created_by  = Auth::user()->id; 
        $product->updated_by  = Auth::user()->id; 

        $product->active      = 1;
        $product->save();

        //Get new product id
        $product_id = $product->id;
        
        //Add product Images
        foreach($files as $image){
            $name = Str::slug($data['product_name']).'_'.time();
            $folder = 'images/product/' . date('Y') . '/' . date('m').'/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);            
            $productImages = new Images();
            $productImages->product_id = $product_id;
            $productImages->image = $filePath;
            $productImages->save();
       }    

        //Add product Attributes
        foreach($data as $attribute=>$value){
            if(str_contains($attribute , 'attribute_')){
                $attribute = ltrim($attribute, 'attribute_');
                //Check if new attribute added by user
                if(!$value == null){
                    $checkNewAttribute = Productattribute::where('attribute','like','%'.$attribute.'%')->exists();
                    if(!$checkNewAttribute ){
                        //create new attribute
                        $newAttribute = new Productattribute();
                        $newAttribute->attribute = $attribute;
                        $newAttribute->attribute_type = 'text';
                        $newAttribute->system = 0;
                        $newAttribute->active = 1;
                        $newAttribute->save();
                    }
                    //insert product's attributes 
                    $productAttribute = new Attributes();
                    $productAttribute->product_id = $product_id;
                  
                    $productAttribute->productattribute_id = $productAttribute->attribute_id($attribute) ;
                    if($attribute == 'weight'){
                        $productAttribute->value = $value .' ' .$data['weight_unit'];
                    }elseif($attribute == 'volume'){
                        $productAttribute->value = $value .' ' .$data['volume_unit'];
                    }else if ($attribute == 'warranty'){
                        $productAttribute->value = $value .' ' .$data['warranty_period'];
                    }else {
                        $productAttribute->value = $value;
                    }
                    $productAttribute->save();
                }
  
            }
        }

        return $product->with(['attributes','images'])->get()->last() ;
	}
    
 
    // Create new product
	public function update(array $data , array $files ) {
        //Product
        $product =  \App\Product::find($data['id']);
        //Add new product to product table
        $product->name        = $data['product_name'];
        $product->category_id = $data['product_category'];
        $product->subCategory_id = $data['product_subCategory'] ?? null;
        $product->description = $data['product_description'];
        $product->condition   = $data['product_status'];
        $product->price       = $data['product_price'];
        $product->currency_id = $data['product_currency'];
        $product->offer        = $data['product_price_offer'];
        $product->update();

 
        //Add product Images
        foreach($files as $image){
            $name = Str::slug($data['product_name']).'_'.time();
            $folder = 'images/product/' . date('Y') . '/' . date('m').'/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);      
            $productImages = new Images();
            $productImages->product_id = $product->id;
            $productImages->image = $filePath;
            $productImages->save();
       }

        //Add product Attributes
        $product->attributes()->detach();
        foreach($data as $attribute=>$value){
            if(str_contains($attribute , 'attribute_')){
                $attribute = ltrim($attribute, 'attribute_');
                //Check if new attribute added by user
                $checkNewAttribute = Productattribute::whereAttribute($attribute)->exists();
                if(!$checkNewAttribute ){
                    //create new attribute
                    $newAttribute = new Productattribute();
                    $newAttribute->attribute = $attribute;
                    $newAttribute->attribute_type = 'text';
                    $newAttribute->system = 0;
                    $newAttribute->active = 1;
                    $newAttribute->save();
                }    
                //insert product's attributes 
                $productAttribute = new Attributes();
                $productAttribute->product_id = $product->id;
                $productAttribute->productattribute_id = $productAttribute->attribute_id($attribute) ;
                if($attribute == 'weight' && $attribute != null){
                    $productAttribute->value = $value .' ' .$data['weight_unit'];
                }elseif($attribute == 'volume' && $attribute != null){
                    $productAttribute->value = $value .' ' .$data['volume_unit'];
                }else if ($attribute == 'warranty' && $attribute != null){
                    $productAttribute->value = $value .' ' .$data['warranty_period'];
                }else {
                    $productAttribute->value = $value ?? '';
                }
                
                $productAttribute->save();
            }
        }
        
        return $product->with(['attributes','images'])->find($data['id']) ;
	}
	
}