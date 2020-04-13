<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShippingAddressTableChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippingaddresses', function (Blueprint $table) {
			$table->string('partyname')->nullable(false);
			$table->string('phone')->nullable(false);
			$table->string('fax')->nullable(false);
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->boolean('vat')->default(1);
			$table->boolean('vatexempt')->default(0);
			$table->integer('exempt_by')->unsigned()->nullable(true);
			$table->boolean('default')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippingaddresses', function (Blueprint $table) {
			$table->dropColumn('partyname');
			$table->dropColumn('phone');
			$table->dropColumn('fax');
            $table->dropColumn('created_by');
			$table->dropColumn('updated_by');
			$table->dropColumn('vat');
			$table->dropColumn('vatexempt');
			$table->dropColumn('exempt_by');	
			$table->dropColumn('default');	
        });
    }
}
