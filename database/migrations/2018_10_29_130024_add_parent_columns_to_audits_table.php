<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentColumnsToAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Config::get('audit.drivers.database.table'), function(Blueprint $table) {
            $table->string('parent_id')->nullable();
            $table->string('parent_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Config::get('audit.drivers.database.table'), function(Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('parent_type');
        });
    }
}
