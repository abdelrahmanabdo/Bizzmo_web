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


class ProcessAppointment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $user;
    protected $company;
	protected $appointment;

	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $company, $appointment)
    {
        $this->user = $user;
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
        $calendar_file_props = [
            'summary' => $this->company->companyname . ' Appointment',
            'description' => $this->appointment->description,
            'duration' => 'PT1H30M0S',
            'dtstart' => $this->appointment->date . ' ' . $this->getTime($this->appointment->timeslot_id, $this->appointment->company->country_id)
        ];
        $companyName = $this->company->companyname;
        $uniqueId = uniqid();
        $calendar_file = new CalendarFileGenerator($calendar_file_props);
        $calendar_file_contents = $calendar_file->to_string();
        $file_path = "mail_attachments/calendar/appointment_$uniqueId.ics";
        $is_saved = Storage::put($file_path, $calendar_file_contents);

        if(!$is_saved)
            throw new \Exception('File not saved for appointment ' . $this->appointment->id);
        
        $full_path = realpath('storage/app/' . $file_path);
        //\Log::Debug("file is saved: " . $is_saved);
        //\Log::Debug("full path: $full_path");
        //\Log::Debug("file path: $file_path");
        //\Log::Debug("file exists: " . file_exists($full_path));

        if(!file_exists($full_path))
            //throw new \Exception('File path is incorrect for appointment ' . $this->appointment->id);
    
        // If the email is send to user
        if($this->user) {
            Mail::send('emails.appointment', ['timeslot' => $this->appointment->timeslot->name, 'date' => $this->appointment->date, 'name' => ''], function(Message $message) use ($file_path) {
                $message->subject('Appointment Calendar');
                $message->to($this->user->email);
                $message->attach(Storage::path($file_path), ['mime' => 'text/calendar; charset=UTF-8; method=PUBLISH;']);
            });
        } 

        // If the email is send to company
        if($this->company) {
            Mail::send('emails.appointment', ['timeslot' => $this->appointment->timeslot->name, 'date' => $this->appointment->date, 'name' => $companyName], function(Message $message) use ($file_path) {
                $message->subject('Appointment Calendar');
                $message->to($this->company->email);
                $message->attach(Storage::path($file_path), array('mime' => 'text/calendar; charset=UTF-8; method=PUBLISH;'));
            });
        }
        Storage::delete($file_path);
    }

    /**
     * Get Time corresponding to timeslot id according to timeslot table
     *
     * @return string
     */
    private function getTime($timeslot, $country_id) 
    {
		if ($country_id == 229) {
			switch($timeslot) {
				case 1:
					return '05:00:00';
				case 2:
					return '06:30:00';
				case 3: 
					return '08:00:00';
				case 4:
					return '09:30:00';
				case 5:
					return '11:00:00';
				default:
					return '';
			}
		} else {
			switch($timeslot) {
				case 1:
					return '06:00:00';
				case 2:
					return '07:30:00';
				case 3: 
					return '09:00:00';
				case 4:
					return '10:30:00';
				case 5:
					return '12:00:00';
				default:
					return '';
			}

		}
    }
}
