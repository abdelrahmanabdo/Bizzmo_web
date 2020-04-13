<?php

use Illuminate\Database\Seeder;

class CompanyDeliveryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('company_deliverytype')->delete();
        
        \DB::table('company_deliverytype')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'deliverytype_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 3,
                'deliverytype_id' => 1,
                'active' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
        
        
    }
}