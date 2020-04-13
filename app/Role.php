<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $guarded = array();
	
    public function permissions()
    {
        return $this->belongsToMany('App\Permission')->withTimestamps();
    }
	
	public function users()
    {
		return $this->belongsToMany('App\User')->withTimestamps();
    }
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
}
