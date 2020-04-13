<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTookanIdToCreditrequestsecurities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditrequestsecurities', function (Blueprint $table) {
			$table->integer('pickupbytime_id')->nullable(true);			
			$table->date('pickupbydate')->nullable(true); 			
            $table->integer('tookandelivery_id')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditrequestsecurities', function (Blueprint $table) {
            $table->dropColumn('pickupbytime_id');
			$table->dropColumn('pickupbydate');
			$table->dropColumn('tookandelivery_id');
        });
    }
}
