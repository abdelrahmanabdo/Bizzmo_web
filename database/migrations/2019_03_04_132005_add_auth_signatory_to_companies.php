<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthSignatoryToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('authsignatory')->default(false)->nullable(false)->after('basicinfo');
			$table->string('signatoryname')->nullable(true)->after('sapvendornumber');
			$table->string('signatoryemail')->nullable(true)->after('sapvendornumber');
			$table->string('signatoryphone')->nullable(true)->after('sapvendornumber');
			$table->string('signatorydesignation')->nullable(true)->after('sapvendornumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('authsignatory');
			$table->dropColumn('signatoryname');
			$table->dropColumn('signatoryemail');
			$table->dropColumn('signatoryphone');
			$table->dropColumn('signatorydesignation');
        });
    }
}
