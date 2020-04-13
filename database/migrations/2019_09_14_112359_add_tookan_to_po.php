<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTookanToPo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->string('delivery_tracking_link')->nullable(true)->after('deliverytype_id');
            $table->string('pickup_tracking_link')->nullable(true)->after('deliverytype_id');
			$table->string('delivery_status')->nullable(true)->after('deliverytype_id');
            $table->integer('delivery_job_id')->nullable(true)->after('deliverytype_id');
			$table->integer('pickup_job_id')->nullable(true)->after('deliverytype_id');
			$table->integer('job_id')->nullable(true)->after('deliverytype_id');
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
            $table->dropColumn('delivery_tracking_link');
            $table->dropColumn('pickup_tracking_link');
            $table->dropColumn('delivery_status');
			$table->dropColumn('delivery_job_id');
			$table->dropColumn('pickup_job_id');
			$table->dropColumn('job_id');
        });
    }
}
