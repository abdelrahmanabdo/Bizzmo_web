<?php

use Illuminate\Database\Seeder;

class AttachmenttypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attachmenttypes')->delete();
        
        \DB::table('attachmenttypes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Owner ID',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Owner visa',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Director ID',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Director visa',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Trade license',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Bank statement',
                'module_id' => 2,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Security check',
                'module_id' => 2,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Financial statement',
                'module_id' => 2,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Owner passport',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Director passport',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Beneficial ID',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Beneficial visa',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Beneficial passport',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Delivery note',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Buyer invoice',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Supplier invoice',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Supplier Contract',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Buyer Contract',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Signed Delivery Document',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Tax Certificate',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Personal guarantee',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Corporate guarantee',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Promissary note',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            23 => 
            array (
                'id' => 27,
                'name' => 'Article of Association',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            24 => 
            array (
                'id' => 28,
                'name' => 'Signatory ID',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            25 => 
            array (
                'id' => 29,
                'name' => 'Signatory visa',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            26 => 
            array (
                'id' => 30,
                'name' => 'Signatory passport',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            27 => 
            array (
                'id' => 31,
                'name' => 'Supplier quotation',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            28 => 
            array (
                'id' => 32,
                'name' => 'Bizzmo quotation',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            29 => 
            array (
                'id' => 33,
                'name' => 'Buyer PO',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            30 => 
            array (
                'id' => 34,
                'name' => 'Bizzmo PO',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            31 => 
            array (
                'id' => 35,
                'name' => 'Product Image',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            32 => 
            array (
                'id' => 36,
                'name' => 'Check Authorization',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            33 => 
            array (
                'id' => 37,
                'name' => 'Old Supplier Contract',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            34 => 
            array (
                'id' => 38,
                'name' => 'Old Buyer Contract',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
            35 => 
            array (
                'id' => 38,
                'name' => 'User',
                'module_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-14 21:27:30',
                'updated_at' => '2017-10-14 21:27:30',
            ),
        ));
        
        
    }
}