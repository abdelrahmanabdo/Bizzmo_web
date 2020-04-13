<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditrequestsecurity extends Model
{
	protected $guarded = array();
	
    public function creditrequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
	
	public function securitytype()
    {
        return $this->belongsTo('App\Securitytype');
    }
    public function country()
    {
        return $this->belongsTo('App\Country');
    }
    
	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
	
    public function attachment($downloadedOnly = false) {
        if($downloadedOnly)
            $attachment = Attachment::where(['id' => $this->document_id, 'status' => "Signed & Downloaded"])->first();
        else
            $attachment = Attachment::where('id', $this->document_id)->first();
        return $attachment;
    }
}
