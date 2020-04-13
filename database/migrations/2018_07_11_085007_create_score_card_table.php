<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_card', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creditrequest_id')->nullable(false)->unsigned();
            $table->integer('factor_id')->nullable(false)->unsigned();
            $table->float('weight')->nullable(false);
            $table->integer('score_id')->nullable(false)->unsigned();
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
        });

        Schema::table('score_card', function($table) {
            $table->foreign('creditrequest_id')->references('id')->on('creditrequests');
            $table->foreign('factor_id')->references('id')->on('score_factors');
            $table->foreign('score_id')->references('id')->on('scores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_card');
    }
}
