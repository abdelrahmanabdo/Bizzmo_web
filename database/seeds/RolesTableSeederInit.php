<?php

use Illuminate\Database\Seeder;

class RolesTableSeederInit extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'rolename' => 'AP',
                'company_id' => 0,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            1 => 
            array (
                'id' => 2,
                'rolename' => 'AR',
                'company_id' => 0,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            2 => 
            array (
                'id' => 3,
                'rolename' => 'Credit',
                'company_id' => 0,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            3 => 
            array (
                'id' => 4,
                'rolename' => 'Salesman',
                'company_id' => 0,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            4 => 
            array (
                'id' => 5,
                'rolename' => 'Support',
                'company_id' => 0,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2018-09-26 14:22:26',
                'updated_at' => '2018-09-26 14:22:26',
            ),
        ));
        
        
    }
}