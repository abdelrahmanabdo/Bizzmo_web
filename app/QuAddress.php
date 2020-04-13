<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class QuAddress extends Model
{	
	protected $fillable = ['qu_id', 'type', 'party_name', 'city', 'country', 'address', 'district', 'po_box', 'phone', 'fax', 'tax', 'delivery_city', 'delivery_country', 'delivery_address', 'delivery_inco'];

	// Addresses types
	public const BILL_TO = "BILL_TO";
	public const SHIP_TO = "SHIP_TO";
	public const PAYER = "PAYER";
	public const SOLD_TO = "SOLD_TO";
	public const SUPPLIER = "SUPPLIER";

	public function quotation()
    {
        return $this->belongsTo('App\Quotation');
    }
}
