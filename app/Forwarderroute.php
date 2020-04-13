<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forwarderroute extends Model
{
    //
    public function startcode()
    {
        return $this->belongsTo('App\Port_code', 'start', 'id');
    }
    public function endcode()
    {
        return $this->belongsTo('App\Port_code', 'end', 'id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
