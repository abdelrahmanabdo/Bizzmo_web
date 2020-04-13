<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Attachment extends Model implements AuditableContract
{
	use Auditable;	
	
	const BUYER_CONTRACT = 18;
	const SUPPLIER_CONTRACT = 17;
	const SUPPLIER_QUOTATION = 31;
	const BIZZMO_QUOTATION = 32;
	const BUYER_PO = 33;
	const BIZZMO_PO = 34;
	const BUYER_INVOICE = 15;
	const SUPPLIER_INVOICE = 16;
	const PRODUCT_IMAGE = 35;
	const BUYER_OLDCONTRACT = 38;
	const SUPPLIER_OLDCONTRACT = 37;
	const USER = 39;

	
    public function attachable()
    {
        return $this->morphTo();
    }
}
