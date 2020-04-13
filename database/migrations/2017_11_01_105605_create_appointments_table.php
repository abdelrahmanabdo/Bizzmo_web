<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('timeslot_id')->nullable(false);
			$table->date('date')->nullable(false); 
			$table->string('description')->nullable(false);
			$table->string('appointmenttype_id')->nullable(false);
			$table->integer('company_id')->nullable(false);
			$table->integer('status_id')->nullable(false);
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
        Schema::dropIfExists('appointments');
    }
}
