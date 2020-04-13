<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippingaddresses', function (Blueprint $table) {
            $table->string('country_name')->nullable(true);
            $table->string('city_name')->nullable(true);
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
            $table->dropColumn('country_name');
            $table->dropColumn('city_name');
        });
    }
}
