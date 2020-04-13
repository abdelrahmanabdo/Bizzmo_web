<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trading_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creditrequest_id')->nullable(false)->unsigned();
            $table->string('quarter')->nullable(false);
            $table->double('sales')->nullable(false);
            $table->double('payments')->nullable(false);
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
        });

        Schema::table('trading_history', function($table) {
            $table->foreign('creditrequest_id')->references('id')->on('creditrequests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trading_history');
    }
}
