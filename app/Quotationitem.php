<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Quotationitem extends Model implements AuditableContract
{
	use Auditable;	
	protected $guarded = array();
	
	public function quotation()
    {
        return $this->belongsTo('App\Quotation');
    }
	
	public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

	public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
	
	public function getSubtotalAttribute() {
		return $this->quantity * $this->price;
    }
    
    public function transformAudit(array $data)
    {
        $data['parent_id'] = $this->quotation_id;
        $data['parent_type'] = 'quotation';

        return $data;
    }

}
