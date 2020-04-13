<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickupaddresses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id')->nullable(false);
			$table->string('partyname')->nullable(false);			
			$table->string('address');
			$table->string('district')->nullable(true);
			$table->string('city_id');			
			$table->string('phone')->nullable(false);
			$table->string('fax')->nullable(false);
			$table->string('po_box')->nullable(true);
			$table->boolean('default')->default(0);
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
        Schema::dropIfExists('pickupaddresses');
    }
}
