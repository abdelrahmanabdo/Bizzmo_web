<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeFaxNullableInTableCompanaies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            DB::statement('ALTER TABLE `companies` MODIFY `fax` varchar(191) NULL;');
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
			DB::statement('update companies set fax = " " where fax is null and id>0;');
            DB::statement('ALTER TABLE `companies` MODIFY `fax` varchar(191) NOT NULL;');
        });
    }
}
