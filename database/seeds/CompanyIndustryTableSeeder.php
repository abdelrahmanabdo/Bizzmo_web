<?php

use Illuminate\Database\Seeder;

class CompanyIndustryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('company_industry')->delete();
        
        \DB::table('company_industry')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 3,
                'industry_id' => 2,
                'created_at' => '2018-07-05 10:24:26',
                'updated_at' => '2018-07-05 10:24:26',
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 8,
                'industry_id' => 2,
                'created_at' => '2018-07-05 10:25:31',
                'updated_at' => '2018-07-05 10:25:31',
            ),
            2 => 
            array (
                'id' => 3,
                'company_id' => 13,
                'industry_id' => 2,
                'created_at' => '2018-07-05 10:28:15',
                'updated_at' => '2018-07-05 10:28:15',
            ),
            3 => 
            array (
                'id' => 4,
                'company_id' => 1,
                'industry_id' => 2,
                'created_at' => '2018-07-05 11:11:40',
                'updated_at' => '2018-07-05 11:11:40',
            ),
            4 => 
            array (
                'id' => 5,
                'company_id' => 14,
                'industry_id' => 2,
                'created_at' => '2018-07-05 14:11:15',
                'updated_at' => '2018-07-05 14:11:15',
            ),
            5 => 
            array (
                'id' => 6,
                'company_id' => 2,
                'industry_id' => 2,
                'created_at' => '2018-07-10 14:44:39',
                'updated_at' => '2018-07-10 14:44:39',
            ),
            6 => 
            array (
                'id' => 7,
                'company_id' => 12,
                'industry_id' => 2,
                'created_at' => '2018-07-12 08:51:46',
                'updated_at' => '2018-07-12 08:51:46',
            ),
            7 => 
            array (
                'id' => 8,
                'company_id' => 15,
                'industry_id' => 1,
                'created_at' => '2018-09-09 10:23:45',
                'updated_at' => '2018-09-09 10:23:45',
            ),
            8 => 
            array (
                'id' => 9,
                'company_id' => 15,
                'industry_id' => 2,
                'created_at' => '2018-09-09 10:23:45',
                'updated_at' => '2018-09-09 10:23:45',
            ),
            9 => 
            array (
                'id' => 10,
                'company_id' => 15,
                'industry_id' => 3,
                'created_at' => '2018-09-09 10:23:45',
                'updated_at' => '2018-09-09 10:23:45',
            ),
        ));
        
        
    }
}