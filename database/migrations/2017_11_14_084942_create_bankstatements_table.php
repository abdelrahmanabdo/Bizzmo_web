<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankstatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankstatements', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('creditrequest_id');
			$table->string('title');
			$table->integer('order')->nullable(false);
			$table->double('Month1D')->nullable(true);
			$table->double('Month2D')->nullable(true);
			$table->double('Month3D')->nullable(true);
			$table->double('Month4D')->nullable(true);
			$table->double('Month5D')->nullable(true);
			$table->double('Month6D')->nullable(true);
			$table->double('Month1C')->nullable(true);
			$table->double('Month2C')->nullable(true);
			$table->double('Month3C')->nullable(true);
			$table->double('Month4C')->nullable(true);
			$table->double('Month5C')->nullable(true);
			$table->double('Month6C')->nullable(true);
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
        Schema::dropIfExists('bankstatements');
    }
}
