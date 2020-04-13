<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'basicInfo',
                'value' => '{
				"companyName": "Bizzmo",
				"poBox": "PO Box 61188",
				"address": "Jebel Ali, Dubai, Utd.Arab Emir",
				"tel": "+97148863360",
				"fax": "+97148863656",
				"tax": "100233168200003"
				}',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
			1 => 
            array (
                'id' => 3,
                'key' => 'buyerInvoiceNumber',
                'value' => '0',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
			2 => 
            array (
                'id' => 4,
                'key' => 'SalesOrderNumber',
                'value' => '0',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
			3 => 
            array (
                'id' => 5,
                'key' => 'COD Limit (USD)',
                'value' => '6800',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            )
        ));
        
        
    }
}