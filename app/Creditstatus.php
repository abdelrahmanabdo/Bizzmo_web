<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditstatus extends Model
{
    const APPROVED = 1;
    const PENDING_CREDIT_DECISION = 2;
    const REJECTED = 3;
    const PENDING_RECEIPT_OF_SECURITIES = 4;
    const CONDITIONAL_APPROVAL = 5;
    const SCHEDULE_SITE_VISIT = 6;

}
