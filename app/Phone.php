<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
  public $table = 'phone';  

  public function user()
  {
      return $this->belongsTo('App\User');
  }

  public function verified()
	{
		$this->verified = true;
		$this->save();
	}
}
