<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsQuotationItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotationitems', function (Blueprint $table) {
            $table->integer('brand_id')->unsigned()->nullable(false)->default(1);
			$table->dropColumn('brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotationitems', function (Blueprint $table) {
            $table->dropColumn('brand_id');
			$table->string('brand')->nullable(false);
        });
    }
}
