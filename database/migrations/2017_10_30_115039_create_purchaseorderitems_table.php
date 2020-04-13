<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseorderitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseorderitems', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('purchaseorder_id')->nullable(false);
			$table->string('productname')->nullable(false);
			$table->string('mpn')->nullable(false);
			$table->string('brand')->nullable(false);
			$table->integer('unit_id')->nullable(false);
			$table->double('quantity')->nullable(false);
			$table->double('price')->nullable(false);
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
        Schema::dropIfExists('purchaseorderitems');
    }
}
