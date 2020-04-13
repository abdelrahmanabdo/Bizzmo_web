<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactColsToCreditrequestbusrefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditrequestbusrefs', function (Blueprint $table) {
            $table->string('contact_name')->nullable(false);
            $table->string('contact_mobile')->nullable(false);
            $table->string('contact_email')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditrequestbusrefs', function (Blueprint $table) {
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_mobile');
            $table->dropColumn('contact_email');
        });
    }
}
