<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditAssessmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creditrequest_id')->nullable(false)->unsigned();
            $table->string('prepared_by')->nullable(false);
            $table->string('approved_by')->nullable(false);
            $table->date('date_of_assessment')->nullable(false);
            $table->text('company_background')->nullable(false);
            $table->text('key_financials_developments')->nullable(false);
            $table->text('key_risks')->nullable(false);
            $table->text('mitigating_factors')->nullable(false);
            $table->double('heighest_balance')->nullable(false);
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
        });

        Schema::table('credit_assessment', function($table) {
            $table->foreign('creditrequest_id')->references('id')->on('creditrequests');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_assessment');
    }
}
