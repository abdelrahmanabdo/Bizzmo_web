<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
			$table->boolean('isAdmin')->default(false);
			$table->boolean('isSysadmin')->default(false);
			$table->integer('tenant_id')->unsigned()->nullable(true);
			$table->boolean('active')->default(false);
			$table->boolean('verified')->default(false);
            $table->string('password');
			$table->string('email_token')->nullable(true);
			$table->string('title')->nullable(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
