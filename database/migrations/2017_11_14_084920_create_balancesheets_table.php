<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalancesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balancesheets', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('creditrequest_id');
			$table->integer('order')->nullable(false);
			$table->integer('balancesheetitem_id');
			$table->double('balancesheetitemy1')->nullable(true);
			$table->double('balancesheetitemy2')->nullable(true);
			$table->double('balancesheetitemy3')->nullable(true);
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
        Schema::dropIfExists('balancesheets');
    }
}
