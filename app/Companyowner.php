<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Companyowner extends Model implements AuditableContract
{
	use Auditable;
	protected $guarded = array();
	
    public function company()
	{
		return $this->belongsTo('App\Company');
	}
	
	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
}
