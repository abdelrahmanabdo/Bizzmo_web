<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        //\DB::table('users')->delete();
        
        \DB::table('users')->insert(array (                       
            6 => 
            array (
                'id' => 7,
                'name' => 'Both',
                'email' => 'sherif@example.com',
                'isAdmin' => 1,
                'isSysadmin' => 0,
                'tenant_id' => 7,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$HbgoCv1Rl.cPckrWY8yG1elcimjRqirFzetPtsJTaK9I3gB.Fukyq',
                'email_token' => NULL,
                'title' => 'Both',
                'remember_token' => 'o84po1N9zDpYvUfWI89yfTikkQ82zHzTSvA1rjTB63ALR9wuheL9drUUiRuk',
                'lastip' => '10.0.2.2',
                'lastlogin' => '2018-09-26 14:37:42',
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2018-09-26 14:35:24',
                'updated_at' => '2018-09-26 14:37:42',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Buyer',
                'email' => 'customer@example.com',
                'isAdmin' => 1,
                'isSysadmin' => 0,
                'tenant_id' => 8,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$LjBSN8V6bxnLRyY5h8oFG.xooru5MTOs3.5/o8xhXDIJqcNpu6F4G',
                'email_token' => NULL,
                'title' => 'Buyer',
                'remember_token' => 'BpLK9quwqNH5MHqmGuzX4Gl4o6Dmmn11JD9L918VGMfM3eGotqM6WDGd7lzX',
                'lastip' => '10.0.2.2',
                'lastlogin' => '2018-09-26 14:48:05',
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2018-09-26 14:46:18',
                'updated_at' => '2018-09-26 14:48:05',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Supplier',
                'email' => 'vendor@example.com',
                'isAdmin' => 1,
                'isSysadmin' => 0,
                'tenant_id' => 9,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$YRyDDdrzb.74OOLnmIuu1u2fUCL/S.B1fB18BHWxelBAQj.T6Km4i',
                'email_token' => NULL,
                'title' => 'Supplier',
                'remember_token' => NULL,
                'lastip' => '10.0.2.2',
                'lastlogin' => '2018-09-26 14:52:50',
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2018-09-26 14:51:17',
                'updated_at' => '2018-09-26 14:52:50',
            ),
        ));
        
        
    }
}