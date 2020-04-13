<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoreCard extends Model
{
  public $table = 'score_card';

  public function creditRequest()
  {
      return $this->belongsTo('App\Creditrequest');
  }

  public function factor()
  {
      return $this->belongsTo('App\ScoreFactor');
  }

  public function score()
  {
      return $this->belongsTo('App\Score');
  }
}
