<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Message;
use Mail;
use Log;


class ProcessCancelAppointment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;
	protected $appointment;

	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company, $appointment)
    {
        $this->company = $company;
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = date_create_from_format('Y-m-d', $this->appointment->date); // convert to datetime 
        $formatedDate = date_format($date, 'l jS F Y'); // e.g. Thursday 20th September 2018  

        // Send email to company
        if($this->company) {
            $data = ['date' => $formatedDate, 'company' => $this->company->companyname];
            Mail::send('emails.cancel-appointment', $data, function(Message $message) {
                $message->subject('Appointment Cancellation');
                $message->to($this->company->email);
            });
        }
    }
}
