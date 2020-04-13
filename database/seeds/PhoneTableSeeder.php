<?php

use Illuminate\Database\Seeder;

class PhoneTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('phone')->insert(array (            
            0 => 
            array (
                'id' => 11,
                'user_id' => 7,
                'phone' => '+201222138636',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2018-07-10 12:33:22',
                'updated_at' => '2018-07-10 12:33:22',
            ),
            1 => 
            array (
                'id' => 12,
                'user_id' => 8,
                'phone' => '+201222138636',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2018-07-10 12:33:22',
                'updated_at' => '2018-07-10 12:33:22',
            ),
            2 => 
            array (
                'id' => 13,
                'user_id' => 9,
                'phone' => '+201222138636',
                'code' => '1234',
                'verified' => 1,
                'created_at' => '2018-07-10 12:33:22',
                'updated_at' => '2018-07-10 12:33:22',
            ),
        ));
        
        
    }
}