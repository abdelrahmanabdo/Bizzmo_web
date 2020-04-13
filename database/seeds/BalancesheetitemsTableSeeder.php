<?php

use Illuminate\Database\Seeder;

class BalancesheetitemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('balancesheetitems')->delete();
        
        \DB::table('balancesheetitems')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Assets',
                'calc' => 1,
                'order' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Current Assets',
                'calc' => 1,
                'order' => 2,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Bank Balances and Cash',
                'calc' => 0,
                'order' => 3,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Accounts Receivable & Prepayments',
                'calc' => 0,
                'order' => 4,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Inventories',
                'calc' => 0,
                'order' => 5,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Related Party Balances',
                'calc' => 0,
                'order' => 6,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Other Current Assets',
                'calc' => 0,
                'order' => 8,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Total Current Assets',
                'calc' => 1,
                'order' => 9,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Non-Current Assets',
                'calc' => 1,
                'order' => 10,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Property, Plant and Equipment',
                'calc' => 0,
                'order' => 11,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Other Non-Current Assets',
                'calc' => 0,
                'order' => 12,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Total Non-Current Assets',
                'calc' => 1,
                'order' => 13,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Total Assets',
                'calc' => 1,
                'order' => 14,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Equity And Liabilities',
                'calc' => 1,
                'order' => 15,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Current Liabilities',
                'calc' => 1,
                'order' => 16,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Accounts Payables and Accruals',
                'calc' => 0,
                'order' => 17,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Debt and Loans',
                'calc' => 0,
                'order' => 18,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Other Current Liabilities',
                'calc' => 0,
                'order' => 20,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Total Current Liabilities',
                'calc' => 1,
                'order' => 21,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Non-Current Liabilities',
                'calc' => 1,
                'order' => 22,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Long Term Loan',
                'calc' => 0,
                'order' => 23,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Total Non-Current Liabilities',
                'calc' => 1,
                'order' => 24,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Total Liabilities',
                'calc' => 1,
                'order' => 25,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Equity',
                'calc' => 0,
                'order' => 26,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Profit of the year',
                'calc' => 0,
                'order' => 27,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Total Equity',
                'calc' => 1,
                'order' => 28,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Total Equity and Liabilities',
                'calc' => 1,
                'order' => 29,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Related Party Liabilities',
                'calc' => 0,
                'order' => 19,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 0,
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Related Party Assets',
                'calc' => 0,
                'order' => 7,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
                'sign' => 1,
            ),
        ));
        
        
    }
}