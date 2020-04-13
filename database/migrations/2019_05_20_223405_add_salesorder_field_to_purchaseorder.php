<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesorderFieldToPurchaseorder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->integer('salesorder')->unsigned()->nullable(false)->default(0)->after('vendornumber');
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
            $table->dropColumn('salesorder');
        });
    }
}
