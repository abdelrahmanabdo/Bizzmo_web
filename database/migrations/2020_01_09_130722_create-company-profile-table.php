<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profile', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id');
            $table->string('address')->nullable();
            $table->string('pobox')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('customers_number')->default(0);
            $table->string('employees_number')->default(0);
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->longText('overview')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_profile', function (Blueprint $table) {
            Schema::dropIfExists('company_profile');
        });
    }
}
