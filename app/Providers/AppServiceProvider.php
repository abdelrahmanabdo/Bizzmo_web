<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; //to stop an error when creating unique in mysql. sherif
use Illuminate\Support\Facades\Validator; //for the customer validator. sherif
use Illuminate\Database\Eloquent\Relations\Relation; //for the polymorphic relations

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //to stop an error when creating unique in mysql. sherif
		Schema::defaultStringLength(191);
		
		Validator::extend('totalshare', function ($attribute, $value, $parameters, $validator) {
			if (array_sum($value) <= 100) {
				return true;
			} else {
				return false;
			}
		});
		
		Relation::morphMap([
			'purchaseorder' => 'App\Purchaseorder',
			'purchaseorderitem' => 'App\Purchaseorderitem',
			'quotation' => 'App\Quotation',
			'quotationitem' => 'App\Quotationitem',
			'company' => 'App\Company',
			'vendor' => 'App\Vendor',
			'companyowner' => 'App\Companyowner',
			'companybeneficial' => 'App\Companybeneficial',
			'companydirector' => 'App\Companydirector',
			'creditrequest' => 'App\Creditrequest',		
			'creditrequestsecurity' => 'App\Creditrequestsecurity',
			'product' => 'App\Product',
			'actiontoken' => 'App\Actiontoken',
			'companytopcustomer' => 'App\Companytopcustomer',
			'companytopproduct' => 'App\Companytopproduct',
			'user' => 'App\User'
		]);
		
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
