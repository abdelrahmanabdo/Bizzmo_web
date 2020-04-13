<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinancialsCurrenyIdToCreditrequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditrequests', function (Blueprint $table) {
            $table->integer('financialscurrency_id')->unsigned()->nullable(false)->default(1)->after('bankstatementstart');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditrequests', function (Blueprint $table) {
            $table->dropColumn('financialscurrency_id');
        });
    }
}
