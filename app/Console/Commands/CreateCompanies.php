<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:create {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new companies records for testing';

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
        $count = intval($this->argument('count'));

        $this->line("Creating $count companies...");

        // Create companies
        factory(\App\Company::class, $count)->create();
        
        $this->info("$count Companies created successfully");
    }
}
