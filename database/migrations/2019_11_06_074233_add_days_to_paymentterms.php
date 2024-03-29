<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDaysToPaymentterms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentterms', function (Blueprint $table) {
            $table->integer('days')->unsigned()->nullable(false)->default(0)->after('sappaymentterm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentterms', function (Blueprint $table) {
            $table->dropColumn('days');
        });
    }
}
