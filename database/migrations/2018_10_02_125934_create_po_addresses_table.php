<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('po_id')->nullable(false)->unsigned();
            $table->string('type'); /** BILL_TO, SHIP_TO, PAYER, SOLD_TO */
            $table->string('party_name');
            $table->string('city');
            $table->string('country');
            $table->string('address');
            $table->string('district')->nullable(true);
            $table->string('po_box')->nullable(true);
            $table->string('phone');
            $table->string('fax');
            $table->string('tax')->nullable(true);
            $table->timestamps();
        });

         // Add relation to the purchaseorders table
         Schema::table('po_addresses', function($table) {
            $table->foreign('po_id')->references('id')->on('purchaseorders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('po_addresses');
    }
}
