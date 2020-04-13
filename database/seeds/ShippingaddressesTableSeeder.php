<?php

use Illuminate\Database\Seeder;

class ShippingaddressesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shippingaddresses')->delete();
        
        \DB::table('shippingaddresses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'address' => 'ABC',
                'city_id' => '3798',
                'created_at' => '2017-01-01 00:00:00',
                'updated_at' => '2017-01-01 00:00:00',
                'country_name' => NULL,
                'city_name' => NULL,
                'po_box' => NULL,
                'partyname' => 'DHM',
                'phone' => '123',
                'fax' => '123',
                'created_by' => 1,
                'updated_by' => 1,
                'vat' => 1,
                'vatexempt' => 0,
                'exempt_by' => NULL,
                'default' => 0,
                'delivery_address' => 'ABC',
                'delivery_city_id' => 3798,
                'incoterm_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 2,
                'address' => 'Has VAT',
                'city_id' => '3798',
                'created_at' => '2017-01-01 00:00:00',
                'updated_at' => '2017-01-01 00:00:00',
                'country_name' => NULL,
                'city_name' => NULL,
                'po_box' => NULL,
                'partyname' => 'PPK',
                'phone' => '123',
                'fax' => '123',
                'created_by' => 1,
                'updated_by' => 1,
                'vat' => 1,
                'vatexempt' => 0,
                'exempt_by' => NULL,
                'default' => 0,
                'delivery_address' => 'Has VAT',
                'delivery_city_id' => 3798,
                'incoterm_id' => 1,
            ),
            2 => 
            array (
                'id' => 4,
                'company_id' => 2,
                'address' => 'No VAT and def',
                'city_id' => '3798',
                'created_at' => '2017-01-01 00:00:00',
                'updated_at' => '2017-01-01 00:00:00',
                'country_name' => NULL,
                'city_name' => NULL,
                'po_box' => NULL,
                'partyname' => 'Wonder',
                'phone' => '123',
                'fax' => '123',
                'created_by' => 1,
                'updated_by' => 1,
                'vat' => 0,
                'vatexempt' => 0,
                'exempt_by' => NULL,
                'default' => 1,
                'delivery_address' => 'No VAT and def',
                'delivery_city_id' => 3798,
                'incoterm_id' => 1,
            ),
        ));
        
        
    }
}