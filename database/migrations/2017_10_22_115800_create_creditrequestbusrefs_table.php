<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditrequestbusrefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditrequestbusrefs', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('creditrequest_id');
			$table->string('busrefname')->nullable(false);
			$table->double('busreflimit')->nullable(false);
			$table->string('busreftype')->nullable(false);
			$table->integer('busreflength')->nullable(false);
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
        Schema::dropIfExists('creditrequestbusrefs');
    }
}
