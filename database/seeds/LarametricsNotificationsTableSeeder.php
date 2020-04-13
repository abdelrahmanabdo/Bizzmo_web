<?php

use Illuminate\Database\Seeder;

class LarametricsNotificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('larametrics_notifications')->delete();
        
        \DB::table('larametrics_notifications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'action' => 'logged_error',
                'filter' => '*',
                'meta' => NULL,
                'notify_by' => 'email_slack',
                'last_fired_at' => NULL,
                'created_at' => '2018-12-09 12:48:30',
                'updated_at' => '2018-12-09 12:48:30',
            ),
        ));
        
        
    }
}