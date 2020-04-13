<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForwarderRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forwarderroutes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start')->nullable(false);
            $table->string('end')->nullable(false);
            $table->integer('company_id')->nullable(false);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('forwarderroutes');
    }
}
