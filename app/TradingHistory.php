<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradingHistory extends Model
{
    public $table = 'trading_history';

    public function creditRequest()
    {
        return $this->belongsTo('App\Creditrequest');
    }
}
