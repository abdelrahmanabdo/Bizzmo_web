<?php

use Illuminate\Database\Seeder;

class CompanytopproductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('companytopproducts')->delete();
        
        \DB::table('companytopproducts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'topproductname' => '1',
                'topproductrevenue' => 70.0,
                'active' => 1,
                'created_at' => '2018-09-26 14:42:26',
                'updated_at' => '2018-09-26 14:42:26',
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 2,
                'topproductname' => '1',
                'topproductrevenue' => 70.0,
                'active' => 1,
                'created_at' => '2018-09-26 14:50:04',
                'updated_at' => '2018-09-26 14:50:04',
            ),
            2 => 
            array (
                'id' => 3,
                'company_id' => 3,
                'topproductname' => '1',
                'topproductrevenue' => 70.0,
                'active' => 1,
                'created_at' => '2018-09-26 14:54:25',
                'updated_at' => '2018-09-26 14:54:25',
            ),
        ));
        
        
    }
}