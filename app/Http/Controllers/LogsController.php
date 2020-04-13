<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Input;
use DateTime;

use App\Phone;
use App\Http\Requests\ChangePasswordRequest;
use App\Loginlog;


class LogsController extends Controller
{
	public function index()
	{
		return redirect('/logs/login-logs');
	}

	public function phpinfo()
	{
		return View('logs.phpinfo')->with('title', 'PHPinfo');
	}
	
	public function loginLogs(Request $request)
	{
		$fromdate = Input::get('fromdate');
		if (Input::get('fromdate') == '') {
			$fromdate = date('j/n/Y');
		} else {
			
		}
		$todate = Input::get('todate');
		if (Input::get('todate') == '') {
			$todate = date('j/n/Y');
		}
		$logs = Loginlog::whereRaw("created_at >= ? AND created_at <= ?", 
			array(DateTime::createFromFormat('j/n/Y', $fromdate)->format('Y-m-d') ." 00:00:00", DateTime::createFromFormat('j/n/Y', $todate)->format('Y-m-d') ." 23:59:59")
		)->get();		
		return View('logs.login')
			->with('fromdate', $fromdate)
			->with('todate', $todate)
			->with('title', "Login Logs")
			->with('logs', $logs);
	}
}
