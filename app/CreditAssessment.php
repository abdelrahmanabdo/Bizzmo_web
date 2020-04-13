<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditAssessment extends Model
{
    public $table = 'credit_assessment';

    public function creditRequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
}
