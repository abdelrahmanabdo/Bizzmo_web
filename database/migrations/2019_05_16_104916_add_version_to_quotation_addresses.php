<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersionToQuotationAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qu_addresses', function (Blueprint $table) {
            $table->integer('version')->unsigned()->nullable(false)->default(0)->after('qu_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qu_addresses', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
}
