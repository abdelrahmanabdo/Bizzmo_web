<?php

use Illuminate\Database\Seeder;

class CompanyPaymenttermTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('company_paymentterm')->delete();
        
        \DB::table('company_paymentterm')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 3,
                'company_id' => 3,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 4,
                'company_id' => 4,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 5,
                'company_id' => 5,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 6,
                'company_id' => 9,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 7,
                'company_id' => 13,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2018-07-05 10:28:15',
                'updated_at' => '2018-07-05 10:28:15',
            ),
            6 => 
            array (
                'id' => 8,
                'company_id' => 14,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2018-07-05 14:11:15',
                'updated_at' => '2018-07-05 14:11:15',
            ),
            7 => 
            array (
                'id' => 9,
                'company_id' => 2,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2018-07-10 12:56:17',
                'updated_at' => '2018-07-10 12:56:17',
            ),
            8 => 
            array (
                'id' => 10,
                'company_id' => 2,
                'paymentterm_id' => 2,
                'buyup' => 1.0,
                'active' => 1,
                'created_at' => '2018-07-10 12:56:17',
                'updated_at' => '2018-07-10 12:56:17',
            ),
            9 => 
            array (
                'id' => 11,
                'company_id' => 15,
                'paymentterm_id' => 1,
                'buyup' => 0.5,
                'active' => 1,
                'created_at' => '2018-09-09 10:23:45',
                'updated_at' => '2018-09-09 10:23:45',
            ),
        ));
        
        
    }
}