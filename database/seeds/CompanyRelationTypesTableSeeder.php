<?php

use Illuminate\Database\Seeder;

class CompanyRelationTypesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('company_relation_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Parent',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Sister',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Subsidiaries',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Affiliated',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
    }
}
