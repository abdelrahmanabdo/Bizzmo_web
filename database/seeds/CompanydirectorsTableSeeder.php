<?php

use Illuminate\Database\Seeder;

class CompanydirectorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('companydirectors')->delete();

        \DB::table('companydirectors')->insert(array(
            0 =>
                array(
                'id' => 1,
                'company_id' => 1,
                'directorname' => 'My Director 1',
                'directortitle' => 'Title 1',
                'directoremail' => 'dir@mail.com',
                'directorphone' => '+(111) 11 1111111',
                'active' => 1,
                'created_at' => '2018-09-26 14:41:56',
                'updated_at' => '2018-09-26 14:41:56',
            ),
            1 =>
                array(
                'id' => 2,
                'company_id' => 2,
                'directorname' => 'My Director 1',
                'directortitle' => 'Title 1',
                'directoremail' => 'dir@mail.com',
                'directorphone' => '+(111) 11 1111111',
                'active' => 1,
                'created_at' => '2018-09-26 14:49:59',
                'updated_at' => '2018-09-26 14:49:59',
            ),
        ));


    }
}