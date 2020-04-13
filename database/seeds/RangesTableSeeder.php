<?php

use Illuminate\Database\Seeder;

class RangesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ranges')->delete();
        
        \DB::table('ranges')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '0-10%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '10-20%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '20-30%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '30-40%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '40-50%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '50-60%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '60-70%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => '70-80%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => '80-90%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '90-100%',
                'rangetype' => 'percent10',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Less than 25',
                'rangetype' => 'personel',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => '26-50',
                'rangetype' => 'personel',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => '51-100',
                'rangetype' => 'personel',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => '101-200',
                'rangetype' => 'personel',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'more than 200',
                'rangetype' => 'personel',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Less than 1 year',
                'rangetype' => 'years',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => '1-3',
                'rangetype' => 'years',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => '3-5',
                'rangetype' => 'years',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => '5-10',
                'rangetype' => 'years',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'More than 10 years',
                'rangetype' => 'years',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            20 => 
            array (
                'id' => 32,
                'name' => '9:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            21 => 
            array (
                'id' => 33,
                'name' => '10:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            22 => 
            array (
                'id' => 34,
                'name' => '11:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            23 => 
            array (
                'id' => 35,
                'name' => '12:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            24 => 
            array (
                'id' => 36,
                'name' => '13:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            25 => 
            array (
                'id' => 37,
                'name' => '14:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            26 => 
            array (
                'id' => 38,
                'name' => '15:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            27 => 
            array (
                'id' => 39,
                'name' => '16:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            28 => 
            array (
                'id' => 40,
                'name' => '17:00',
                'rangetype' => 'hours',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
        
        
    }
}