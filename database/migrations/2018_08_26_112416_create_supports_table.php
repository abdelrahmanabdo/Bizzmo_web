<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->nullable(false);
			$table->string('title')->nullable(false);
			$table->string('company')->nullable(false);
			$table->string('email')->nullable(false);
			$table->string('message')->nullable(false);
			$table->string('resolution')->nullable(true);
			$table->integer('user_id')->unsigned()->nullable(true);
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
        Schema::dropIfExists('supports');
    }
}
