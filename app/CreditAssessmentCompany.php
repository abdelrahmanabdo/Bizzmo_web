<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditAssessmentCompany extends Model
{
	public $table = 'credit_assessment_companies';
	
    public function creditRequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
	
	public function companyrelationtype()
    {
        return $this->belongsTo('App\Companyrelationtype');
    }
}
