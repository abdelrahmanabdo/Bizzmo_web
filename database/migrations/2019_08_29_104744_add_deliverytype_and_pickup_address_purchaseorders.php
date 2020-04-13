<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliverytypeAndPickupAddressPurchaseorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->integer('deliverytype_id')->unsigned()->nullable(false)->default(1)->after('deliverydate');
			$table->integer('pickupaddress_id')->unsigned()->nullable(false)->after('shippingaddress_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->dropColumn('deliverytype_id');
			$table->dropColumn('pickupaddress_id');
        });
    }
}
