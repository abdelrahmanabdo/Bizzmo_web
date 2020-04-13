<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use File;
use PDF;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

class Purchaseorder extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $appends = array('canchange', 'canreleaseorder', 'canapproveorder', 'userrelation', 'isvendorchange');
	
	public function purchaseorderitems()
    {
        return $this->hasMany('App\Purchaseorderitem');
    }
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function deliverytype()
    {
        return $this->belongsTo('App\Deliverytype');
    }
	
	public function freightexpense()
    {
        return $this->belongsTo('App\Freightexpense');
    }
	
	public function poaddresses()
    {
        return $this->hasMany('App\PoAddress', 'po_id');
    }
	
	public function shippingaddress()
    {
        return $this->belongsTo('App\Shippingaddress');
	}
	public function shippinginquiry()
    {
        return $this->hasMany('App\Shippinginquiry','purchaseorder_id');
    }
	
	public function pickupaddress()
    {
        return $this->belongsTo('App\Pickupaddress');
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
	
	public function vendorpaymentterm()
    {
        return $this->belongsTo('App\Paymentterm', 'vendorterm_id');
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
		if (in_array($this->status_id, array(13))) {
			return true; 
		} else {
			return false;
		}
	}
	
	public function getCanreleaseorderAttribute() {
		if ($this->id == '') {
			return false; 
		}
		if ($this->status_id == 13) {
			return true; 
		} else {
			return false;
		}
	}
	
	public function getCanapproveorderAttribute() {
		if ($this->id == '') {
			return false; 
		}
		if ($this->status_id == 7) {
			return true; 
		} else {
			return false;
		}
	}
	
	public function getTotalAttribute() {
		return $this->purchaseorderitems->sum('subtotal');
	}

	public function getGrandTotalAttribute() {
		$total = $this->purchaseorderitems->sum('subtotal');
		$fees = 1 * number_format($total * $this->buyup / 100, 2, '.', '');
		$vat = 1 * number_format(($total + $fees) * $this->vat / 100, 2, '.', '');
		$grandTotal = $total + $fees + $vat;

		return $grandTotal;
	}

	public function canCancel() {
		return ($this->signed_at ? false : true) && ($this->status_id == 6  ? false : true);
	}

	public function canResubmit() {
		return ($this->status_id == 14  ? true : false);
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

		if ($this->getUserrelationAttribute() == 1 // buyer
			&& $this->status_id == 13 // Not submitted
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
					if (array_key_exists('pickupaddress_id', $audit->old_values)) {
						$changes += array('pickupaddress_id' => Pickupaddress::find($audit->old_values['pickupaddress_id'])->address);
					}
				}
			}
		}
		return $changes;
	}

	public function saveAddresses()
	{
		$companyData = [
			"po_id" => $this->id,
			"party_name" => $this->company->companyname,
			"city" => $this->company->city->cityname,
			"country" => $this->company->city->country->countryname,
			"address" => $this->company->address,
			"district" => $this->company->district,
			"phone" => $this->company->phone,
			"fax" => $this->company->fax,
			"tax" => $this->company->tax
		];

		// Save PO BILL_TO address
		$billTo = new PoAddress($companyData);
		$billTo->type = PoAddress::BILL_TO;
		$billTo->save();

		// Save PO PAYER address
		$payer = new PoAddress($companyData);
		$payer->type = PoAddress::PAYER;
		$payer->save();

		// Save PO SOLD_TO address
		$soldTo = new PoAddress($companyData);
		$soldTo->type = PoAddress::SOLD_TO;
		$soldTo->save();

		// Save PO SHIP_TO address
		$shippingcity = $this->shippingaddress['city'] ? $this->shippingaddress['city']['cityname'] : $this->shippingaddress['city_name'];
		$shippingcountry = $this->shippingaddress['city'] ? $this->shippingaddress['city']['country']['countryname'] : $this->shippingaddress['country_name'];
		$shippingdeliverycity = $this->shippingaddress->deliverycity->cityname;
		$shippingdeliverycountry = $this->shippingaddress->deliverycity->country->countryname;
		$shipTo = new PoAddress([
			"po_id" => $this->id,
			"type" => PoAddress::SHIP_TO,
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
		
		// Save PO SUPPLIER address
		$shippingcity = $this->shippingaddress['city'] ? $this->shippingaddress['city']['cityname'] : $this->shippingaddress['city_name'];
		$shippingcountry = $this->shippingaddress['city'] ? $this->shippingaddress['city']['country']['countryname'] : $this->shippingaddress['country_name'];
		$shippingdeliverycity = $this->shippingaddress->deliverycity->cityname;
		$shippingdeliverycountry = $this->shippingaddress->deliverycity->country->countryname;
		$supplier = new PoAddress([
			"po_id" => $this->id,
			"type" => PoAddress::SUPPLIER,
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
		return PoAddress::where(['po_id' => $this->id, 'type' => PoAddress::BILL_TO])->orderBy('version', 'desc')->first();
	}

	public function getPayerAddress()
	{
		return PoAddress::where(['po_id' => $this->id, 'type' => PoAddress::PAYER])->orderBy('version', 'desc')->first();
	}

	public function getSoldToAddress()
	{
		return PoAddress::where(['po_id' => $this->id, 'type' => PoAddress::SOLD_TO])->orderBy('version', 'desc')->first();
	}

	public function getShipToAddress()
	{
		return PoAddress::where(['po_id' => $this->id, 'type' => PoAddress::SHIP_TO])->orderBy('version', 'desc')->first();
	}

	public function getSupplierAddress()
	{
		return PoAddress::where(['po_id' => $this->id, 'type' => PoAddress::SUPPLIER])->orderBy('version', 'desc')->first();
	}
	
	public function getTypeName() {
		return 'purchase order';
	}

	public function deletedProducts() {
		return Audit::where([
			'event' => 'deleted',
			'auditable_type' => 'purchaseorderitem',
			'parent_id' => $this->id,
			'parent_type' => 'purchaseorder',
			'event' => 'deleted',
		])->get();
	}
	public function getQuotationAttribute() {
		return Quotation::where('po_id', $this->id)->first();
	}
	
	public function createAttachment() {
		//Buyer PO
		$path = str_replace('\\', '/', storage_path()) . '/app/buyerpo/' . date('Y') . '/' . date('m') . '/';
		if (!File::exists($path)) {
			File::makeDirectory($path, 0777, true, true);
		}

		// Save PDF
		$filename = uniqid() . 'bupo-' . $this->id . '.pdf';
		$pdf = PDF::loadView('pdfs/buyerpo', ['purchaseOrder' => $this]);
		$pdf->save($path . $filename);

		//Delete attachment of same version
		DB::table('attachments')->where('attachable_id', $this->id)->where('attachmenttype_id', Attachment::BUYER_PO)
			->where('attachable_type', 'purchaseorder')->where('version', $this->version)->delete();
		// Save attachment
		$attachment = new Attachment;
		$attachment->path = 'buyerpo/' . date('Y') . '/' . date('m') . '/' . $filename;
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Buyer PO';
		$attachment->attachable_type = 'purchaseorder';
		$attachment->attachable_id = $this->id;
		$attachment->version = $this->version;
		$attachment->attachmenttype_id = Attachment::BUYER_PO;
		$attachment->filename = $filename;
		$attachment->save();
		
		//Bizzmo PO
		$path = str_replace('\\', '/', storage_path()) . '/app/bizzmopo/' . date('Y') . '/' . date('m') . '/';
		if (!File::exists($path)) {
			File::makeDirectory($path, 0777, true, true);
		}

		// Save PDF
		$filename = uniqid() . 'bipo-' . $this->id . '.pdf';
		$pdf = PDF::loadView('pdfs/bizzmopo', ['purchaseOrder' => $this]);
		$pdf->save($path . $filename);

		//Delete attachment of same version
		DB::table('attachments')->where('attachable_id', $this->id)->where('attachmenttype_id', Attachment::BIZZMO_PO)
			->where('attachable_type', 'purchaseorder')->where('version', $this->version)->delete();
		// Save attachment
		$attachment = new Attachment;
		$attachment->path = 'bizzmopo/' . date('Y') . '/' . date('m') . '/' . $filename;
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Bizzmo PO';
		$attachment->attachable_type = 'purchaseorder';
		$attachment->attachable_id = $this->id;
		$attachment->version = $this->version;
		$attachment->attachmenttype_id = Attachment::BIZZMO_PO;
		$attachment->filename = $filename;
		$attachment->save();
	}
}
