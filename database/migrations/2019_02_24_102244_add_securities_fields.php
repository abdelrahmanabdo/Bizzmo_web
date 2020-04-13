<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecuritiesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditrequestsecurities', function (Blueprint $table) {
            //
            $table->string('company_name')->nullable(true);
            $table->string('commercial_register')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('company_owner')->nullable(true);
            $table->string('designation')->nullable(true);
            //$table->boolean('vendor_signed')->default(false)->nullable(false)->after('tenant_id');
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
            //
            $table->dropColumn('company_name');
            $table->dropColumn('commercial_register');
            $table->dropColumn('address');
            $table->dropColumn('company_owner');
            $table->dropColumn('designation');
        });
    }
}
