<?php

use Illuminate\Database\Seeder;

class MargindeposittypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('margindeposittypes')->delete();
        
        \DB::table('margindeposittypes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Cash',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Cheque',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Bank Guarantee',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
        
        
    }
}