<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditrequests', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id')->nullable(false);
			$table->integer('requesttype_id')->nullable(false);
			$table->integer('tenor_id')->nullable(false);
			$table->double('limit')->unsigned();
			$table->double('askedlimit')->unsigned()->default(0);
			$table->string('justification')->nullable(false);
			$table->integer('margindeposittype_id')->nullable(true);
			$table->double('margindepositvalue')->nullable(true);
			$table->integer('appointment_id')->nullable(true);
			$table->integer('approved_by')->nullable(true);
			$table->dateTime('approved_on')->nullable(true);
			$table->integer('creditstatus_id')->nullable(false)->default(2);
			$table->date('incomestatementfrom')->nullable(false);
			$table->date('incomestatementto')->nullable(true);
			$table->date('balancesheeton')->nullable(false); 
			$table->date('bankstatementstart')->nullable(false); 
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
        Schema::dropIfExists('creditrequests');
    }
}
