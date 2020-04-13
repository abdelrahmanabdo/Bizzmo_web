<?php

use Illuminate\Database\Seeder;

class CompanytopcustomersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('companytopcustomers')->delete();
        
        \DB::table('companytopcustomers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'topcustomername' => 'Cust 1',
                'country_id' => 229,
				'buyertype_id' => 1,
				'active' => 1,
                'created_at' => '2018-09-26 14:42:26',
                'updated_at' => '2018-09-26 14:42:26',
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 3,
                'topcustomername' => 'Cust 2',
				'country_id' => 229,
				'buyertype_id' => 1,
                'active' => 1,
                'created_at' => '2018-09-26 14:54:25',
                'updated_at' => '2018-09-26 14:54:25',
            ),
        ));
        
        
    }
}