<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'homecontroller@index');
Route::get('/home', 'homecontroller@index');
Route::get('test', 'testcontroller@test');
Route::get('test3', 'testcontroller@dhltest');
Route::get('test2', 'testcontroller@test2');
Route::post('test', 'modulecontroller@permissions');
Route::get('test1', 'testcontroller@teststart');
Route::post('test1', 'testcontroller@test1');
Route::get('form', 'testcontroller@abc');
Route::post('form', 'companycontroller@SuppliersList');
Route::get('searchsuppliers', 'companycontroller@searchSuppliers');
Route::get('tabform', 'testcontroller@tabform');
Route::get('design', 'testcontroller@design_test');

Route::post('form', 'testcontroller@abc');
Route::post('json', 'testcontroller@json');


Route::get('demos/infinite-scroll','testcontroller@viewInfiniteScroll');
Route::post('demos/infinite-scroll','testcontroller@getInfiniteScroll');

Route::post('sign', function (Request $request) {
	Storage::disk('local')->append('json.txt', 'POST');
	$a = $request->input('id');
	Storage::disk('local')->append('json.txt', $a);
});

Route::get('how-it-works', function () { return view('home.how-it-works'); })->middleware('guest');

Route::get('test-broadcast', function(){
    broadcast(new \App\Events\ExampleEvent);
});
Route::get('/list/companies' , 'companycontroller@get_companies_list');

//companies
Route::prefix('companies')->middleware('auth')->group(function () {
	Route::post('companyshipaddr', 'companycontroller@companyshipaddr');
	Route::post('companypickupaddr', 'companycontroller@companypickupaddr');
	Route::get('select2', 'companycontroller@dataAjax');
	Route::get('search-buyers', 'companycontroller@searchBuyers');
	Route::get('select', 'companycontroller@select');
	Route::post('select', 'companycontroller@companytype');
	Route::post('suppliers', 'companycontroller@searchSuppliers');
	Route::get('activate/{id}', 'companycontroller@activate')->middleware('can:cr_ap');
	Route::get('products', 'productcontroller@view');
	Route::get('productadd', 'productcontroller@productadd')->name('addProduct');
	Route::post('productadd', 'productcontroller@create');

	//Abdelrahman product endpoints

	//post add product
	Route::post('product/add_product', 'productcontroller@post_product_data')->name('postProductData');

	//Edit product
	Route::get('product/edit/{product_id}', 'productcontroller@show_edit_product')->name('showEditProduct');
	Route::post('product/edit_product', 'productcontroller@edit_product_data_posting')->name('updateProductDataPosting');

	//Display products
	Route::get('product/category/products/{category_id}','productcontroller@get_category_products')->name('showCategoryProducts');
	Route::get('product/get_subCategory/{category_id}' , 'productcontroller@get_subCategory');
	Route::get('/product/get_subCategory/{subcategory_id}/products' , 'productcontroller@get_subCategories_products');
	Route::get('/product/{product_id}' , 'productcontroller@get_product_details')->name('displayProductDetails');

	Route::get('searchsuppliers', 'companycontroller@searchSuppliers');
	Route::get('productedit/{id}', 'productcontroller@productedit');
	Route::post('productedit/{id}', 'productcontroller@productsave');
	Route::get('productdelete/{id}', 'productcontroller@productdelete');
	Route::get('deregisterrequest/{id}', 'companycontroller@deregisterrequest')->middleware('can:cr_ap');																									   
	Route::get('deactivate/{id}', 'companycontroller@deactivate')->middleware('can:cr_ap');
	Route::get('mysuppliers/{id?}', 'companycontroller@mysuppliers')->middleware('can:co_ch');
	Route::post('mysuppliers/{id?}', 'companycontroller@mysupplierssave');
	Route::post('savesuppliersajax', 'companycontroller@savesuppliersajax');
	Route::get('mybuyers/{id?}', 'companycontroller@mybuyers')->middleware('can:co_ch');
	Route::post('mybuyers/{id?}', 'companycontroller@myBuyersSave');
	Route::post('savebuyersajax', 'companycontroller@savebuyersajax');
	Route::get('create', 'companycontroller@manage')->middleware('can:co_cr');
	Route::post('create', 'companycontroller@save');
	Route::post('getbuyup', 'companycontroller@getbuyup');
	Route::post('get-vat', 'companycontroller@getVAT');
	Route::post('roles', 'companycontroller@roles');
	Route::get('paymenttermsview/{companyid}', 'companycontroller@paymenttermsview')->middleware('can:pt_vw');
	Route::get('paymentterms/{companyid}', 'companycontroller@paymentterms')->middleware('can:pt_as');
	Route::post('paymentterms/{companyid}', 'companycontroller@savepaymentterms');
	Route::get('supplierpaymenttermsview/{companyid}', 'companycontroller@supplierpaymenttermsview')->middleware('can:pt_vw');
	Route::get('supplierpaymentterms/{companyid}', 'companycontroller@supplierpaymentterms')->middleware('can:pt_as');
	Route::post('supplierpaymentterms/{companyid}', 'companycontroller@savesupplierpaymentterms');
	
	Route::get('supplierdeliveryview/{companyid}', 'companycontroller@supplierdeliveryview')->middleware('can:pt_as');
	Route::get('supplierdelivery/{companyid}', 'companycontroller@supplierdelivery')->middleware('can:pt_as');
	Route::post('supplierdelivery/{companyid}', 'companycontroller@savesupplierdelivery');
	
	Route::get('unconfirmed', 'companycontroller@searchunconfirmed')->middleware('can:co_cr');
	Route::get('view/{id}', 'companycontroller@view')->middleware('can:co_vw,id')->name('companies.view');
	Route::get('profile/{id}', 'companycontroller@profile_view')->middleware('can:co_vw,id')->name('companies.profile.view');
	Route::post('profile/{id}', 'companycontroller@profile_edit')->middleware('can:co_vw,id')->name('companies.profile.edit');

	Route::post('view/{id}', 'companycontroller@confirm')->middleware('can:co_co,id');		
	Route::get('attach/{id}', 'companycontroller@attachstart')->middleware('can:co_at,id');
	
	Route::post('attach/{id}', 'companycontroller@attach');
	Route::get('attachment/{id}', 'companycontroller@attachment')->middleware('can:co_va,id');	
	
	Route::get('{id}/{tab?}', 'companycontroller@manage')->middleware('can:co_ch,id');
	Route::post('{id}/{tab?}', 'companycontroller@save');
	Route::get('', 'companycontroller@searchstart')->middleware('can:co_sc');
	Route::post('', 'companycontroller@search');
});

Route::prefix('company')->middleware('auth')->group(function () {
	Route::get('edit/{id}/{tab?}', 'companycontroller@manage')->middleware('can:cr_ap');
	Route::post('edit/{id}/{tab?}', 'companycontroller@save')->middleware('can:cr_ap');
	
	Route::get('changes/{id}', 'companycontroller@changes')->middleware('can:cr_ap');
	
	Route::get('{id}/{tab?}', 'companycontroller@manage')->middleware('can:co_ch,id');
	Route::post('{id}/{tab?}', 'companycontroller@save');

});
Route::prefix('forwarder')->middleware('auth')->group(function () {
	Route::prefix('route')->middleware('auth')->group(function () {
		Route::get('view', 'forwarderrouteconroller@view');
		Route::get('view/{id}', 'forwarderrouteconroller@view');
		Route::get('create', 'forwarderrouteconroller@manage');
		Route::post('create', 'forwarderrouteconroller@save');
		Route::get('edit/{id}', 'forwarderrouteconroller@edit');
		Route::post('edit/{id}', 'forwarderrouteconroller@save');
		Route::get('getport', 'forwarderrouteconroller@getport');
		Route::get('find/{id}', 'forwarderrouteconroller@find');
		Route::post('find/{id}', 'forwarderrouteconroller@searchresult');
		Route::post('shipInq','forwarderrouteconroller@shipInq');
		Route::get('show/{id}', 'forwarderrouteconroller@show');
		Route::get('display/{id}', 'forwarderrouteconroller@display');
	});
	Route::prefix('services')->middleware('auth')->group(function () {
		Route::get('view', 'forwarderserviceconroller@view');
		Route::get('create/{id}', 'forwarderserviceconroller@manage');
		Route::post('create/{id}', 'forwarderserviceconroller@save');
	});
	Route::prefix('inspection')->middleware('auth')->group(function () {
		Route::get('view', 'forwarderinspectionconroller@view');
		Route::get('template/{id}', 'forwarderinspectionconroller@template');
		Route::get('create/{id}', 'forwarderinspectionconroller@manage');
		Route::post('create/{id}', 'forwarderinspectionconroller@save');
		Route::get('edit/{id}', 'forwarderinspectionconroller@edit');
		Route::post('edit/{id}', 'forwarderinspectionconroller@save');
	});
});

// Fetch brands
Route::prefix('brand')->middleware('auth')->group(function () {
	Route::get('/', 'BrandController@search');
});
Route::prefix('product')->middleware('auth')->group(function () {
	Route::get('/productcategory', 'productcontroller@searchcategory');
	Route::get('/productattribute', 'productcontroller@searchattribute');
	Route::post('create', 'productcontroller@create');
	Route::get('', 'productcontroller@search');
	//Route::post('upload', 'productcontroller@fileupload');
});
Route::post('/productupload', 'productcontroller@fileupload');
Route::post('/tookandocupload', 'purchaseordercontroller@fileupload');

//vendors
Route::prefix('vendors')->middleware('auth')->group(function () {
    Route::get('create', 'vendorcontroller@manage')->middleware('can:vn_cr');
	Route::post('create', 'vendorcontroller@save');
	Route::get('unconfirmed', 'vendorcontroller@searchunconfirmed')->middleware('can:vn_cr');
	Route::get('view/{id}', 'vendorcontroller@view')->middleware('can:vn_vw,id');
	Route::post('view/{id}', 'vendorcontroller@confirm')->middleware('can:vn_co,id');
	Route::get('attach/{id}', 'vendorcontroller@attachstart')->middleware('can:vn_at,id');
	Route::post('attach/{id}', 'vendorcontroller@attach');
	Route::get('attachment/{id}', 'vendorcontroller@attachment')->middleware('can:vn_va,id');
	Route::get('{id}', 'vendorcontroller@manage')->middleware('can:vn_ch,id');
	Route::post('{id}', 'vendorcontroller@save');
	Route::get('', 'vendorcontroller@searchstart')->middleware('can:vn_sc');
	Route::post('', 'vendorcontroller@search');
});
//creditrequets
Route::prefix('creditrequests')->middleware('auth')->group(function () {
    Route::get('create', 'creditrequestcontroller@actions')->middleware('can:cr_cr');
	Route::get('raise', 'creditrequestcontroller@raise')->middleware('can:cr_cr');
	Route::get('pending', 'creditrequestcontroller@searchpending')->middleware('can:cr_of');
	Route::get('pendingcustomer', 'creditrequestcontroller@searchpendingcustomer')->middleware('can:cr_sc');
	Route::get('create/{id}', 'creditrequestcontroller@create')->middleware('can:cr_cr');
	Route::post('create/{id}', 'creditrequestcontroller@save');
	Route::get('increase/{id}', 'creditrequestcontroller@increase')->middleware('can:cr_cr');
	Route::post('increase/{id}', 'creditrequestcontroller@save');
	Route::get('approvec/{id}', 'creditrequestcontroller@approvec')->middleware('can:cr_of');
	Route::get('proceed/{id}', 'creditrequestcontroller@proceed')->middleware('can:cr_of');
	Route::post('proceed/{id}', 'creditrequestcontroller@saveapprove');
	Route::get('reject/{id}', 'creditrequestcontroller@reject');
	Route::post('attachdocument', 'creditrequestcontroller@attachdocument');
	Route::get('view/{id}', 'creditrequestcontroller@view')->middleware('can:cr_vw,id');
	Route::get('delete/{id}', 'creditrequestcontroller@getDelete')->middleware('can:cr_ch,id');
	Route::post('delete/{id}', 'creditrequestcontroller@postDelete')->middleware('can:cr_ch,id');
	Route::get('sign/{id}', 'creditrequestcontroller@sign')->middleware('can:cr_ch,id');
	Route::get('signature/{type}/{id}', 'creditrequestcontroller@signature')->middleware('can:cr_ch,id');
	Route::get('attachment/bank/{id}', 'creditrequestcontroller@bank');
	Route::get('attachment/financial/{id}', 'creditrequestcontroller@financial');
	Route::get('attachment/view/{id}', 'creditrequestcontroller@viewattachment');
	Route::get('attach/{id}', 'creditrequestcontroller@attachstart');
	Route::post('attach/{id}', 'creditrequestcontroller@attach');
	Route::get('attachment/{id}', 'creditrequestcontroller@attachment');
	Route::get('{id}', 'creditrequestcontroller@edit')->middleware('can:cr_ch,id');
	Route::post('{id}', 'creditrequestcontroller@save');
	Route::post('credit-cheque/{id}/recieved', 'creditrequestcontroller@markChequeAsRecieved');
	Route::get('', 'creditrequestcontroller@searchstart')->middleware('can:cr_sc');
	Route::post('', 'creditrequestcontroller@search');
});

//transactions
Route::prefix('transactions')->middleware('auth')->group(function () {
	Route::get('', 'transactioncontroller@pending')->middleware('can:po_sc')->name('transactions');
});		
//purchaseorders
Route::prefix('purchaseorders')->middleware('auth')->group(function () {
	Route::get('view/{id}', 'purchaseordercontroller@view')->middleware('can:po_vw,id');
	Route::get('vview/{id}', 'purchaseordercontroller@view')->middleware('can:vp_vw,id');
	Route::get('changes/{id}', 'purchaseordercontroller@changes')->middleware('can:po_vw,id');
	Route::get('approve/{id}', 'purchaseordercontroller@approve')->middleware('can:vp_ap,id');
	Route::get('approvec/{id}', 'purchaseordercontroller@approvec')->middleware('can:vp_ap,id');
	Route::get('vresend/{id}', 'purchaseordercontroller@vresend')->middleware('can:vp_ap,id');
	Route::post('vresend/{id}', 'purchaseordercontroller@verifyvrelease')->middleware('can:vp_ap,id');
	Route::post('approvec/{id}', 'purchaseordercontroller@verifyvrelease')->middleware('can:vp_ap,id');
	Route::get('reject/{id}', 'purchaseordercontroller@reject')->middleware('can:vp_ap,id');
	Route::get('rejectc/{id}', 'purchaseordercontroller@rejectc')->middleware('can:vp_ap,id');
	Route::get('creject/{id}', 'purchaseordercontroller@creject')->middleware('can:po_ch,id');
	Route::get('crejectc/{id}', 'purchaseordercontroller@crejectc')->middleware('can:po_ch,id');
	Route::get('credit-reject/{id}', 'purchaseordercontroller@creditReject')->middleware('can:po_rc,id');
	Route::get('delete/{id}', 'purchaseordercontroller@delete')->middleware('can:po_ch,id');
	Route::get('mview/{id}', 'purchaseordercontroller@view')->middleware('can:po_vm');
	Route::get('orderrelease/{id}', 'purchaseordercontroller@orderrelease')->middleware('can:po_rl,id');
	Route::get('orderreleasec/{id}', 'purchaseordercontroller@orderreleasec')->middleware('can:po_rl,id');
	Route::get('resend/{id}', 'purchaseordercontroller@resend')->middleware('can:po_rl,id');
	Route::post('resend/{id}', 'purchaseordercontroller@verifyrelease')->middleware('can:po_rl,id');
	Route::post('orderreleasec/{id}', 'purchaseordercontroller@verifyrelease')->middleware('can:po_rl,id');
	Route::get('resubmitc/{id}', 'purchaseordercontroller@resubmitc')->middleware('can:po_rl,id');
	Route::get('creditrelease/{id}', 'purchaseordercontroller@creditrelease')->middleware('can:po_rc');
	Route::get('change/{id}', 'purchaseordercontroller@manage')->middleware('can:vp_ch,id');
	Route::post('change/{id}', 'purchaseordercontroller@save');
	Route::get('create', 'purchaseordercontroller@companies')->middleware('can:po_cr');
	Route::get('create/{id}', 'purchaseordercontroller@create')->middleware('can:po_cr');
	Route::get('pendingvendor', 'purchaseordercontroller@pendingvendor')->middleware('can:po_sc');
	Route::get('pendingcustomer', 'purchaseordercontroller@pendingcustomer')->middleware('can:po_sc');
	Route::get('pendingcredit', 'purchaseordercontroller@pendingcredit')->middleware('can:po_rc');
	Route::get('tookandoc', 'purchaseordercontroller@tookandoc');
	Route::post('create', 'purchaseordercontroller@save');
	Route::post('create/{id}', 'purchaseordercontroller@savenew');
	Route::get('{id}', 'purchaseordercontroller@manage')->middleware('can:po_ch,id');
	Route::post('{id}', 'purchaseordercontroller@save');
	Route::get('', 'purchaseordercontroller@pending')->middleware('can:po_sc')->name('purchaseorders');
	Route::post('', 'purchaseordercontroller@search');
});	

//credit
Route::prefix('credit')->middleware('auth')->group(function () {
	Route::get('check', 'creditcontroller@companies')->middleware('can:co_sc');
	Route::post('check', 'creditcontroller@changelimit')->middleware('can:co_sc');
	Route::get('companies/search', 'creditcontroller@searchCompanies')->middleware('can:co_sc');
	Route::get('change/{id}', 'creditcontroller@change')->middleware('can:cr_ap');
	Route::post('change/{id}', 'creditcontroller@savelimit')->middleware('can:cr_ap');
	Route::get('company/{id}/partial-load/{owner?}', 'creditcontroller@creditStatusPartialLoad')->middleware('can:co_sc');	
	Route::get('company/{id}', 'creditcontroller@creditStatus')->middleware('can:co_sc');	
});	

//quotations
	Route::prefix('quotations')->middleware('auth')->group(function () {
		
	Route::get('view/{id}', 'quotationcontroller@view')->middleware('can:qu_vw,id');
	Route::get('bview/{id}', 'quotationcontroller@view')->middleware('can:cq_vw,id');
	// Route::get('approve/{id}', 'quotationcontroller@approve')->middleware('can:vp_ap,id');
	// 	Route::get('reject/{id}', 'quotationcontroller@reject')->middleware('can:vp_ap,id');
	Route::get('approvec/{id}', 'quotationcontroller@approvec')->middleware('can:cq_ap,id');
	
	Route::get('vresend/{id}', 'quotationcontroller@vresend')->middleware('can:cq_ap,id');
	Route::post('vresend/{id}', 'quotationcontroller@verifyvrelease')->middleware('can:cq_ap,id');
	Route::post('approvec/{id}', 'quotationcontroller@verifyvrelease')->middleware('can:cq_ap,id');
	
	Route::get('rejectc/{id}', 'quotationcontroller@rejectc')->middleware('can:cq_ap,id');
	Route::get('cancel/{id}', 'quotationcontroller@cancel')->middleware('can:qu_cr,id');
// 	Route::get('creject/{id}', 'quotationcontroller@creject')->middleware('can:qu_ch,id');
// 	Route::get('crejectc/{id}', 'quotationcontroller@crejectc')->middleware('can:qu_ch,id');
// 	Route::get('mview/{id}', 'quotationcontroller@view')->middleware('can:qu_vm');
	Route::get('delete/{id}', 'quotationcontroller@delete')->middleware('can:qu_ch,id');
// 	Route::get('release/{id}', 'quotationcontroller@orderrelease')->middleware('can:qu_rl,id');
// 	Route::get('releasec/{id}', 'quotationcontroller@orderreleasec')->middleware('can:qu_rl,id');
// 	Route::get('creditrelease/{id}', 'quotationcontroller@creditrelease')->middleware('can:qu_rc');
	Route::get('orderreleasec/{id}', 'quotationcontroller@orderreleasec')->middleware('can:qu_rl,id');
	
	Route::get('orderreleasec/{id}', 'quotationcontroller@orderreleasec')->middleware('can:qu_rl,id');
	Route::get('resend/{id}', 'quotationcontroller@resend')->middleware('can:qu_rl,id');
	Route::post('resend/{id}', 'quotationcontroller@verifyrelease')->middleware('can:qu_rl,id');
	Route::post('orderreleasec/{id}', 'quotationcontroller@verifyrelease')->middleware('can:qu_rl,id');
	
	Route::get('change/{id}', 'quotationcontroller@edit')->middleware('can:cq_ch,id');
	Route::post('change/{id}', 'quotationcontroller@save')->middleware('can:cq_ch,id');
// 	Route::get('create/{id}', 'quotationcontroller@create')->middleware('can:qu_cr');
// 	Route::get('pendingvendor', 'quotationcontroller@pendingvendor')->middleware('can:qu_sc');
// 	Route::get('pendingcustomer', 'quotationcontroller@pendingcustomer')->middleware('can:qu_sc');
// 	Route::get('pendingcredit', 'quotationcontroller@pendingcredit')->middleware('can:qu_rc');
	Route::post('create', 'quotationcontroller@newquotation');
// 	Route::post('create/{id}', 'quotationcontroller@savenew');
	Route::get('create', 'quotationcontroller@create')->middleware('can:qu_cr')->name('quotations.create');

	Route::get('{id}', 'quotationcontroller@edit')->middleware('can:qu_ch,id');
	
	Route::post('{id}', 'quotationcontroller@save');

	Route::get('', 'quotationcontroller@searchstart')->middleware('can:qu_sc')->name('quotations');
	
	Route::post('', 'quotationcontroller@search');
});

// Quotations Coming Soon
// Route::pattern('any', '[A-z]*');
// Route::prefix('quotations')->middleware('auth')->group(function () {
	// Route::get('', function () { return view('coming-soon', ['context' => 'Quotations']); })->middleware('auth');
	// Route::get('{any}', function () { return view('coming-soon', ['context' => 'Quotations']); })->middleware('auth');
// });

//pending requests 
Route::get('pending-requests', 'PendingRequestsController@view')->middleware('auth');

//countries
Route::prefix('countries')->middleware('auth')->group(function () {
	    Route::post('cities', 'countrycontroller@cities');
});
//modules
Route::prefix('modules')->middleware('auth')->group(function () {
	    Route::post('permissions', 'modulecontroller@permissions');
});
//material groups
Route::prefix('materialgroups')->middleware('auth')->group(function () {
	Route::get('{id?}', 'materialgroupcontroller@index')->middleware('can:mg_mg');
	Route::post('{id?}', 'materialgroupcontroller@save')->middleware('can:mg_mg');
});
//payment terms
Route::prefix('paymentterms')->middleware('auth')->group(function () {
	Route::get('{id?}', 'paymenttermcontroller@index')->middleware('can:pt_mg');
	Route::post('{id?}', 'paymenttermcontroller@save')->middleware('can:pt_mg');
});
//users
Route::prefix('users')->middleware('auth')->group(function () {	
	Route::get('create', 'usercontroller@manage')->middleware('can:us_cr');
	Route::post('create', 'usercontroller@save');
	Route::get('view/{id}', 'usercontroller@view')->middleware('can:us_vw,id');
	Route::get('{id}', 'usercontroller@manage')->middleware('can:us_ch,id');
	Route::post('{id}', 'usercontroller@save');	
	Route::get('', 'usercontroller@searchstart')->middleware('can:us_sc');
	Route::post('', 'usercontroller@search');
});
//calendar
Route::prefix('calendar')->middleware('auth')->group(function () {
	Route::get('view/{id}', 'calendarcontroller@view')->middleware('can:cr_sc');
	Route::get('manage/{id}', 'calendarcontroller@manage')->middleware('can:cr_sc');
	Route::get('cancel/{id}', 'calendarcontroller@cancel')->middleware('can:cr_ap');
	Route::get('cancelc/{id}', 'calendarcontroller@cancelc')->middleware('can:cr_ap');
	Route::get('complete/{id}', 'calendarcontroller@complete')->middleware('can:cr_of');
	Route::post('completec/{id}', 'calendarcontroller@completeappointment')->middleware('can:cr_of');
	Route::get('confirm/{id}', 'calendarcontroller@confirm')->middleware('can:cr_sc');
	Route::get('accept/{id}', 'calendarcontroller@accept')->middleware('can:cr_of');
	Route::get('acceptc/{id}', 'calendarcontroller@acceptc')->middleware('can:cr_of');
	Route::get('reject/{id}', 'calendarcontroller@reject')->middleware('can:cr_sc');
	Route::get('rejectc/{id}', 'calendarcontroller@rejectc')->middleware('can:cr_sc');
	Route::get('unblock/{id}', 'calendarcontroller@unblock')->middleware('can:cr_ap');
	Route::get('unblockc/{id}', 'calendarcontroller@unblockc')->middleware('can:cr_ap');
	Route::get('create/{id?}', 'calendarcontroller@create')->middleware('can:cr_sc');
	Route::get('block-appointments', 'calendarcontroller@blockAppointments')->middleware('can:cr_ap');
	Route::post('block-appointments/{id?}', 'calendarcontroller@block')->middleware('can:cr_ap');
	Route::post('create/{id?}', 'calendarcontroller@save');
	Route::get('upcoming', 'calendarcontroller@searchupcoming');
	Route::get('pending', 'calendarcontroller@searchpending');
	Route::get('pendingcredit', 'calendarcontroller@searchpendingcredit');
	Route::get('', 'calendarcontroller@searchstart');
	Route::post('', 'calendarcontroller@search');
});
//roles
Route::prefix('roles')->middleware('auth')->group(function () {
	Route::get('create', 'rolecontroller@create')->middleware('can:ro_cr');
	Route::post('create', 'rolecontroller@save');
	Route::get('users/{id}', 'rolecontroller@users')->middleware('can:ro_as,id');
	Route::post('users/{id}', 'rolecontroller@assign');
	Route::get('delete/{id}', 'rolecontroller@delete');
	Route::get('deletec/{id}', 'rolecontroller@deletec');
	Route::get('activate/{id}', 'rolecontroller@activate');
	Route::get('deactivate/{id}', 'rolecontroller@deactivate');
	Route::get('view/{id}', 'rolecontroller@view')->middleware('can:ro_vw,id');
	Route::get('{id}', 'rolecontroller@manage')->middleware('can:ro_ch,id');
	Route::post('{id}', 'rolecontroller@save');
	Route::get('', 'rolecontroller@searchstart')->middleware('can:ro_sc');
	Route::post('', 'rolecontroller@search');
});
//users
Route::prefix('users1')->middleware('auth')->group(function () {
	Route::get('create', 'usercontroller@manage')->middleware('can:us_cr');
	Route::post('create', 'usercontroller@save');
	Route::get('view/{id}', 'usercontroller@view')->middleware('can:us_vw,id');
	Route::get('assign/{id}', 'usercontroller@assign')->middleware('can:us_as,id');
	Route::post('assign/{id}', 'usercontroller@save');
	Route::get('{id}', 'usercontroller@manage')->middleware('can:us_ch,id');
	Route::post('{id}', 'usercontroller@save');
	Route::get('', 'usercontroller@searchstart')->middleware('can:us_cr')->middleware('can:us_ch')->middleware('can:us_vw');
	Route::post('', 'usercontroller@search');
});
//attachments
Route::post('/attach', 'attachmentcontroller@upload');
//shippingaddress
Route::prefix('shippingaddresses')->middleware('auth')->group(function () {
	Route::get('create', 'shippingaddresscontroller@manage');
	Route::post('create', 'shippingaddresscontroller@save');
	Route::get('view/{id}', 'shippingaddresscontroller@view')->middleware('can:co_sc');
	Route::get('{id}', 'shippingaddresscontroller@manage')->middleware('can:co_ch');
	Route::post('{id}', 'shippingaddresscontroller@save')->middleware('can:co_ch');
	Route::get('', 'shippingaddresscontroller@list');
});																		   
Route::post('/shippingaddress', 'companycontroller@shippingaddress');
Route::post('/shippingaddressdata', 'companycontroller@shippingaddressdata');

//pickupaddress
Route::prefix('pickupaddresses')->middleware('auth')->group(function () {
	Route::get('create', 'pickupaddresscontroller@manage');
	Route::post('create', 'pickupaddresscontroller@save');
	Route::get('view/{id}', 'pickupaddresscontroller@view')->middleware('can:co_sc');
	Route::get('{id}', 'pickupaddresscontroller@manage')->middleware('can:co_ch');
	Route::post('{id}', 'pickupaddresscontroller@save')->middleware('can:co_ch');
	Route::get('', 'pickupaddresscontroller@list');
});																		   
Route::post('/pickupaddress', 'companycontroller@pickupaddress');
Route::post('/pickupaddressdata', 'companycontroller@pickupaddressdata');

//VAT exempt
Route::prefix('vatexempt')->middleware('auth')->group(function () {
	Route::get('/approve/{id}', 'shippingaddresscontroller@vatapprove');
	Route::get('/reject/{id}', 'shippingaddresscontroller@vatreject');
	Route::get('', 'shippingaddresscontroller@vatexemptlist');
	Route::post('', 'shippingaddresscontroller@vatexemptlist');
});																

//Route::get('sign/{event?}', 'testcontroller@sign'); 
Route::get('signature/{envelope}/{event?}', 'creditrequestcontroller@signed'); 
Route::get('signing/{code}', 'creditrequestcontroller@signature'); 
//sign the delivery
Route::get('dsignature/{envelope}/{event?}', 'purchaseordercontroller@signed'); 
Route::get('dsigning/{code}', 'purchaseordercontroller@signature'); 
//Route::get('cr/signature/{type}/{id}', 'creditrequestcontroller@signature');
//support
Route::prefix('support')->group(function () {
	Route::get('', function () { return view('support.create'); })->middleware('guest');
	Route::post('', 'supportcontroller@save')->middleware('guest');
	Route::get('report-issue', 'supportcontroller@getReportIssue')->middleware('auth');
	Route::post('report-issue', 'supportcontroller@postReportIssue')->middleware('auth');
	Route::get('view-issue/{id}', 'supportcontroller@viewUserIssue')->middleware('auth');	
	Route::get('issues', 'supportcontroller@getUserIssues')->middleware('auth');	

});
Route::prefix('supports')->middleware('auth')->group(function () {
	Route::get('view/{id}', 'supportcontroller@view')->middleware('can:su_vw');
	Route::get('open', 'supportcontroller@searchopen')->middleware('can:su_sc');
	Route::get('{id}', 'supportcontroller@manage')->middleware('can:su_ch');
	Route::post('{id}', 'supportcontroller@close')->middleware('can:su_ch');	
	Route::get('', 'supportcontroller@searchstart')->middleware('can:su_sc');
	Route::post('', 'supportcontroller@search')->middleware('can:su_sc');
});

Route::prefix('chat')->middleware('auth')->group(function () {
	Route::get('users', 'chatcontroller@dataAjax');
	Route::get('getAll/{id}', 'chatcontroller@getAll');
	Route::get('/negotiate' , 'chatcontroller@create_negotiation_chats');
	Route::post('/normal' , 'chatcontroller@create_normal_chat');
	Route::get('userchat', 'chatcontroller@userchat');
	Route::get('/create', 'chatcontroller@create');	
	Route::post('/create', 'chatcontroller@newchat');	
	Route::post('/members', 'chatcontroller@members');	
	Route::post('post/{id}', 'chatcontroller@save');
	Route::get('/{id?}', 'chatcontroller@index');	
	Route::post('reset/{id}', 'chatcontroller@resetcount');

});
	
//message (chat)
Route::prefix('message')->middleware('auth')->group(function () {
	Route::get('', function () {
		return view('messages.manage');
	});
});

Route::prefix('data-reporting')->middleware('auth')->group(function () {
	Route::get('companies/search', 'DataReportingController@searchCompanies');
	Route::get('outstanding/{companyId?}', 'DataReportingController@outstanding');
	Route::get('outstanding/company/{companyId}/partial-load/{owner?}', 'DataReportingController@outstandingPartialLoad');	
	Route::get('statement-of-account/company/{companyId}/partial-load/{owner?}', 'DataReportingController@statementOfAccountPartialLoad');	
	Route::get('statement-of-account', 'DataReportingController@statementOfAccount')->name('account-summary');	
	Route::get('', 'DataReportingController@index');	
});

Route::prefix('profile')->middleware('auth')->group(function () {
	Route::get('', 'ProfileController@index');
	Route::get('edit', 'ProfileController@editProfile');
	Route::post('edit', 'ProfileController@saveProfile');
	Route::get('change-password', 'ProfileController@changePassword');
	Route::post('change-password', 'ProfileController@savePassword');
});

Route::get('signature/{envelope}/{event?}', 'creditrequestcontroller@signed'); 

Route::post('freightexpense', 'freightexpensecontroller@list'); 

Route::get('checkpickup/{id}/{authcode}', 'creditrequestcontroller@checkpickup'); 
Route::post('checkpickup/{id}/{authcode}', 'creditrequestcontroller@savecheckpickup'); 																				  
Route::prefix('logs')->middleware('auth')->group(function () {
	Route::get('login-logs', 'LogsController@loginLogs')->middleware('can:sy_ad');
	Route::post('login-logs', 'LogsController@loginLogs')->middleware('can:sy_ad');
	Route::get('', 'LogsController@index')->middleware('can:sy_ad');
	Route::get('phpinfo', 'LogsController@phpinfo')->middleware('can:sy_ad');
});

//larametrics
Route::group(['middleware' => ['auth', 'can:sy_ad'], 'prefix' => 'admin'], function() {
    \Aschmelyun\Larametrics\Larametrics::routes();
});


Route::prefix('inquiry')->middleware('auth')->group(function () {
	Route::get('/add', 'InquiryController@add_to_inquiry')->name('addToInquiry');
	Route::get('/', 'InquiryController@show_inquiry')->name('showInquiry');

});

//register
Route::get('register/verify/{token}', 'Auth\RegisterController@verify'); 
Route::post('register/verify-account', 'Auth\RegisterController@verifyAccount'); 
Route::post('register/send-verification-code', 'Auth\RegisterController@sendVerificationCode'); 
Route::get('logout', 'Auth\LoginController@logout');
Route::get('/auth/login', 'Auth\LoginController@showLoginForm');

//Search in products , people and companies
Route::get('/search/{query}' , 'homecontroller@home_search')->name('homeSearch');

Auth::routes();