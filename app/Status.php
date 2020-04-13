<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const SUPPORT_ALL = 19;
    const SUPPORT_OPEN = 20;
    const SUPPORT_CLOSED = 21;
    const PO_PENDING_CREDIT_DECISION = 4;
    const PO_SUPPLIER_REJECTED = 5;
    const PO_CANCELLED_BY_BUYER = 6;
    const PO_PENDING_SUPPLIER_APPROVAL = 7;
    const PO_PENDING_BUYER_SUBMITTAL = 13;
    const PO_CREDIT_REJECTED = 14;
    const PO_PENDING_BUYER_POD_SIGNATURE = 15;
    const PO_DELIVERED = 16;
    const QU_PENDING_SUPPLIER_SUBMITTAL = 23;
    const QU_PENDING_BUYER_APPROVAL = 24;
    const QU_BUYER_REJECTED = 25;
    const QU_CANCELLED_BY_SUPPLIER = 26;
    const QU_COMPLETED = 27;
}
