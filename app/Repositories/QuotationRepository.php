<?php

namespace App\Repositories;

use Auth;
use DB;

use App\Company;
use App\Quotation;
use App\Quotationitem;
use App\Shippingaddress;
use App\Paymentterm;


class QuotationRepository
{
	public static function create(array $data) {
		$shipaddrs = Shippingaddress::find($data['shippingaddress_id']);
		$quotation = new Quotation;
		$number = DB::table('quotations')->where('vendor_id', $data['vendor_id'])->max('number');				
		$date = date_create_from_format("d/m/Y",$data['date']);
		$quotation = new Quotation;
		$quotation->number = $number + 1;						
		$vendornumber = DB::table('quotations')->max('vendornumber');	
		$quotation->vendornumber = $vendornumber + 1;
		$quotation->company_id = $data['company_id'];
		$quotation->vendor_id = $data['vendor_id'];
		$quotation->date = $date->format('Y-m-d');			
		$quotation->paymentterm_id = $data['paymentterm_id'];			
		$quotation->created_by = Auth::user()->id;			
		$quotation->version = 0;
		$quotation->vat = $shipaddrs->vat ? $shipaddrs->company->vat : 0; //$data['VAT']; //Company::find($data['company_id'])->vat;
		$quotation->note = $data['note'];
		$quotation->currency_id = $data['currency_id'];
		$quotation->incoterm_id = $data['incoterm_id'];
		$quotation->paymentterm_id = $data['paymentterm_id'];
		$quotation->shippingaddress_id = $data['shippingaddress_id'];
		$quotation->pickupaddress_id = $data['pickupaddress_id'];
		$quotation->deliverytype_id = $data['deliverytype_id'];
		$quotation->deliverbydate = date_create_from_format("d/m/Y",$data['deliverbydate']);
		$quotation->deliverbytime_id = $data['deliverbytime_id'];
		$quotation->pickupbydate = date_create_from_format("d/m/Y",$data['pickupbydate']);
		$quotation->pickupbytime_id = $data['pickupbytime_id'];
		if (array_key_exists("freightexpense_id",$data)) {
			$quotation->freightexpense_id = $data['freightexpense_id'];
		} else {
			$quotation->freightexpense_id = null;
		}
		$quotation->buyup = Company::find($data['company_id'])->paymentterms()->where('paymentterm_id', $data['paymentterm_id'])->first()->pivot->buyup;
		$quotation->updated_by = Auth::user()->id;
		$quotation->status_id = 23;
		$quotation->save();
		//Quotation sub data
		$i = 0;

		if ($data['itemid']) {
			foreach ($data['itemid'] as $item) {
				if (!$data['productname'][$i]) {
					$i++;
					continue;
				}

				$price = $data['price'][$i];
				if ($item == '' && $data['itemdel'][$i] == '') {					
					$quotationitem  = new Quotationitem(array('productname' => $data['productname'][$i], 'MPN' => $data['mpn'][$i], 'brand_id' => $data['brand'][$i], 'unit_id' => $data['unit_id'][$i], 'quantity'=> $data['quantity'][$i], 'price'=> $price));
					$quotation->quotationitems()->save($quotationitem);
				} elseif ($item != '') {
					if ($data['itemdel'][$i] == '') {
						$quotationitem = Quotationitem::find($item);
						$quotationitem->productname = $data['productname'][$i];
						$quotationitem->mpn = $data['mpn'][$i];
						$quotationitem->brand_id = $data['brand'][$i];
						$quotationitem->unit_id = $data['unit_id'][$i];
						$quotationitem->quantity = $data['quantity'][$i];
						$quotationitem->price = $price;
						$quotationitem->save();
					} else {
						$quotationitem = Quotationitem::destroy($item);
					}
				}
				$i++;
			}
		}
        return $quotation;
	}
	
	public static function save(array $data) {
		$quotation = Quotation::find($data['id']);
		$quotation->company_id = $data['company_id'];
		$quotation->paymentterm_id = $data['paymentterm_id'];			
		$quotation->version = $quotation->version + 1;
		$quotation->vat = $data['VAT'];
		$quotation->note = $data['note'];
		$quotation->currency_id = $data['currency_id'];
		$quotation->incoterm_id = $data['incoterm_id'];
		$quotation->paymentterm_id = $data['paymentterm_id'];
		$quotation->shippingaddress_id = $data['shippingaddress_id'];
		$quotation->pickupaddress_id = $data['pickupaddress_id'];
		$quotation->deliverytype_id = $data['deliverytype_id'];
		$quotation->deliverbydate = date_create_from_format("d/m/Y",$data['deliverbydate']);
		$quotation->deliverbytime_id = $data['deliverbytime_id'];
		$quotation->pickupbydate = date_create_from_format("d/m/Y",$data['pickupbydate']);
		$quotation->pickupbytime_id = $data['pickupbytime_id'];
		if (array_key_exists("freightexpense_id",$data)) {
			$quotation->freightexpense_id = $data['freightexpense_id'];
		} else {
			$quotation->freightexpense_id = null;
		}
		$quotation->updated_by = Auth::user()->id;
		
		// Get FEEs from payment terms
		$paymentTerms = Paymentterm::where('id', $data['paymentterm_id'])->first();
		$quotation->buyup = Company::find($data['company_id'])->paymentterms()->where('paymentterm_id', $data['paymentterm_id'])->first()->pivot->buyup;

		if ($quotation->userrelation == 1) { 
			$quotation->status_id = 23;
		} else {
			$quotation->changed = 1;
		}
		$quotation->save();
		//Quotation sub data
		$i = 0;

		if ($data['itemid']) {
			foreach ($data['itemid'] as $item) {
				if (!$data['productname'][$i]) {
					$i++;
					continue;
				}

				$price = $data['price'][$i];
				if ($item == '' && $data['itemdel'][$i] == '') {					
					$quotationitem  = new Quotationitem(array('productname' => $data['productname'][$i], 'MPN' => $data['mpn'][$i], 'brand_id' => $data['brand'][$i], 'unit_id' => $data['unit_id'][$i], 'quantity'=> $data['quantity'][$i], 'price'=> $price));
					$quotation->quotationitems()->save($quotationitem);
				} elseif ($item != '') {
					if ($data['itemdel'][$i] == null) {
						$quotationitem = Quotationitem::find($item);
						if ($quotationitem->productname != $data['productname'][$i] ||
							$quotationitem->mpn != $data['mpn'][$i] ||
							$quotationitem->brand_id != $data['brand'][$i] ||
							$quotationitem->unit_id != $data['unit_id'][$i] ||
							$quotationitem->quantity != $data['quantity'][$i] ||
							$quotationitem->price != $price
						) {
							$quotationitem->productname = $data['productname'][$i];
							$quotationitem->mpn = $data['mpn'][$i];
							$quotationitem->brand_id = $data['brand'][$i];
							$quotationitem->unit_id = $data['unit_id'][$i];
							$quotationitem->quantity = $data['quantity'][$i];
							$quotationitem->price = $price;
							$quotationitem->save();
						}
					} else {
						$quotationitem = Quotationitem::destroy($item);
					}
				}
				$i++;
			}
		}
        return $quotation;
	}
}