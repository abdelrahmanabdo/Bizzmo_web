<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReleaseOtpToPurchaseorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {            
			$table->string('accepted_at')->nullable(true)->after('status_id');
			$table->string('accepted_by')->nullable(true)->after('status_id');			
			$table->string('release_otp')->nullable(true)->after('released_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
			$table->dropColumn('accepted_at');            
			$table->dropColumn('accepted_by');
			$table->dropColumn('release_otp');			
        });
    }
}
