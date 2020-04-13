<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SupportsAddCreatedAndUpdatedByCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->integer('created_by')->unsigned()->nullable();
			$table->integer('updated_by')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supports', function (Blueprint $table) {
			$table->integer('user_id')->unsigned()->nullable(true);
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
