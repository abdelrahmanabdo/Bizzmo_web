<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SupportsAddCustomerSupportCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->integer('order_number')->unsigned()->nullable();
            $table->string('comp_acc_info')->nullable();
            $table->string('supp_acc_info')->nullable();
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
            $table->dropColumn('order_number');
			$table->dropColumn('comp_acc_info');
			$table->dropColumn('supp_acc_info');
        });
    }
}
