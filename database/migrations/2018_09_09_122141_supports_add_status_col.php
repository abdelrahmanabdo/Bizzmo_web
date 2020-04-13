<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Status;

class SupportsAddStatusCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->integer('status_id')->unsigned()->nullable(false)->default(Status::SUPPORT_OPEN);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });
    }
}
