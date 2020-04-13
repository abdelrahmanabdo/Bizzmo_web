<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actiontokens', function (Blueprint $table) {
            $table->increments('id');
			$table->string('action')->nullable(false);
			$table->string('token')->nullable(false);
			$table->integer('object_id')->nullable(false)->unsigned();
			$table->dateTime('expiry')->nullable(false);
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
        Schema::dropIfExists('actiontokens');
    }
}
