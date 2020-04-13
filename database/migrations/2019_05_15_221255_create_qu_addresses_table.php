<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qu_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qu_id')->nullable(false)->unsigned();
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
			$table->string('delivery_address')->nullable(true);
			$table->string('delivery_city')->nullable(true);
			$table->string('delivery_country')->nullable(true);
			$table->string('delivery_inco')->nullable(true);
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
        Schema::dropIfExists('qu_addresses');
    }
}
