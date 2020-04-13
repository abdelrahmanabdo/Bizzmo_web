<?php

use Illuminate\Database\Seeder;

class UsersInitTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Sysadmin',
                'email' => 'sysadmin@metragroup.com',
                'isAdmin' => 0,
                'isSysadmin' => 1,
                'tenant_id' => 1,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$4XZ2XDntDi0MvBzNRxmheOGcSFYxlN3oOVFVkHR3gP82CLQlrdEZq',
                'email_token' => NULL,
                'title' => 'System administrator',
                'remember_token' => 'EAnRVhT03uqyUfN8AKf8x2cU1v4LZ2wnfY8r1nf2Wy6oPDVrpslXy3ueapiZ',
				'lastip' => NULL,
                'lastlogin' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
			1 => 
            array (
                'id' => 2,
                'name' => 'AP',
                'email' => 'ap@metragroup.com',
                'isAdmin' => 0,
                'isSysadmin' => 0,
                'tenant_id' => 1,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$4ShB3H6QUSe2DxNdeSzqveoz/Wtf4t0GYcK7vhiR9u34W2VHdbwZG',
                'email_token' => 'nR4U56obkP9eZ22SahrJ',
                'title' => 'AP',
                'remember_token' => NULL,
                'lastip' => NULL,
                'lastlogin' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:24:14',
                'updated_at' => '2018-09-26 14:24:14',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'AR',
                'email' => 'ar@metragroup.com',
                'isAdmin' => 0,
                'isSysadmin' => 0,
                'tenant_id' => 1,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$6RetkPqxzm.keZgnC1lIEuVrEUZnStTd2CWQvY8YX5PHfDlkNxY.y',
                'email_token' => '7kP8LEWLY8zW5bskVHpL',
                'title' => 'AR',
                'remember_token' => NULL,
                'lastip' => NULL,
                'lastlogin' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:24:38',
                'updated_at' => '2018-09-26 14:24:38',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Credit',
                'email' => 'credit@metragroup.com',
                'isAdmin' => 0,
                'isSysadmin' => 0,
                'tenant_id' => 1,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$DvwA/bgtmWITSCmBE4.PK.dsLzXE75DbzBi394GtiKchFc6sbjd0m',
                'email_token' => 'CEf4egaRDGTkjkuXyM9b',
                'title' => 'Credit',
                'remember_token' => NULL,
                'lastip' => NULL,
                'lastlogin' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:25:00',
                'updated_at' => '2018-09-26 14:25:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Salesman',
                'email' => 'salesman@metragroup.com',
                'isAdmin' => 0,
                'isSysadmin' => 0,
                'tenant_id' => 1,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$qOxXpuY3Hko.zgqDgP7XueMgORv4WgSFuqjgwwXgu.ytZLy3wJ8tu',
                'email_token' => 'ebZJcjPHG7mBMuGpJnW1',
                'title' => 'Salesman',
                'remember_token' => NULL,
                'lastip' => NULL,
                'lastlogin' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:25:27',
                'updated_at' => '2018-09-26 14:25:27',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Support',
                'email' => 'support@metragroup.com',
                'isAdmin' => 0,
                'isSysadmin' => 0,
                'tenant_id' => 1,
                'active' => 1,
                'verified' => 1,
                'password' => '$2y$10$oMMbY3k8bwRbQDy3o7l8POH4A10lOQG07chtGQyXHaTplfJe6pOZ.',
                'email_token' => 'EAGoMhH47iTldUD0LHeN',
                'title' => 'Support',
                'remember_token' => NULL,
                'lastip' => NULL,
                'lastlogin' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:26:07',
                'updated_at' => '2018-09-26 14:26:07',
            ),
        ));
        
        
    }
}