<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSapDocNumInPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->string('sap_buyer_doc_number')->nullable(true);
        });

        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->string('sap_supplier_doc_number')->nullable(true);
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
            $table->dropColumn('sap_buyer_doc_number');
        });

        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->dropColumn('sap_supplier_doc_number');
        });
    }
}
