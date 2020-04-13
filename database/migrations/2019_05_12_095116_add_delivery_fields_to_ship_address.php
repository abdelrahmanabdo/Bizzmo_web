<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryFieldsToShipAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippingaddresses', function (Blueprint $table) {
            $table->string('delivery_address')->nullable(true);
			$table->integer('delivery_city_id')->nullable(true);
			$table->integer('incoterm_id')->nullable(true);
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippingaddresses', function (Blueprint $table) {
            $table->dropColumn('delivery_address');
			$table->dropColumn('delivery_city_id');
			$table->dropColumn('incoterm_id');			
        });
    }
}
