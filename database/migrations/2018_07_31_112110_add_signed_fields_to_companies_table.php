<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignedFieldsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('vendor_signed')->default(false)->nullable(false)->after('tenant_id');
            $table->boolean('customer_signed')->default(false)->nullable(false)->after('vendor_signed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('vendor_signed');
            $table->dropColumn('customer_signed');
        });
    }
}
