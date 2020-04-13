<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangedFieldToPurchaseorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->integer('vendorterm_id')->unsigned()->nullable(false)->default(6)->after('buyup');
			$table->boolean('changed')->default(false)->after('buyup');
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
            $table->dropColumn('vendorterm_id');
			$table->dropColumn('changed');
        });
    }
}
