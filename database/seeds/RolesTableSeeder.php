<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        //\DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (            
            5 => 
            array (
                'id' => 6,
                'rolename' => 'Admin',
                'company_id' => 1,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 7,
                'updated_by' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            6 => 
            array (
                'id' => 7,
                'rolename' => 'Finance',
                'company_id' => 1,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 7,
                'updated_by' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            7 => 
            array (
                'id' => 8,
                'rolename' => 'Purchaser',
                'company_id' => 1,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 7,
                'updated_by' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            8 => 
            array (
                'id' => 9,
                'rolename' => 'Purchasing manager',
                'company_id' => 1,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 7,
                'updated_by' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            9 => 
            array (
                'id' => 10,
                'rolename' => 'Salesman',
                'company_id' => 1,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 7,
                'updated_by' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            10 => 
            array (
                'id' => 11,
                'rolename' => 'Sales manager',
                'company_id' => 1,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 7,
                'updated_by' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            11 => 
            array (
                'id' => 12,
                'rolename' => 'Admin',
                'company_id' => 2,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 8,
                'updated_by' => 8,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            12 => 
            array (
                'id' => 13,
                'rolename' => 'Finance',
                'company_id' => 2,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 8,
                'updated_by' => 8,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            13 => 
            array (
                'id' => 14,
                'rolename' => 'Purchaser',
                'company_id' => 2,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 8,
                'updated_by' => 8,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            14 => 
            array (
                'id' => 15,
                'rolename' => 'Purchasing manager',
                'company_id' => 2,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 8,
                'updated_by' => 8,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            15 => 
            array (
                'id' => 16,
                'rolename' => 'Admin',
                'company_id' => 3,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 9,
                'updated_by' => 9,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            16 => 
            array (
                'id' => 17,
                'rolename' => 'Finance',
                'company_id' => 3,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 9,
                'updated_by' => 9,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            17 => 
            array (
                'id' => 18,
                'rolename' => 'Salesman',
                'company_id' => 3,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 9,
                'updated_by' => 9,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            18 => 
            array (
                'id' => 19,
                'rolename' => 'Sales manager',
                'company_id' => 3,
                'active' => 1,
                'systemrole' => 1,
                'created_by' => 9,
                'updated_by' => 9,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
        ));
        
        
    }
}