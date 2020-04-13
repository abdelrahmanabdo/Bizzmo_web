<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersionToPoAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_addresses', function (Blueprint $table) {
            $table->integer('version')->unsigned()->nullable(false)->default(0)->after('po_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_addresses', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
}
