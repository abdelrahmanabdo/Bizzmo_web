<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Models
use App\Company;
use App\Incoterm;
use App\Currency;
use App\Unit;
use App\Deliverytype;
use App\Jobs\ProcessBizzmoPO;
use App\Shippingaddress;
use App\Purchaseorder;
use App\Settings;
use App\Purchaseorderitem;
use App\Jobs\ProcessPOoutput;

class PurchaseOrderController extends Controller
{
    /**
     * Create new purchase order
     */
    public function add_purchaseorder(Request $request){
		$rules = [
			'date' => 'required|date_format:j/n/Y',
			'deliverbydate' => 'required|date_format:j/n/Y',
			'pickupbydate' => 'required|date_format:j/n/Y',
			'shippingaddress_id' => 'required|integer|min:1',
			'shipaddress' => 'required_if:shippingaddress_id,0|max:180',
			'po_box' => 'max:180',
			'city_id' => 'required_unless:country_id,0',
			'country_id' => 'required_if:shippingaddress_id,0',
			'otherCountry' => 'required_if:country_id,0',
			'otherCity' => 'required_if:country_id,0',
			'note' => 'max:180',
			'itemcount' => 'required|integer|min:1',
			'productname.*' => 'required_with:mpn.*,brand.*,price.*,quantity.*|max:180',
			'mpn.*' => 'required_with:productname.*,brand.*,price.*,quantity.*|max:60',
			'brand.*' => 'required_with:productname.*,mpn.*,price.*,quantity.*|max:60',
			'price.*' => 'sometimes|nullable|required_with:productname.*,mpn.*,brand.*,quantity.*|numeric',
			'quantity.*' => 'sometimes|nullable|required_with:productname.*,mpn.*,brand.*,price.*|numeric',
			'vendor_id' => 'required'
				];
				
		$messages = [
			'otherCountry.required_if' => 'Please provide the shipping country',
			'otherCity.required_if' => 'Please provide the shipping city',
			'vendor_id.required' => 'Please provide a supplier',
			'shippingaddress_id.required' => 'Please provide a shipping address',
			'note' => "Note length should not exceed 180 characters",
			'productname.*.max' => "Product description length should not exceed 180 characters",
			'productname.*' => "Please provide the product description",
			'mpn.*' => "Please provide the MPN",
			'brand.*' => "Please select the brand",
			'price.*' => "Please provide the price",
			'quantity.*' => "Please provide the quantity",
		];
        
		// Validate products count
		$productsCount = 0;
        $i = 0;
        
		foreach ($request->productname as $item) {
			if ($item && $request->itemdel[$i] != 1)
				$productsCount++;
			$i++;
		}

		if($productsCount < 1) {
			$firstProduct = 0;
			foreach ($request->itemdel as $key => $item) {
				if ($item != 1) {
					$firstProduct = $key;
					break;
				}
			}

			$rules = [
				"productname.$firstProduct" => 'required|max:180',
				"mpn.$firstProduct" => 'required|max:60',
				"brand.$firstProduct" => 'required|max:60',
				"price.$firstProduct" => 'required|numeric',
				"quantity.$firstProduct" => 'required|numeric'
				];
			$messages = [
			'productname.*.max' => "Product description length should not exceed 180 characters",
			'productname.*' => "Please provide the product description",
			'mpn.*' => "Please provide the MPN",
			'brand.*' => "Please select the brand",
			'price.*' => "Please provide the price",
			'quantity.*' => "Please provide the quantity",
		];
			$this->validate($request, $rules, $messages);
		}
		
		$number = \DB::table('purchaseorders')->where('company_id', $request->company_id)->max('number');				
		$date = date_create_from_format("d/m/Y",$request->date);
		
		//sales order number
		$setting = Settings::find(Settings::SALES_ORDER);						
		$purchaseorder = new Purchaseorder;
		$purchaseorder->salesorder     = $setting->SalesOrderNumber();
		$purchaseorder->number         = $number + 1;						
		$vendornumber                  = \DB::table('purchaseorders')->max('vendornumber');	
		$purchaseorder->vendornumber   = $vendornumber + 1;
		$purchaseorder->company_id     = $request->company_id;
		$purchaseorder->vendor_id      = $request->vendor_id;
		$purchaseorder->date           = $date->format('Y-m-d');			
		$purchaseorder->paymentterm_id = $request->paymentterm_id;			
		$purchaseorder->created_by     = $request->user_id;			
		$purchaseorder->version        = -1;
		
		$purchaseorder->status_id = 13;
		if ($request->shippingaddress_id != 0) {
			$purchaseorder->shippingaddress_id = $request->shippingaddress_id;
        }
        
		$purchaseorder->pickupaddress_id = $request->pickupaddress_id;
		$purchaseorder->deliverytype_id  = $request->deliverytype_id;
		if ($request->freightexpense_id) {
			$purchaseorder->freightexpense_id = $request->freightexpense_id;
		} else {
			$purchaseorder->freightexpense_id = null;
		}
		$purchaseorder->vat              = $this->getVAT($request->shippingaddress_id);
		$purchaseorder->note             = $request->note;
		$purchaseorder->currency_id      = $request->currency_id;
		$purchaseorder->incoterm_id      = $request->incoterm_id;
		$purchaseorder->paymentterm_id   = $request->paymentterm_id;
        $purchaseorder->buyup            = Company::find($request->company_id)
                                                  ->paymentterms()
                                                  ->where('paymentterm_id', $request->paymentterm_id)
                                                  ->first()->pivot->buyup;
		$purchaseorder->deliverbydate    = date_create_from_format("d/m/Y",$request->deliverbydate);
		$purchaseorder->deliverbytime_id = $request->deliverbytime_id;
		$purchaseorder->pickupbydate     = date_create_from_format("d/m/Y",$request->pickupbydate);
		$purchaseorder->pickupbytime_id  = $request->pickupbytime_id;
		$purchaseorder->updated_by       = $request->user_id;
		if ($purchaseorder->userrelation == 1) {
			$purchaseorder->vendor_id = $request->vendor_id;
		}

		// Increament PO version
		$purchaseorder->version = $purchaseorder->version + 1;

		// Save
		$purchaseorder->save();

		//PO sub data
		$i = 0;
		if ($request->has('itemid')) {
			foreach ($request->itemid as $item) {
				if (!$request->productname[$i]) {
					$i++;
					continue;
				}
				$price = $request->price[$i];
				if ($item == '' && $request->itemdel[$i] == '') {					
                    $purchaseorderitem  = new Purchaseorderitem(array('productname' => $request->productname[$i], 
                                                                      'MPN' => $request->mpn[$i], 'brand_id' => $request->brand[$i], 
                                                                      'unit_id' => $request->unit_id[$i], 'quantity'=> $request->quantity[$i],
                                                                      'price'=> $price));
					$purchaseorder->purchaseorderitems()->save($purchaseorderitem);
				} elseif ($item != '') {
					if ($request->itemdel[$i] == '') {
						$purchaseorderitem = Purchaseorderitem::find($item);
						if ($purchaseorderitem->productname != $request->productname[$i] ||
							$purchaseorderitem->mpn         != $request->mpn[$i] || 
							$purchaseorderitem->brand_id    != $request->brand[$i] || 
							$purchaseorderitem->unit_id     != $request->unit_id[$i] || 
							$purchaseorderitem->quantity    != $request->quantity[$i] || 
							$purchaseorderitem->price       != $price
						) {
							$purchaseorderitem->productname = $request->productname[$i];
							$purchaseorderitem->mpn         = $request->mpn[$i];
							$purchaseorderitem->brand_id    = $request->brand[$i];
							$purchaseorderitem->unit_id     = $request->unit_id[$i];
							$purchaseorderitem->quantity    = $request->quantity[$i];
							$purchaseorderitem->price       = $price;
							$purchaseorderitem->save();
						}
					} else {
						$purchaseorderitem = Purchaseorderitem::destroy($item);
					}
				}
				$i++;
			}
		}

		//shipping address
        $shippingaddresses = Shippingaddress::where('address', $purchaseorder->shippingaddress)
                                            ->where('company_id', $purchaseorder->company_id)
                                            ->get();
		$purchaseorder->saveAddresses();
        ProcessPOoutput::dispatch($purchaseorder);
        
		return response()->json([
			'status'  => true,
			'message' => 'Puchase Orderd is created successfully',
			'data'    => $purchaseorder
		]);	
									
	}
	
	//Get Shipping address VAT
	public function getVAT($shipaddressid) 
	{
		if (!$shipaddressid || $shipaddressid == 0)
			return 0;
		else {
			$shipaddress = Shippingaddress::find($shipaddressid);
			return $shipaddress->vat ? $shipaddress->company->vat : 0;
		}
	}

}
