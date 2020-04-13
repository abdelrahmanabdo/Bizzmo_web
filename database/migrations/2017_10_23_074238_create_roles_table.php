<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
			$table->string('rolename');
			$table->integer('company_id')->unsigned()->nullable(false);
			$table->boolean('active')->default(true);
			$table->boolean('systemrole')->default(true);
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();			
			$table->unique(['rolename', 'company_id']);
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
        Schema::dropIfExists('roles');
    }
}
