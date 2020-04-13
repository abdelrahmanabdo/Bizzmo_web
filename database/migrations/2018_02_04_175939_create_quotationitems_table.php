<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotationitems', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('quotation_id')->nullable(false);
			$table->string('productname')->nullable(false);
			$table->string('mpn')->nullable(false);
			$table->string('brand')->nullable(false);
			$table->integer('unit_id')->nullable(false);
			$table->float('quantity')->nullable(false);
			$table->float('price')->nullable(false);
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
        Schema::dropIfExists('quotationitems');
    }
}
