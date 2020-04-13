<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SupportsChangeLongTxtSizeCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->string('message', 255)->change();
            $table->string('comp_acc_info', 255)->change();
            $table->string('supp_acc_info', 255)->change();
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
            $table->string('message')->change();
            $table->string('comp_acc_info')->change();
            $table->string('supp_acc_info')->change();
        });
    }
}
