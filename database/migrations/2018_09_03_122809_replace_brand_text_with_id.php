<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceBrandTextWithId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop brand column
        Schema::table('purchaseorderitems', function (Blueprint $table) {
			$table->dropColumn('brand');
        });

        // Add brand_id column
        Schema::table('purchaseorderitems', function (Blueprint $table) {
			$table->integer('brand_id')->unsigned()->nullable(false)->default(1);
        });

        // Add relation to the brands table
        Schema::table('purchaseorderitems', function($table) {
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchaseorderitems', function (Blueprint $table) {
			$table->dropForeign('purchaseorderitems_brand_id_foreign');
			$table->dropColumn('brand_id');
        });

        // Add brand_id column
        Schema::table('purchaseorderitems', function (Blueprint $table) {
			$table->string('brand')->nullable(false);
        });
    }
}
