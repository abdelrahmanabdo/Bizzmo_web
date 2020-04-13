<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('supplier_id')->unsigned()->comment('The company id');
            $table->integer('buyer_id')->unsigned()->comment('Current logged in user');
            $table->integer('qty')->default(1);
            $table->float('price');
            $table->boolean('discount')->default(false);
            $table->integer('discount_value')->default(0);
            $table->string('status')->default('waiting')->comment("waiting , canceled , in deliver");
            $table->timestamps();

            // Relations
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry');
    }
}
