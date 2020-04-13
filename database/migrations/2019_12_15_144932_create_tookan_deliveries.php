<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTookanDeliveries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tookandeliveries', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('job_id')->nullable(true);
			$table->integer('pickup_job_id')->nullable(true);			
			$table->integer('delivery_job_id')->nullable(true);		
			$table->string('pickup_tracking_link')->nullable(true);			
			$table->string('delivery_tracking_link')->nullable(true);            
			$table->string('delivery_status')->nullable(true);
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
        Schema::dropIfExists('tookandeliveries');
    }
}
