<?php

use Illuminate\Database\Seeder;

class AddRightSignatureToken extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->insert(array(
            0 =>
                array(
                'id' => 2,
                'key' => 'rightSignature',
                'value' => '{
                    "refreshToken": "fcb0f1a07e5bf9d27919d365ce70d5e8d1a4a9292fa293f17d2f4d6037869cb0"
                  }',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
    }
}
