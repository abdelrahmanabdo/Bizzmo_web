<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use \App\Inquiry;
class InquiryController extends Controller
{

    /**
     * Add Product to inquiry
     */
    public function add_to_inquiry (Request $request){
        $buyer_id = Auth::user()->getCompanyId();  
        $product = \App\Product::find($request->product_id);
        $isAdded = Inquiry::where(['product_id' => $request->product_id ,
                                    'supplier_id' => $request->supplier_id ,
                                    'buyer_id' => $buyer_id])->count();
        if($isAdded == 0){
            //check if there old open item in inquiry to get the deal id            
            $isSameSupplierDealId = Inquiry::where([
                                                'supplier_id' => $request->supplier_id,
                                                'buyer_id'    => $buyer_id,
                                                'status'      => 'waiting'
                                            ])->value('deal_id');
            $inquiry =  new Inquiry();
            $inquiry->product_id = $request->product_id;
            $inquiry->supplier_id = $request->supplier_id;
            $inquiry->buyer_id = $buyer_id;
            $inquiry->qty = $request->qty ?? 1;
            $inquiry->price = $product->price;     
            if(empty($isSameSupplierDealId)){
                $inquiry->deal_id = '#'.mt_rand(100000,999999);
            }else{
                $inquiry->deal_id = $isSameSupplierDealId;         
            }
            $inquiry->save();

            if($request->withQty){
                session()->flash('success', '');
                session()->forget('success');
                return 'success';
            }else{
                return back()->with('success' , 'The product was added successfully');
            }
        }else{
            if($request->withQty){
               session()->put('error', '');
               session()->forget('error');
               return 'error';
            }else{
                return back()->with('error' , 'The product has already been added before');
            }
        }
    }

    // Show Inquiry Page
    public function show_inquiry (){
        return view('cart.cart');
    }
}
