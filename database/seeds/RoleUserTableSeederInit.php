<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeederInit extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'user_id' => 2,
                'created_at' => '2018-09-26 14:24:14',
                'updated_at' => '2018-09-26 14:24:14',
            ),
            1 => 
            array (
                'id' => 2,
                'role_id' => 2,
                'user_id' => 3,
                'created_at' => '2018-09-26 14:24:38',
                'updated_at' => '2018-09-26 14:24:38',
            ),
            2 => 
            array (
                'id' => 3,
                'role_id' => 3,
                'user_id' => 4,
                'created_at' => '2018-09-26 14:25:00',
                'updated_at' => '2018-09-26 14:25:00',
            ),
            3 => 
            array (
                'id' => 4,
                'role_id' => 4,
                'user_id' => 5,
                'created_at' => '2018-09-26 14:25:27',
                'updated_at' => '2018-09-26 14:25:27',
            ),
            4 => 
            array (
                'id' => 5,
                'role_id' => 5,
                'user_id' => 6,
                'created_at' => '2018-09-26 14:26:07',
                'updated_at' => '2018-09-26 14:26:07',
            ),
            5 => 
            array (
                'id' => 6,
                'role_id' => 6,
                'user_id' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
        ));
        
        
    }
}