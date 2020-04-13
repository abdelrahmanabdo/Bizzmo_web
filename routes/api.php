<?php

use Illuminate\Http\Request;

use App\Country;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

 Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/companies', 'companycontroller@search');
Route::middleware('auth:api')->post('/companies/create', 'companycontroller@save');
Route::middleware('auth:api')->get('/companies/view/{id}', 'companycontroller@view');

Route::middleware('auth:api')->get('/countries', function () {
    return Country::with('cities')->get();
});

Route::post('apiregister', 'Auth\RegisterController@apiregister');

//Post a new purchase order 
Route::post('purchaseorders/add',
              'Api\PurchaseOrderController@add_purchaseorder');

Route::get('purchaseorders/view/{id}', 'purchaseordercontroller@save');

//Route::middleware('auth:api')->get('products', 'productcontroller@search');
Route::get('products', 'productcontroller@list');

// Signature routes
Route::prefix('signature')->group(function () {

    // Adobe Sign Route
    Route::prefix('adobe-sign')->group(function () {
        Route::get('contract', 'AdobeSignController@contract');
    });

    // Right Signature Routes
    Route::prefix('right-signature')->group(function () {
        Route::prefix('document')->group(function () {
            Route::post('update', 'SignatureController@rightSignatureDocumentUpdate');
        });
        Route::prefix('securites-document')->group(function () {
            Route::post('update', 'SignatureController@rightSignatureSecuritiesDocumentUpdate');
        });
        Route::prefix('request')->group(function () {
            Route::post('update', 'SignatureController@rightSignatureRequestUpdate');
        });
    });

      // DocuSign Routes
      Route::prefix('docu-sign')->group(function () {
        Route::post('contract-update', 'SignatureController@docuSignContractUpdate');
        Route::post('security-update', 'SignatureController@docuSignSecurityUpdate');
        Route::post('delivery-update', 'SignatureController@docuSignDeliveryUpdate');             
    });
});

Route::post('tookan','SignatureController@tookanWebhook');
