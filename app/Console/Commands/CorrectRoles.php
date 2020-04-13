<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\Settings;
use Carbon\Carbon;

class CorrectRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:correct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing permissions to salesman and purchaser roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$roles  = Role::where('rolename', 'Salesman')->where('company_id', '<>', '0')->get();
		foreach ($roles as $role) {
			echo $role->permissions->count();
			if ($role->permissions->count() == 1) {
				$role->permissions()->attach([37, 38, 39]);
			}
		}
		
		$roles  = Role::where('rolename', 'Purchaser')->where('company_id', '<>', '0')->get();
		foreach ($roles as $role) {
			echo $role->permissions->count();
			if ($role->permissions->count() == 3) {
				$role->permissions()->attach([46, 47]);
			}
		}
		
	}
}
