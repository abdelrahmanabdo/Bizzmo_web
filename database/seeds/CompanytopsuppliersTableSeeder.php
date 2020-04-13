<?php

use Illuminate\Database\Seeder;

class CompanytopsuppliersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('companytopsuppliers')->delete();
        
        \DB::table('companytopsuppliers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 3,
                'topsuppliername' => 'dfg',
                'active' => 1,
                'created_at' => '2018-07-05 10:25:00',
                'updated_at' => '2018-07-05 10:25:00',
                'suppliertype_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 3,
                'topsuppliername' => 'dfg',
                'active' => 1,
                'created_at' => '2018-07-05 10:25:00',
                'updated_at' => '2018-07-05 10:25:00',
                'suppliertype_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'company_id' => 3,
                'topsuppliername' => 'dfgsdfg',
                'active' => 1,
                'created_at' => '2018-07-05 10:25:00',
                'updated_at' => '2018-07-05 10:25:00',
                'suppliertype_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'company_id' => 13,
                'topsuppliername' => 'sdgf',
                'active' => 1,
                'created_at' => '2018-07-05 10:29:22',
                'updated_at' => '2018-07-05 10:29:22',
                'suppliertype_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'company_id' => 13,
                'topsuppliername' => 'dsfdg',
                'active' => 1,
                'created_at' => '2018-07-05 10:29:22',
                'updated_at' => '2018-07-05 10:29:22',
                'suppliertype_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'company_id' => 1,
                'topsuppliername' => 'tty',
                'active' => 1,
                'created_at' => '2018-07-05 11:12:01',
                'updated_at' => '2018-07-05 11:12:01',
                'suppliertype_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'company_id' => 1,
                'topsuppliername' => 'reyt',
                'active' => 1,
                'created_at' => '2018-07-05 11:12:01',
                'updated_at' => '2018-07-05 11:12:01',
                'suppliertype_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'company_id' => 14,
                'topsuppliername' => 'fdhg',
                'active' => 1,
                'created_at' => '2018-07-05 14:12:06',
                'updated_at' => '2018-07-05 14:12:06',
                'suppliertype_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'company_id' => 14,
                'topsuppliername' => 'fgh',
                'active' => 1,
                'created_at' => '2018-07-05 14:12:06',
                'updated_at' => '2018-07-05 14:12:06',
                'suppliertype_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'company_id' => 2,
                'topsuppliername' => 'Supplier 1',
                'active' => 1,
                'created_at' => '2018-07-10 14:48:32',
                'updated_at' => '2018-07-10 14:48:32',
                'suppliertype_id' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'company_id' => 2,
                'topsuppliername' => 'Supplier 2',
                'active' => 1,
                'created_at' => '2018-07-10 14:48:32',
                'updated_at' => '2018-07-10 14:48:32',
                'suppliertype_id' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'company_id' => 12,
                'topsuppliername' => 'fdghdfgh',
                'active' => 1,
                'created_at' => '2018-07-12 08:52:06',
                'updated_at' => '2018-07-12 08:52:06',
                'suppliertype_id' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'company_id' => 15,
                'topsuppliername' => 'sup1',
                'active' => 1,
                'created_at' => '2018-09-09 10:32:13',
                'updated_at' => '2018-09-09 10:32:13',
                'suppliertype_id' => 1,
            ),
        ));
        
        
    }
}