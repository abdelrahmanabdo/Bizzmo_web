<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyertypeIdToTopCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companytopcustomers', function (Blueprint $table) {
            $table->integer('buyertype_id')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companytopcustomers', function (Blueprint $table) {
            $table->dropColumn('buyertype_id');
        });
    }
}
