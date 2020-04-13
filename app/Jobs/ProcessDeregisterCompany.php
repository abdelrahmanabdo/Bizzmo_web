<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Helpers\CalendarFileGenerator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Message;
use Storage;
use Mail;
use Log;

use App\Actiontoken;
use App\Company;

class ProcessDeregisterCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $actiontoken;

	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($actiontoken)
    {
        $this->actiontoken = $actiontoken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$company = Company::find($this->actiontoken->object_id);
		$email = $company->email;
		Mail::send('emails.deregister', ['id' => $this->actiontoken->id, 'token' => $this->actiontoken->token, 'company' => $company->companyname], function(Message $message) use ($email) {
			$message->subject('Company deregistration');
			$message->to($email);
		});      
	}
}
