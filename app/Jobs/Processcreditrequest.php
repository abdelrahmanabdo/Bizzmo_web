<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Creditrequest;
use App\Helpers\SapConnection;

use Mail;

class Processcreditrequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $creditrequest;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($creditrequest)
    {
        $this->creditrequest = $creditrequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$data = ['askedlimit' => $this->creditrequest->askedlimit, 'name' => $this->creditrequest->company->companyname];

		if ($this->creditrequest->creditstatus_id == 3) {
			Mail::send('emails.creditrejection', $data, function($message) {
				//$message->sender('paul@verso-branding.com');
				$message->subject('Credit request status update');
				$message->to($this->creditrequest->company->email);
			});
		} else {
			if ($this->creditrequest->creditstatus_id == 1) {
				$sap_connection = SapConnection::getConnection();
				$func = $sap_connection->getFunction('ZCREATE_CUST_CREDIT');
				$result = $func->invoke([	
					'KUNNR' => $this->creditrequest->company->sapnumber,
					'KLIMK' => $this->creditrequest->limit
				]);
				//var_dump($result);
			}
		}
		
    }
}
