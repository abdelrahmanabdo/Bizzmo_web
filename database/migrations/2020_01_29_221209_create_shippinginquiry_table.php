<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippinginquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippinginquiries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('purchaseorder_id');
            $table->string('size');
            $table->string('volume');
            $table->boolean('boxes');
            $table->integer('status');
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('shippinginquiries');
    }
}
