<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('attachable_id')->nullable(true);
			$table->string('attachable_type')->nullable(true);
			$table->integer('attachmenttype_id')->nullable(true);
			$table->string('path')->nullable(false);
			$table->string('description')->nullable(true);
			$table->string('filename')->nullable(true);
			$table->string('authcode')->nullable(true);
			$table->string('envelope')->nullable(true);
			$table->string('document')->nullable(true);
			$table->string('status')->nullable(true);
			$table->integer('created_by');
			$table->integer('updated_by');
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
        Schema::dropIfExists('attachments');
    }
}
