<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyownersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companyowners', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id');
			$table->string('ownername')->nullable(false);
			$table->string('owneremail')->nullable(true);
			$table->string('ownerphone')->nullable(true);
			$table->float('ownershare')->nullable(false);
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
        Schema::dropIfExists('companyowners');
    }
}
