<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        //\DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (            
            6 => 
            array (
                'id' => 7,
                'role_id' => 12,
                'user_id' => 8,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            7 => 
            array (
                'id' => 8,
                'role_id' => 16,
                'user_id' => 9,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
        ));
        
        
    }
}