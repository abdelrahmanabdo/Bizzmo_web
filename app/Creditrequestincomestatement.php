<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditrequestincomestatement extends Model
{
	protected $guarded = array();
	protected $table = 'incomestatements';
	
    public function creditrequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
	
	public function incomestatementitem()
    {
        return $this->belongsTo('App\Incomestatementitem');
    }
}
