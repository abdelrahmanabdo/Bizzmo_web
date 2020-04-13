<?php

use Illuminate\Database\Seeder;

class IncomestatementitemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('incomestatementitems')->delete();
        
        \DB::table('incomestatementitems')->insert(array (
            0 => 
            array (
                'id' => 4,
                'name' => 'Revenues',
                'calc' => 0,
                'order' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            1 => 
            array (
                'id' => 5,
                'name' => 'Cost of Goods Sold',
                'calc' => 0,
                'order' => 2,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            2 => 
            array (
                'id' => 6,
                'name' => 'Gross Profit',
                'calc' => 1,
                'order' => 3,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            3 => 
            array (
                'id' => 7,
                'name' => '%',
                'calc' => 1,
                'order' => 4,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            4 => 
            array (
                'id' => 8,
                'name' => 'Sales, General & Admin Expenses',
                'calc' => 0,
                'order' => 5,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            5 => 
            array (
                'id' => 9,
                'name' => 'Operating profit',
                'calc' => 1,
                'order' => 6,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            6 => 
            array (
                'id' => 10,
                'name' => '%',
                'calc' => 1,
                'order' => 7,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            7 => 
            array (
                'id' => 11,
                'name' => 'Interest Income',
                'calc' => 0,
                'order' => 8,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            8 => 
            array (
                'id' => 12,
                'name' => 'Interest Expense',
                'calc' => 0,
                'order' => 9,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            9 => 
            array (
                'id' => 13,
                'name' => 'Other Income',
                'calc' => 0,
                'order' => 10,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            10 => 
            array (
                'id' => 14,
                'name' => 'Other Expenses',
                'calc' => 0,
                'order' => 11,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            11 => 
            array (
                'id' => 15,
                'name' => 'Net Profit',
                'calc' => 1,
                'order' => 12,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            12 => 
            array (
                'id' => 16,
                'name' => '%',
                'calc' => 1,
                'order' => 13,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
        ));
        
        
    }
}