<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Attachmenttype;
use App\Company;
use App\Currency;
use App\Product;
use App\Productattribute;
use App\Productattributevalues;
use App\Productcategory;
use App\Status;

use App\Http\Requests\CreateProductRequest;

use App\Repositories\ProductRepository;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Input;
use View;

class productcontroller extends Controller
{
    public function view(Request $request)
    {
        $title = "Product Catalog";
        $productcategories = Productcategory::all();
        $products = Product::all();
        return view('products.list')
            ->with('title', $title)
            ->with('categories', $productcategories);
    }


    public function productadd(Request $request)
    {
        $vendor = Auth::user()->getSupplierCompany();		
		
        if(!$vendor)
            return view('message',[
                'title' => 'Create product',
                'message' => 'Cannot create product',
                'description' =>  __('messages.noSupplierCompany', ['context' => 'product']),
                'error' => true,
            ]);
            
        $productattribute = Productattribute::where('system', 1)->get();
        $title = "New Product";
		$currencies = Currency::where('active', 1)->orderBy('name')->get();
		$statuses = Status::where('statustype', 'productcondition')->where('active', 1)->orderBy('name')->get();
        return view('products.manage')
			->with('title',$title)
			->with('currencies',$currencies->pluck('name', 'id'))
			->with('statuses',$statuses->pluck('name', 'id'))
            ->with('productattribute', $productattribute);
    }
    public function product(Request $request, $id)
    {
        //echo("Prod");
        $title = "Product";
        $product = Product::with('attributes', 'productcategory', 'currency', 'productcondition')->find($id);
        // return $product;
        $attachments = Attachment::where('attachable_id', $id)->where('attachmenttype_id', Attachment::PRODUCT_IMAGE)->get();
        $productattibutevalues = Productattributevalues::where('product_id', $id)->get();
        return view('products.manage')
            ->with('title', $title)
            ->with('mode', 'v')
            ->with('product', $product)
            ->with('attachments', $attachments)
            ->with('productattibutevalues', $productattibutevalues);
    }
   
    public function create(CreateProductRequest $request)
    {
        $vendor = Auth::user()->getSupplierCompany();
        $product = new Product;
        $product->company_id = $vendor->id;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->condition = $request->input('condition');
        $product->price = $request->input('price');
        $product->currency_id = $request->input('currency_id');
        $product->category_id = $request->input('product_category');
		if (Input::has('cbvat')) {
			$product->active = Input::has('cbvat');
		} else {
			$product->active = 0;
		}
		$product->created_by = Auth::user()->id;
        $product->updated_by = Auth::user()->id;
        $product->save();
        //images
        $img = $request->input('images');
        $imgs = explode(",", $img);
        foreach ($imgs as $att) {
            $attachment = Attachment::find($att);
            if(isset($attachment)){
                $attachment->attachmenttype_id = Attachment::PRODUCT_IMAGE;
                $attachment->attachable_type = "product";
                $attachment->attachable_id = $product->id;
                $attachment->save();              
            }
        }
        DB::table('productattributevalues')->where('product_id', $product->id)->delete();
        $productattribute = Productattribute::all();
        foreach ($productattribute as $attribute) {
            if ($request->input('product_details_' . $attribute->id) != null) {

                $productattributevalues = new Productattributevalues;
                $productattributevalues->product_id = $product->id;
                $productattributevalues->productattribute_id = $attribute->id;
                $productattributevalues->value = $request->input('product_details_' . $attribute->id);
                $productattributevalues->save();
            }
        }
        return redirect('/companies/product/'.$product->id);
    }
    public function productedit(Request $request, $id)
    {
        $title = "Product";
        //$product = Product::with('attributes')->find($id);
        $product = Product::with('attributes', 'productcategory')->find($id);
        // $product = Product::find($id);
        // $productattribute = Productattribute::all();
        $attachments = Attachment::where('attachable_id', $id)->where('attachmenttype_id', Attachment::PRODUCT_IMAGE)->get();
        // $productattibutevalues = Productattributevalues::where('product_id', $id)->get();
        $currencies = Currency::where('active', 1)->orderBy('name')->get(['id', 'name']);
        $statuses = Status::where('statustype', 'productcondition')->where('active', 1)->orderBy('name')->get();
        return view('products.manage')
            ->with('title', $title)
            // ->with('productattribute', $productattribute)
            ->with('attachments', $attachments)
            // ->with('productattibutevalues', $productattibutevalues)
            ->with('currencies',$currencies->pluck('name', 'id'))
			->with('statuses',$statuses->pluck('name', 'id'))
            ->with('product', $product);
    }
    public function productsave(Request $request, $id)
    {
        $vendor = Auth::user()->getSupplierCompany();
        $product = Product::find($id);
        $product->company_id = $vendor->id;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->condition = $request->input('condition');
        $product->price = $request->input('price');
        $product->currency_id = $request->input('currency_id');
        $product->category_id = $request->input('product_category');
        $product->active = 1;
        $product->created_by = Auth::user()->id;
        $product->updated_by = Auth::user()->id;
        $product->save();
        $img = $request->input('images');
        $imgs = explode(",", $img);
        foreach ($imgs as $att) {
            $attachment = Attachment::find($att);
            $attachment->attachmenttype_id = Attachment::PRODUCT_IMAGE;
            $attachment->attachable_type = "product";
            $attachment->attachable_id = $product->id;
            $attachment->save();
        }
        DB::table('productattributevalues')->where('product_id', $product->id)->delete();
        $productattribute = Productattribute::all();
        foreach ($productattribute as $attribute) {
            if ($request->input('product_details_' . $attribute->id) != null) {
                $productattributevalues = new Productattributevalues;
                $productattributevalues->product_id = $product->id;
                $productattributevalues->productattribute_id = $attribute->id;
                $productattributevalues->value = $request->input('product_details_' . $attribute->id);
                $productattributevalues->save();
            }
        }

        return redirect('/companies/product/'.$product->id);
    }
    public function productdelete(Request $request, $id)
    {
        // $attachments  = Attachment::where('attachable_id', $id)->where('attachmenttype_id', Attachment::PRODUCT_IMAGE)->get();
        DB::table('attachments')->where('attachable_id', $id)->where('attachmenttype_id', Attachment::PRODUCT_IMAGE)->delete();
        DB::table('productattributevalues')->where('product_id', $id)->delete();

        $product = Product::find($id);
        $product->delete();

        return redirect('/companies/products');
    }
    public function fileupload(Request $request)
    {
        $path = $request->file('attach')->store('upload/' . date('Y') . '/' . date('m'));
        $attachment = new Attachment;
        $attachment->path = $path;
        $attachment->created_by = Auth::user()->id;
        $attachment->updated_by = Auth::user()->id;
        $attachment->description = 'Product image';
        $attachment->save();        
        //return $attachment->id;
		//return response()->json($attachment);
		return Response(['id' => $attachment->id,'path' => $attachment->path]);

    }
	
    public function searchcategory(Request $request)
    {
        $keyword = $request->q;
        $brands = Productcategory::where('category', 'like', '%' . $keyword . '%')->where('active', 1)->orderBy('category', 'asc')->get();

        return response()->json($brands);
    }

    public function searchattribute(Request $request)
    {
        $keyword = $request->q;
        $brands = Productattribute::where('attribute', 'like', '%' . $keyword . '%')->where('system', 0)->orderBy('attribute', 'asc')->get();

        return response()->json($brands);
    }
	
	public function list()
	{
	  return response(Product::with('productcategory', 'company', 'currency', 'productcondition', 'attachments')->get()->jsonSerialize(), Response::HTTP_OK);
	}
	
	public function search()
	{
	  return view('products.list');
			
    }
    
    /**
     * add product to database
     */
    public function post_product_data (CreateProductRequest $request){
            //All Product inputes except files
            $productData = $request->all();
            // product images
            $productImages = $request->file('fileup') ?  $request->file('fileup') : [];
            //send data to product repository to create new product 
            $productRepo= new ProductRepository;
            $product = $productRepo->create($productData , $productImages);       
            if($product){
                return view('products.displayProduct')->with('product',$product);
            }
    }

    /**
     * shoe Edit produt
     */
    public function show_edit_product ($product_id){
        $product = \App\Product::whereId($product_id)->with('attributes','customAttributes')->first();
        return view('products.manage')->with('product',$product);
    }

    /**
     * Edit product
     * 
     */
    public function edit_product_data_posting (CreateProductRequest $request){
            //All Product inputes except files
            $productData = $request->all();
            // product images
            $productImages = $request->file('fileup') ?  $request->file('fileup') : [];
            $productRepo= new ProductRepository;

            $product = $productRepo->update($productData , $productImages);
            if($product){
                return view('products.displayProduct')->with('product',$product);
            }
    }


    /**
     * get category products
     */
    public function get_category_products ($category_id){
        $category = \App\Productcategory::whereId($category_id)->first();
        $products = \App\Product::whereCategoryId($category_id)->with('images')->paginate(12);
        $subCategories = \App\ProductSubCategory::whereCategoryId($category_id)->get();
        return view('products.products')->with(['products'=>$products , 'subCategories' => $subCategories , 'category' => $category] );
    }

    //Get subCategory when select Category
    public function get_subCategory ($category_id){
        $subCategories = \App\ProductSubCategory::whereCategoryId($category_id)->get();
        return $subCategories;
    }

    //get subCategories products
    public function get_subCategories_products ($subCategory_id){
        //variable format is {all or name of sub category .'-'. subcategoryID}
        $checkIfSubCategory = explode('-' , $subCategory_id);
        //get all products
        if( $checkIfSubCategory[0] == 'all'){
            $products = \App\Product::where('category_id',$checkIfSubCategory[1])->with('images')->get();
        }
        //get subcategory products
        else{
            $products = \App\Product::where(['subCategory_id'=>$checkIfSubCategory[1]  ])->with('images')->get();
        }
        return $products;
    }

    /**
     * Show product details
     */
    public function get_product_details($product_id){   
        $product = \App\Product::whereId($product_id)->with(['images' ,'attributes'])->first();
        $similar_products = \App\Product::where('id' , '!=' ,$product_id)->whereCategoryId($product->category_id)->limit(4)->get();
        return view('products.displayProduct',['product'=>$product , 'similarProducts'=>$similar_products]);
    }



}
