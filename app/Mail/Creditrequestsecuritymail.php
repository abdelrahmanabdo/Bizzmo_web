<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Creditrequest;

class Creditrequestsecuritymail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Creditrequest $creditrequest)
    {
        $this->user = $creditrequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.creditrequestsecurities');
    }
}
