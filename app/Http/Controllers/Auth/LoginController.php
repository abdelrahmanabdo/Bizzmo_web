<?php

namespace App\Http\Controllers\Auth;

//use Request;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Actiontoken;
use App\Company;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
	protected $loginPath = '/home';
	//following line added by Sherif to set the maximum allowed login attemps to 10
	private $maxLoginAttempts = 10;

	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	public function logout(Request $request)
    {
        $this->guard()->logout();
        //$request->session()->flush();
        //$request->session()->regenerate();
        return redirect('/');
    }
	
	public function authenticated(Request $request, $user)
    {
        $id = $request->id;
		$token = $request->token;
		if ($id && $token) {
			session(['id' => $id, 'token' => $token]);			
		}
    }
		
	public function showLoginForm(Request $request)
    {
        //$this->guard()->logout();
        //$request->session()->flush();
        //$request->session()->regenerate();
        return redirect('/');
    }
	
	public function credentials(Request $request)
	{
		return [
			'email' => $request->email,
			'password' => $request->password,
			'verified' => 1,
		];
	}

}
