<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryFieldsToPoAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_addresses', function (Blueprint $table) {
            $table->string('delivery_address')->nullable(true);
			$table->string('delivery_city')->nullable(true);
			$table->string('delivery_country')->nullable(true);
			$table->string('delivery_inco')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_addresses', function (Blueprint $table) {
            $table->dropColumn('delivery_address');
			$table->dropColumn('delivery_city');
			$table->dropColumn('delivery_country');
			$table->dropColumn('delivery_inco');
        });
    }
}
