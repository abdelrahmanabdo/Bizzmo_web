<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;

use App\Loginlog;

class LogSuccessfulLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {		
        $user = $event->user;
        $user->lastlogin = date('Y-m-d H:i:s');
        $user->lastip = $this->request->ip();
        $user->save();
		$loginlog = new Loginlog;
		$loginlog->user_id = $event->user->id;
		$loginlog->ip_address =  $this->request->ip();
		$loginlog->user_agent =  $this->request->userAgent();
		$loginlog->save();
    }
}
