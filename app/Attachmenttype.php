<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachmenttype extends Model
{
    const SUPPLIER_CONTRACT = 17;
    const BUYER_CONTRACT = 18;
    const SIGNED_DELIVERY_DOCUMENT = 19;
    const TAX_CERTIFICATE = 20;
    const PERSONAL_GURANTEE_DOCUMENT = 21;
    const CORPORATE_GURANTEE_DOCUMENT = 22;
    const PROMISSORY_NOTE_DOCUMENT = 23;
	const CHECK_AUTH_DOCUMENT = 36;
}
