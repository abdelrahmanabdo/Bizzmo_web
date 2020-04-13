<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditrequestSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditrequestsecurities', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('creditrequest_id')->unsigned();
			$table->integer('securitytype_id')->unsigned();
			$table->string('signername')->nullable(true);
			$table->string('signeremail')->nullable(true);
			$table->float('amount')->nullable(true);
			$table->string('authcode')->nullable(true);
			$table->string('envelope')->nullable(true);
			$table->string('document')->nullable(true);
			$table->string('document_id')->nullable(true);
			$table->string('status')->nullable(true);
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
        Schema::dropIfExists('creditrequestsecurities');
    }
}
