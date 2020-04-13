<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function status()
    {
        return $this->belongsTo('App\Status');
    }
	
	public function timeslot()
    {
        return $this->belongsTo('App\Timeslot');
    }

    public function getTypeName() {
        return 'appointment';
    }
}
