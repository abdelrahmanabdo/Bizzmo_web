<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPaymenttermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_paymentterm', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id')->unsigned();
			$table->integer('paymentterm_id')->unsigned();
			$table->float('buyup')->nullable(false);
			$table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_paymentterm');
    }
}
