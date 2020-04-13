<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomestatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomestatements', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('creditrequest_id');
			$table->integer('order')->nullable(false);
			$table->integer('incomestatementitem_id');
			$table->double('incomestatementitemy1')->nullable(true);
			$table->double('incomestatementitemy2')->nullable(true);
			$table->double('incomestatementitemy3')->nullable(true);
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
        Schema::dropIfExists('incomestatements');
    }
}
