<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditAssessmentCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_assessment_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creditrequest_id')->nullable(false)->unsigned();
            $table->string('company_name')->nullable(false);
            $table->integer('companyrelationtype_id')->nullable(false)->unsigned();
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
        });

        Schema::table('credit_assessment_companies', function($table) {
            $table->foreign('creditrequest_id')->references('id')->on('creditrequests');
            $table->foreign('companyrelationtype_id')->references('id')->on('company_relation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_assessment_companies');
    }
}
