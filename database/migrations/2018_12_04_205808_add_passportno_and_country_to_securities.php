<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassportnoAndCountryToSecurities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditrequestsecurities', function (Blueprint $table) {
            $table->string('passportno')->nullable(true);
            $table->integer('country_id')->nullable(true);
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
            $table->dropColumn('passportno');
			$table->dropColumn('country_id');
        });
    }
}
