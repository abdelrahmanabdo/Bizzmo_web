<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickupAndDeliveryTimesToPo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->integer('pickupbytime_id')->nullable(true)->after('deliverytype_id');			
			$table->date('pickupbydate')->nullable(true)->after('deliverytype_id'); 			
			$table->integer('deliverbytime_id')->nullable(true)->after('deliverytype_id');			
			$table->date('deliverbydate')->nullable(true)->after('deliverytype_id');			
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
            $table->dropColumn('pickupbytime_id');
			$table->dropColumn('pickupbydate');
			$table->dropColumn('deliverbytime_id');
			$table->dropColumn('deliverbydate');
        });
    }
}
