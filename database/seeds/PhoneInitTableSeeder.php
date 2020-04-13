<?php

use Illuminate\Database\Seeder;

class PhoneInitTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('phone')->delete();
        
        \DB::table('phone')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'phone' => '+201222138638',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'phone' => '+201222138638',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2017-12-07 08:27:51',
                'updated_at' => '2017-12-07 08:28:53',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 3,
                'phone' => '+201222138638',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2017-12-07 08:27:51',
                'updated_at' => '2017-12-07 08:27:51',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 4,
                'phone' => '+201222138638',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2017-12-07 08:27:51',
                'updated_at' => '2017-12-07 08:27:51',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 5,
                'phone' => '+201222138638',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2017-12-10 11:04:24',
                'updated_at' => '2017-12-10 11:04:24',
            ),
            5 => 
            array (
                'id' => 10,
                'user_id' => 6,
                'phone' => '+201222138638',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2018-07-10 12:33:22',
                'updated_at' => '2018-07-10 12:34:47',
            ),
        ));
        
        
    }
}