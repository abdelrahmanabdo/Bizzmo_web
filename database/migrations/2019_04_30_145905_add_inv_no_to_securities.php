<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvNoToSecurities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditrequestsecurities', function (Blueprint $table) {
            $table->integer('inv_no')->nullable(true);
			$table->string('sap_fi_doc_no')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditrequestsecurities', function (Blueprint $table) {
            $table->dropColumn('inv_no');            
			$table->dropColumn('sap_fi_doc_no');            
        });
    }
}
