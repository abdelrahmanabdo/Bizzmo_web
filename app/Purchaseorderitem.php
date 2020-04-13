<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Purchaseorderitem extends Model implements AuditableContract
{
	use Auditable;	
	protected $guarded = array();
	
	public function purchaseorder()
    {
        return $this->belongsTo('App\Purchaseorder');
    }
	
	public function unit()
    {
        return $this->belongsTo('App\Unit');
    }
	
	public function getSubtotalAttribute() {
		return $this->quantity * $this->price;
	}

	public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function transformAudit(array $data)
    {
        $data['parent_id'] = $this->purchaseorder_id;
        $data['parent_type'] = 'purchaseorder';

        return $data;
    }
}
