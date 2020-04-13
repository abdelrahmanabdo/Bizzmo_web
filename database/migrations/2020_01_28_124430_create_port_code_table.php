<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('PointCode')->nullable(true);
            $table->string('PointName')->nullable(true);
            $table->string('PortCode')->nullable(true);
            $table->string('PortName')->nullable(true);
            $table->string('CountryCode')->nullable(true);
            $table->string('CountryName')->nullable(true);
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
        Schema::dropIfExists('port_codes');
    }
}
