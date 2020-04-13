<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
   const BUYER_INVOICE = 3;
   const SALES_ORDER = 4;
   
   public function buyerInvoiceNumber() {
	   $inv_no = intval($this->value) + 1;
	   $this->value = $inv_no;
	   $this->save();
	   return $inv_no;
   }
   
   public function SalesOrderNumber() {
	   $so_no = intval($this->value) + 1;
	   $this->value = $so_no;
	   $this->save();
	   return $so_no;
   }
}
