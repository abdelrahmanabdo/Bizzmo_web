<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFreightExpenseIdToPurchaseorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->integer('freightexpense_id')->unsigned()->nullable(true)->after('pickupbytime_id');
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
            $table->dropColumn('freightexpense_id');            
        });
    }
}
