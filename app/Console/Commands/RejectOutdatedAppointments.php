<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Appointment;
use App\Creditrequest;
use Carbon\Carbon;
use App\Jobs\ProcessCancelAutomaticallyAppointment;

class RejectOutdatedAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete appointments not confirmed by credit team that are due today';

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
        $outdatedAppointments = Appointment::where('status_id', 1)
            ->whereDate('date', '<', Carbon::today()->toDateString())
            ->get();

        foreach ($outdatedAppointments as $appointment) {
            $appointment->status_id = 2;
            $appointment->save();

            // Unlink from its credit request
            $creditRequest = Creditrequest::where('appointment_id', $appointment->id)->first();
			Creditrequest::cancelappointment($creditRequest->id);

            ProcessCancelAutomaticallyAppointment::dispatch($appointment->company, $appointment);
        }
    }
}
