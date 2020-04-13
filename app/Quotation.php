<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

use App\Purchaseorder;
use App\Purchaseorderitem;

use App\Jobs\ProcessBuyerPO;
use App\Jobs\Processpocredit;

class Quotation extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $appends = array('canchange', 'canreleaseorder', 'canapproveorder', 'userrelation', 'isvendorchange');
	protected $protected = array();
	
	public function quotationitems()
    {
        return $this->hasMany('App\Quotationitem');
	}
	
	public function po()
    {
        return $this->belongsTo('App\Purchaseorder');
    }
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function shippingaddress()
    {
        return $this->belongsTo('App\Shippingaddress');
    }
	
	public function pickupaddress()
    {
        return $this->belongsTo('App\Pickupaddress');
    }
	
	public function deliverytype()
    {
        return $this->belongsTo('App\Deliverytype');
    }
	
	public function freightexpense()
    {
        return $this->belongsTo('App\Freightexpense');
    }
	
	public function vendor()
    {
        return $this->belongsTo('App\Company', 'vendor_id', 'id');
    }
	
	public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
	
	public function paymentterm()
    {
        return $this->belongsTo('App\Paymentterm');
    }
	
	public function incoterm()
    {
        return $this->belongsTo('App\Incoterm');
    }
	
	public function pickupbytime()
    {
        return $this->belongsTo('App\Range', 'pickupbytime_id');
    }
	
	public function deliverbytime()
    {
        return $this->belongsTo('App\Range', 'deliverbytime_id');
    }
	
	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
	
	public function status()
    {
        return $this->belongsTo('App\Status');
    }

	public function saveAddresses()
	{
		$companyData = [
			"qu_id" => $this->id,
			"version" => $this->version,
			"party_name" => $this->company->companyname,
			"city" => $this->company->city->cityname,
			"country" => $this->company->city->country->countryname,
			"address" => $this->company->address,
			"district" => $this->company->district,
			"phone" => $this->company->phone,
			"fax" => $this->company->fax,
			"tax" => $this->company->tax
		];

		// Save QU BILL_TO address
		$billTo = new QuAddress($companyData);
		$billTo->type = QuAddress::BILL_TO;
		$billTo->save();

		// Save QU PAYER address
		$payer = new QuAddress($companyData);
		$payer->type = QuAddress::PAYER;
		$payer->save();

		// Save QU SOLD_TO address
		$soldTo = new QuAddress($companyData);
		$soldTo->type = QuAddress::SOLD_TO;
		$soldTo->save();

		// Save QU SHIP_TO address
		$shippingcity = $this->shippingaddress['city'] ? $this->shippingaddress['city']['cityname'] : $this->shippingaddress['city_name'];
		$shippingcountry = $this->shippingaddress['city'] ? $this->shippingaddress['city']['country']['countryname'] : $this->shippingaddress['country_name'];
		$shippingdeliverycity = $this->shippingaddress->deliverycity->cityname;
		$shippingdeliverycountry = $this->shippingaddress->deliverycity->country->countryname;
		$shipTo = new QuAddress([
			"qu_id" => $this->id,
			"version" => $this->version,
			"type" => QuAddress::SHIP_TO,
			"party_name" => $this->shippingaddress->partyname,
			"city" => $shippingcity,
			"country" => $shippingcountry,
			"address" => $this->shippingaddress->address,
			"delivery_city" => $shippingdeliverycity,
			"delivery_country" => $shippingdeliverycountry,
			"delivery_address" => $this->shippingaddress->delivery_address,
			"delivery_inco" => $this->shippingaddress->incoterm->name,
			"po_box" => $this->shippingaddress->po_box,
			"phone" => $this->shippingaddress->phone,
			"fax" => $this->shippingaddress->fax,
			"tax" => $this->shippingaddress->tax
		]);
		$shipTo->save();
		
		// Save QU SUPPLIER address
		$shippingcity = $this->shippingaddress['city'] ? $this->shippingaddress['city']['cityname'] : $this->shippingaddress['city_name'];
		$shippingcountry = $this->shippingaddress['city'] ? $this->shippingaddress['city']['country']['countryname'] : $this->shippingaddress['country_name'];
		$shippingdeliverycity = $this->shippingaddress->deliverycity->cityname;
		$shippingdeliverycountry = $this->shippingaddress->deliverycity->country->countryname;
		$supplier = new QuAddress([
			"qu_id" => $this->id,
			"type" => QuAddress::SUPPLIER,
			"party_name" => $this->vendor->companyname,
			"city" => $this->vendor->city->cityname,
			"country" => $this->vendor->city->country->countryname,
			"address" => $this->vendor->address,
			"po_box" => $this->vendor->pobox,
			"phone" => $this->vendor->phone,
			"fax" => $this->vendor->fax,
			"tax" => $this->vendor->tax
		]);
		$supplier->save();
	}
	
	public function getBillToAddress()
	{
		return QuAddress::where(['qu_id' => $this->id, 'type' => QuAddress::BILL_TO])->orderBy('version', 'desc')->first();
	}

	public function getPayerAddress()
	{
		return QuAddress::where(['qu_id' => $this->id, 'type' => QuAddress::PAYER])->orderBy('version', 'desc')->first();
	}

	public function getSoldToAddress()
	{
		return QuAddress::where(['qu_id' => $this->id, 'type' => QuAddress::SOLD_TO])->orderBy('version', 'desc')->first();
	}

	public function getShipToAddress()
	{
		return QuAddress::where(['qu_id' => $this->id, 'type' => QuAddress::SHIP_TO])->orderBy('version', 'desc')->first();
	}
	
	public function getSupplierAddress()
	{
		return QuAddress::where(['qu_id' => $this->id, 'type' => QuAddress::SUPPLIER])->orderBy('version', 'desc')->first();
	}
	
	public function getIsvendorchangeAttribute() {
		$retval = false;
		$audits = $this->audits;
		if ($audits->count() > 0) {
			$audit = $this->audits->last();
			if (array_key_exists('status_id', $audit->old_values)) {
				if ($audit->old_values['status_id'] == 7 && $audit->new_values['status_id'] == 13) {
					$retval = true;
				}
			}
		}
		return $retval;
	}
	
	public function getUserrelationAttribute() {
		if (Auth::guest()) {
			return -1;
		} else {
			$companies = Auth::user()->companies;
			foreach ($companies as $company) {
				if ($company->id  == $this->company_id) {
					return 1;
				}
				if ($company->id  == $this->vendor_id) {
					return 2;
				}
			}
			return 0;
		}
	}
	
	public function getCanchangeAttribute() {
		if ($this->id == '') {
			return true; 
		}
		if ($this->userrelation == 2 && in_array($this->status_id, array(23))) {
			return true; 
		} elseif ($this->userrelation == 1 && in_array($this->status_id, array(24))) {	
		} else {
			return false;
		}
	}
	
	public function getCanreleaseorderAttribute() {
		if ($this->id == '') {
			return false; 
		}
		if ($this->status_id == 23) {
			return true; 
		} else {
			return false;
		}
	}
	
	public function getCanapproveorderAttribute() {
		if ($this->id == '') {
			return false; 
		}
		if ($this->status_id == 24) {
			return true; 
		} else {
			return false;
		}
	}
	
	public function getTotalAttribute() {
		return $this->quotationitems->sum('subtotal');
	}
	
	public function canCancel() {
		if ($this->getUserrelationAttribute() == 2) { // supplier
			return (($this->status_id == 23 || $this->status_id == 24)  ? true : false);
		} else {
			return ($this->status_id == 24  ? true : false);
		}		
	}
	
	public function canDelete()
	{
		$firstSubmit = true;

		$audits = $this->audits;
		if ($audits->count() > 0) {
			foreach ($audits as $audit) {
				if (array_key_exists('status_id', $audit->old_values))
					$firstSubmit = false;
			}
		}
		if ($this->getUserrelationAttribute() == 2 // supplier
			&& $this->status_id == 23 // Not submitted
			&& $firstSubmit) // No change in status
			return true;
		else
			return false;
	}
	
	public function previousversion() {
		$changes = [];
		$audits = $this->audits;
		if ($audits->count() > 0) {			
			$audit = $this->audits->last();
			if (array_key_exists('status_id', $audit->old_values)) {
				if ($audit->old_values['status_id'] == 7 && $audit->new_values['status_id'] == 13) {
					//last change was from vendor
					if (array_key_exists('incoterm_id', $audit->old_values)) {
						$changes += array('incoterm_id' => Incoterm::find($audit->old_values['incoterm_id'])->name);
					}
					if (array_key_exists('paymentterm_id', $audit->old_values)) {
						$changes += array('paymentterm_id' => Paymentterm::find($audit->old_values['paymentterm_id'])->name);
					}
					if (array_key_exists('shippingaddress_id', $audit->old_values)) {
						$changes += array('shippingaddress_id' => Shippingaddress::find($audit->old_values['shippingaddress_id'])->address);
					}
				}
			}
		}
		return $changes;
	}

	public function getTypeName() {
		return 'quotation';
	}

	public function deletedProducts() {
		return Audit::where([
			'event' => 'deleted',
			'auditable_type' => 'quotationitem',
			'parent_id' => $this->id,
			'parent_type' => 'quotation',
			'event' => 'deleted',
		])->get();
	}
	
	public function toPurchaseOrder() {
		$setting = Settings::find(Settings::SALES_ORDER);
		$today = new \DateTime();
		$purchaseOrder = new Purchaseorder;
		$purchaseOrder->number = $this->getNumber();
		$purchaseOrder->vendornumber = DB::table('purchaseorders')->max('vendornumber') + 1;
		$purchaseOrder->salesorder = $setting->SalesOrderNumber();
		$purchaseOrder->company_id = $this->company_id;
		$purchaseOrder->vendor_id = $this->vendor_id;
		$purchaseOrder->currency_id = $this->currency_id;		
		$purchaseOrder->buyup = $this->buyup;
		$purchaseOrder->shippingaddress_id = $this->shippingaddress_id;
		$purchaseOrder->pickupaddress_id = $this->pickupaddress_id;
		$purchaseOrder->deliverytype_id = $this->deliverytype_id;
		$purchaseOrder->deliverbydate = $this->deliverbydate;
		$purchaseOrder->deliverbytime_id = $this->deliverbytime_id;
		$purchaseOrder->pickupbydate = $this->pickupbydate;
		$purchaseOrder->pickupbytime_id = $this->pickupbytime_id;
		$purchaseOrder->freightexpense_id = $this->freightexpense_id;
		$purchaseOrder->note = $this->note;
		$purchaseOrder->released_by = Auth::user()->id;
		$purchaseOrder->released_at = $today->format('Y-m-d H:i:s');
		$purchaseOrder->date = $today->format('Y-m-d');
		$purchaseOrder->paymentterm_id = $this->paymentterm_id;			
		$purchaseOrder->status_id = Status::PO_PENDING_CREDIT_DECISION;
		$purchaseOrder->version = 0;
		$purchaseOrder->vat = $this->vat;			
		$purchaseOrder->incoterm_id = $this->incoterm_id;
		$purchaseOrder->created_by = $purchaseOrder->updated_by = Auth::user()->id;

		if(!$purchaseOrder->save())
			return null;

		foreach ($this->quotationitems as $quotationItem) {

			$purchaseOrderItem = new Purchaseorderitem([
				'productname' => $quotationItem->productname, 
				'MPN' => $quotationItem->mpn, 
				'brand_id' => $quotationItem->brand_id, 
				'unit_id' => $quotationItem->unit_id, 
				'quantity'=> $quotationItem->quantity,
				'price'=> $quotationItem->price
				]);
			$purchaseOrder->purchaseorderitems()->save($purchaseOrderItem);
		}
		$purchaseOrder->saveAddresses();
		$purchaseOrder->createAttachment();
		Processpocredit::dispatch($purchaseOrder);
		ProcessBuyerPO::dispatch($purchaseOrder);
		return $purchaseOrder;
	}

	private function getNumber() {
		$number = DB::table('purchaseorders')->where('company_id', $this->company_id)->max('number');
		if(!$number)
			return 1;
		else
			return $number + 1;
	}
}
