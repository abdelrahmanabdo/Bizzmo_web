<?php

use Illuminate\Database\Seeder;

class IncomestatementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('incomestatements')->delete();
        
        \DB::table('incomestatements')->insert(array (
            0 => 
            array (
                'id' => 1,
                'creditrequest_id' => 2,
                'order' => 1,
                'incomestatementitem_id' => 4,
                'incomestatementitemy1' => 9.0,
                'incomestatementitemy2' => 9.0,
                'incomestatementitemy3' => 9.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            1 => 
            array (
                'id' => 2,
                'creditrequest_id' => 2,
                'order' => 2,
                'incomestatementitem_id' => 5,
                'incomestatementitemy1' => 9.0,
                'incomestatementitemy2' => 9.0,
                'incomestatementitemy3' => 9.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            2 => 
            array (
                'id' => 3,
                'creditrequest_id' => 2,
                'order' => 5,
                'incomestatementitem_id' => 8,
                'incomestatementitemy1' => 9.0,
                'incomestatementitemy2' => 9.0,
                'incomestatementitemy3' => 9.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            3 => 
            array (
                'id' => 4,
                'creditrequest_id' => 2,
                'order' => 8,
                'incomestatementitem_id' => 11,
                'incomestatementitemy1' => 9.0,
                'incomestatementitemy2' => 9.0,
                'incomestatementitemy3' => 9.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            4 => 
            array (
                'id' => 5,
                'creditrequest_id' => 2,
                'order' => 9,
                'incomestatementitem_id' => 12,
                'incomestatementitemy1' => 9.0,
                'incomestatementitemy2' => 9.0,
                'incomestatementitemy3' => 9.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            5 => 
            array (
                'id' => 6,
                'creditrequest_id' => 2,
                'order' => 3,
                'incomestatementitem_id' => 6,
                'incomestatementitemy1' => 0.0,
                'incomestatementitemy2' => 0.0,
                'incomestatementitemy3' => 0.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            6 => 
            array (
                'id' => 7,
                'creditrequest_id' => 2,
                'order' => 4,
                'incomestatementitem_id' => 7,
                'incomestatementitemy1' => 0.0,
                'incomestatementitemy2' => 0.0,
                'incomestatementitemy3' => 0.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            7 => 
            array (
                'id' => 8,
                'creditrequest_id' => 2,
                'order' => 6,
                'incomestatementitem_id' => 9,
                'incomestatementitemy1' => 9.0,
                'incomestatementitemy2' => 9.0,
                'incomestatementitemy3' => 9.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            8 => 
            array (
                'id' => 9,
                'creditrequest_id' => 2,
                'order' => 7,
                'incomestatementitem_id' => 10,
                'incomestatementitemy1' => 1.0,
                'incomestatementitemy2' => 1.0,
                'incomestatementitemy3' => 1.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            9 => 
            array (
                'id' => 10,
                'creditrequest_id' => 2,
                'order' => 10,
                'incomestatementitem_id' => 13,
                'incomestatementitemy1' => 27.0,
                'incomestatementitemy2' => 27.0,
                'incomestatementitemy3' => 27.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
            10 => 
            array (
                'id' => 11,
                'creditrequest_id' => 2,
                'order' => 11,
                'incomestatementitem_id' => 14,
                'incomestatementitemy1' => 3.0,
                'incomestatementitemy2' => 3.0,
                'incomestatementitemy3' => 3.0,
                'created_at' => '2018-07-17 13:30:07',
                'updated_at' => '2018-07-17 13:30:07',
            ),
        ));
        
        
    }
}