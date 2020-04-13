<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Companyattachment extends Model implements AuditableContract
{
	use Auditable;	
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
    public function attachmenttype()
    {
        return $this->belongsTo('App\Attachmenttype');
    }
}
