<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Status;

class Support extends Model
{
	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getStatusNameAttribute() {
		switch ($this->status_id) {
			case Status::SUPPORT_CLOSED:
				return 'Closed';
				
			case Status::SUPPORT_OPEN:
				return 'Open';
		}
    }

    public function getIssuerNameAttribute() {
        if ($this->name)
            return $this->name;

        if ($this->created_by) {
            $user = User::findOrFail($this->created_by);
            return $user->name;
        }

        return null;
    }

    public function getIssuerEmailAttribute() {
        if ($this->email)
            return $this->email;

        if ($this->created_by) {
            $user = User::findOrFail($this->created_by);
            return $user->email;
        }

        return null;
    }
    
    public function isOpen() {
        return $this->status_id == Status::SUPPORT_OPEN;
    }

}
