<?php

use Illuminate\Database\Seeder;

class CompanyownersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('companyowners')->delete();

        \DB::table('companyowners')->insert(array(
            0 =>
                array(
                'id' => 1,
                'company_id' => 1,
                'ownername' => 'Shareholder1',
                'owneremail' => 'owner@mail.com',
                'ownerphone' => '+(111) 11 1111111',
                'ownershare' => 90.0,
                'active' => 1,
                'created_at' => '2018-09-26 14:41:39',
                'updated_at' => '2018-09-26 14:41:39',
            ),
            1 =>
                array(
                'id' => 2,
                'company_id' => 2,
                'ownername' => 'Shareholder1',
                'owneremail' => 'owner@mail.com',
                'ownerphone' => '+(111) 11 1111111',
                'ownershare' => 90.0,
                'active' => 1,
                'created_at' => '2018-09-26 14:49:42',
                'updated_at' => '2018-09-26 14:49:42',
            ),
        ));


    }
}