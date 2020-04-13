<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public function getSign() {
        switch ($this->abbreviation) {
            case "USD":
                return "$";
            default:
                return $this->abbreviation;
        }
    }
}
