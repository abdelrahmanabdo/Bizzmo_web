<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSapPaymenttermPaymentterms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentterms', function (Blueprint $table) {
            $table->string('sappaymentterm')->after('buyup')->nullable(false);
        });
		
		\DB::table('paymentterms')->delete();
        
        \DB::table('paymentterms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Cash',
                'buyup' => 0.5,
                'sappaymentterm' => 'C001',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '30 days',
                'buyup' => 1.0,
                'sappaymentterm' => 'C019',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '60 days',
                'buyup' => 2.0,
                'sappaymentterm' => 'C037',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '90 days',
                'buyup' => 3.0,
                'sappaymentterm' => 'C041',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentterms', function (Blueprint $table) {
            $table->dropColumn('sappaymentterm');
        });
    }
}
