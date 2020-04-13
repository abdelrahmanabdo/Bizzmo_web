<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCompanyVendorColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_vendor', function(Blueprint $table) {
            $table->renameColumn('company_id', 'owner_company');
            $table->renameColumn('vendor_id', 'favourite_company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_vendor', function(Blueprint $table) {
            $table->renameColumn('owner_company', 'company_id');
            $table->renameColumn('favourite_company', 'vendor_id');
        });
    }
}
