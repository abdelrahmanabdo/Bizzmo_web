<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanydirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companydirectors', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id');
			$table->string('directorname')->nullable(false);
			$table->string('directortitle')->nullable(false);
			$table->string('directoremail')->nullable(true);
			$table->string('directorphone')->nullable(true);
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
        Schema::dropIfExists('companydirectors');
    }
}
