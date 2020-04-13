<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
			$table->string('companyname')->nullable(false);
			$table->string('address')->nullable(false);
			$table->string('district')->nullable(true);
			$table->integer('city_id')->nullable(false);
			$table->integer('country_id')->nullable(false);
			$table->integer('companytype_id')->nullable(false);
			$table->string('pobox')->nullable(true);
			$table->string('email')->nullable(false);
			$table->string('phone')->nullable(false);
			$table->string('fax')->nullable(false);
			$table->string('license')->nullable(false);
			$table->string('tax')->nullable(false);
			$table->string('vatno')->nullable(true);
			$table->double('vat')->nullable(true)->default(5);
			$table->date('incorporated')->nullable(false); 
			$table->integer('employees');
			$table->double('creditlimit')->default(0);
			$table->double('margin')->default(0);
			$table->double('payment')->default(0);
			$table->string('website')->nullable(true);
			$table->string('operating')->nullable(true);
			$table->string('accountname')->nullable(true);
			$table->string('bankname')->nullable(true);
			$table->string('accountnumber')->nullable(true);
			$table->string('iban')->nullable(true);
			$table->string('routingcode')->nullable(true);
			$table->string('swift')->nullable(true);
			$table->string('otp')->nullable(true);			
			$table->string('sapnumber')->nullable(true);			
			$table->string('sapvendornumber')->nullable(true);			
			$table->boolean('active')->default(false);
			$table->boolean('confirmed')->default(false);
			$table->boolean('sameowner')->default(1);
			$table->boolean('basicinfo')->default(0);
			$table->boolean('shareholders')->default(0);
			$table->boolean('beneficialowners')->default(0);
			$table->boolean('directors')->default(0);
			$table->boolean('business')->default(0);
			$table->boolean('banks')->default(0);
			$table->integer('tenant_id')->unsigned()->nullable(true);
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
        Schema::dropIfExists('companies');
    }
}
