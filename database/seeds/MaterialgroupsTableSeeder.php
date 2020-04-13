<?php

use Illuminate\Database\Seeder;

class MaterialgroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('materialgroups')->delete();
        
        \DB::table('materialgroups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'HP',
                'description' => 'HP',
                'active' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => '2017-11-15 07:02:10',
                'updated_at' => '2017-11-15 07:02:10',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Samsung',
                'description' => 'Samsung',
                'active' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => '2017-11-15 07:02:22',
                'updated_at' => '2017-11-15 07:02:22',
            ),
        ));
        
        
    }
}