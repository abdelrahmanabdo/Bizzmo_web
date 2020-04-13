<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
  public function user()
  {
      return User::where('id', $this->user_id)->first();
  }
}
