<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SupportsMakeSelectedColsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->string('name')->nullable(true)->change();
            $table->string('company')->nullable(true)->change();
            $table->string('email')->nullable(true)->change();
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
            $table->string('name')->nullable(false)->change();
            $table->string('company')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
}
