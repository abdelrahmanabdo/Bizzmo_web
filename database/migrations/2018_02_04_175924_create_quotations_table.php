<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('number')->nullable(false);
			$table->integer('vendornumber')->nullable(true);
            $table->date('date')->nullable(false); 
			$table->integer('company_id')->nullable(false);
			$table->integer('vendor_id')->nullable(false);
			$table->integer('currency_id')->nullable(false);
			$table->integer('paymentterm_id')->nullable(false);
			$table->float('buyup')->nullable(false);
			$table->string('shippingaddress_id');
			$table->string('note')->nullable(true);
			$table->integer('approver_id')->nullable(true);
			$table->dateTime('approved_at')->nullable(true);
			$table->integer('released_by')->nullable(true);
			$table->dateTime('released_at')->nullable(true);
			$table->integer('status_id')->nullable(false);
			$table->integer('incoterm_id')->nullable(false);
			$table->integer('delivery')->nullable(true);
			$table->date('deliverydate')->nullable(true); 
			$table->integer('binvoice')->nullable(true);
			$table->date('binvoicedate')->nullable(true); 
			$table->integer('sinvoice')->nullable(true);
			$table->date('sinvoicedate')->nullable(true); 
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
