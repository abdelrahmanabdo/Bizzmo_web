<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;

use App\Loginlog;

class LogFailedLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
		$loginlog = new Loginlog;
		if ($event->user == null) {
			$loginlog->email =  $event->credentials['email'];
		} else {
			$loginlog->user_id = $event->user->id;
		}
		$loginlog->ip_address =  $this->request->ip();
		$loginlog->user_agent =  $this->request->userAgent();
		$loginlog->save();
    }
}
