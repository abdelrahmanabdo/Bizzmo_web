<?php

use Illuminate\Database\Seeder;

class PaymenttermsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('paymentterms')->delete();
        
        \DB::table('paymentterms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Cash',
                'buyup' => 0.5,
                'sappaymentterm' => 'C001',
                'days' => 0,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '7 days',
                'buyup' => 0.6,
                'sappaymentterm' => 'C009',
                'days' => 7,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '10 days',
                'buyup' => 0.65,
                'sappaymentterm' => 'C010',
                'days' => 10,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '15 days',
                'buyup' => 0.75,
                'sappaymentterm' => 'C012',
                'days' => 15,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '21 days',
                'buyup' => 0.85,
                'sappaymentterm' => 'C015',
                'days' => 21,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '30 days',
                'buyup' => 1.0,
                'sappaymentterm' => 'C019',
                'days' => 30,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '60 days',
                'buyup' => 2.0,
                'sappaymentterm' => 'C037',
                'days' => 60,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => '90 days',
                'buyup' => 3.0,
                'sappaymentterm' => 'C041',
                'days' => 90,
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
        
        
    }
}