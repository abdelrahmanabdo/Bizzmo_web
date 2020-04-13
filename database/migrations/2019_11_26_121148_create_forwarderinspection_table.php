<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForwarderinspectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forwarderinspections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id');
            $table->string('name');
            $table->string('type');
            $table->string('value');
            $table->boolean('required');
            $table->boolean('active');
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
        Schema::dropIfExists('forwarderinspections');
    }
}
