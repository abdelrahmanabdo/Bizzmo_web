<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeederInit extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permission_role')->delete();
        
        \DB::table('permission_role')->insert(array (
                        0 => 
            array (
                'id' => 1,
                'permission_id' => 8,
                'role_id' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            1 => 
            array (
                'id' => 2,
                'permission_id' => 20,
                'role_id' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            2 => 
            array (
                'id' => 3,
                'permission_id' => 44,
                'role_id' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            3 => 
            array (
                'id' => 4,
                'permission_id' => 8,
                'role_id' => 2,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            4 => 
            array (
                'id' => 5,
                'permission_id' => 20,
                'role_id' => 2,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            5 => 
            array (
                'id' => 6,
                'permission_id' => 44,
                'role_id' => 2,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            6 => 
            array (
                'id' => 7,
                'permission_id' => 7,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            7 => 
            array (
                'id' => 8,
                'permission_id' => 8,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            8 => 
            array (
                'id' => 9,
                'permission_id' => 20,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            9 => 
            array (
                'id' => 10,
                'permission_id' => 21,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            10 => 
            array (
                'id' => 11,
                'permission_id' => 35,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            11 => 
            array (
                'id' => 12,
                'permission_id' => 8,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            12 => 
            array (
                'id' => 13,
                'permission_id' => 20,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            13 => 
            array (
                'id' => 14,
                'permission_id' => 32,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            14 => 
            array (
                'id' => 15,
                'permission_id' => 35,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            15 => 
            array (
                'id' => 16,
                'permission_id' => 40,
                'role_id' => 5,
                'created_at' => '2018-09-26 14:22:26',
                'updated_at' => '2018-09-26 14:22:26',
            ),
            16 => 
            array (
                'id' => 17,
                'permission_id' => 41,
                'role_id' => 5,
                'created_at' => '2018-09-26 14:22:26',
                'updated_at' => '2018-09-26 14:22:26',
            ),
            17 => 
            array (
                'id' => 18,
                'permission_id' => 1,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            18 => 
            array (
                'id' => 19,
                'permission_id' => 2,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            19 => 
            array (
                'id' => 20,
                'permission_id' => 3,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            20 => 
            array (
                'id' => 21,
                'permission_id' => 4,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            21 => 
            array (
                'id' => 22,
                'permission_id' => 5,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            22 => 
            array (
                'id' => 23,
                'permission_id' => 6,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            23 => 
            array (
                'id' => 24,
                'permission_id' => 9,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            24 => 
            array (
                'id' => 25,
                'permission_id' => 10,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            25 => 
            array (
                'id' => 26,
                'permission_id' => 11,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            26 => 
            array (
                'id' => 27,
                'permission_id' => 17,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            27 => 
            array (
                'id' => 28,
                'permission_id' => 22,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            28 => 
            array (
                'id' => 29,
                'permission_id' => 25,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            29 => 
            array (
                'id' => 30,
                'permission_id' => 26,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            30 => 
            array (
                'id' => 31,
                'permission_id' => 27,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),  
            34 => 
            array (
                'id' => 35,
                'permission_id' => 31,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            35 => 
            array (
                'id' => 36,
                'permission_id' => 42,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
        ));
        
        
    }
}