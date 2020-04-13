<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;
use App\Mail\EmailVerification;
use App\User;


class Processregistration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $user;

	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		
		$data = ['email_token' => $this->user->email_token, 'name' => $this->user->name];

        Mail::send('emails.verification', $data, function($message) {
            //$message->sender('paul@verso-branding.com');
            $message->subject('Bizzmo email confirmation');
            $message->to($this->user->email);
			$message->bcc(env('MAIL_ARCHIVE'));
        });
		
        //$email = new EmailVerification($this->user);
		//Mail::to($user->email)->send($email);
    }
}
