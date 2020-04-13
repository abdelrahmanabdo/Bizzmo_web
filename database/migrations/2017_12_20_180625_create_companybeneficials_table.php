<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanybeneficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companybeneficials', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id');
			$table->string('beneficialname')->nullable(false);
			$table->string('beneficialemail')->nullable(true);
			$table->string('beneficialphone')->nullable(true);
			$table->float('beneficialshare')->nullable(false);
			$table->boolean('active')->default(true);
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
        Schema::dropIfExists('companybeneficials');
    }
}
