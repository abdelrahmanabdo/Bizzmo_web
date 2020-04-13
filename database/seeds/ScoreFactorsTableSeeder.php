<?php

use Illuminate\Database\Seeder;

class ScoreFactorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('score_factors')->insert(array(
            0 =>
                array(
                'id' => 1,
                'name' => 'Payment history',
                'weight' => 0.3,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 =>
                array(
                'id' => 2,
                'name' => 'Pay history to others dist',
                'weight' => 0.04,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 =>
                array(
                'id' => 3,
                'name' => 'Bank rating and references',
                'weight' => 0.08,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 =>
                array(
                'id' => 4,
                'name' => 'Financial Ratios',
                'weight' => 0.15,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            4 =>
                array(
                'id' => 5,
                'name' => 'Trade references',
                'weight' => 0.03,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            5 =>
                array(
                'id' => 6,
                'name' => 'Trade history years',
                'weight' => 0.35,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            6 =>
                array(
                'id' => 7,
                'name' => 'Early settlement -discounts',
                'weight' => 0.05,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
    }
}
