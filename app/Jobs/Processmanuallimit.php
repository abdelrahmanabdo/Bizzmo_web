<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Company;
use App\Helpers\SapConnection;

use Mail;

class Processmanuallimit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $company;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
				$sap_connection = SapConnection::getConnection();
				$func = $sap_connection->getFunction('ZCREATE_CUST_CREDIT');
				$result = $func->invoke([	
					'KUNNR' => $this->company->sapnumber,
					'KLIMK' => $this->company->creditlimit
				]);
				var_dump($result);		
    }
}
