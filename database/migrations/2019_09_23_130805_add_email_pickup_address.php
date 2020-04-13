<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailPickupAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickupaddresses', function (Blueprint $table) {
            $table->string('email')->nullable(true)->after('fax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickupaddresses', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}
