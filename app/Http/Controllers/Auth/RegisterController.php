<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use DB;
use Mail;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use App\Jobs\Processregistration;
use App\Http\Requests\storeuserrequest;
use App\Helpers\AWSsmsHelper;
use App\Helpers\TwilioHelper;
use App\Phone;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
	 */

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|string|max:100',
			'title' => 'required|string|max:100',
			'email' => 'required|string|email|max:100|unique:users',
			'password' => 'required|string|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'title' => $data['title'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'isAdmin' => 1,
			'active' => 0,
			'created_by' => 0,
			'updated_by' => 0,
			'verified' => 0,
			'email_token' => str_random(20),
		]);
	}

	public function apiregister(storeuserrequest $request)
	{
		$user = new User;
		$user->name = $request->name;
		$user->title = $request->title;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->isAdmin = 1;
		$user->active = 0;
		$user->created_by = 0;
		$user->updated_by = 0;
		$user->verified = 0;
		$user->email_token = str_random(20);
		$user->save();
		$user->tenant_id = $user->id;
		$user->save();

		// Save phone record
		$phone = new Phone();
		$phone->user_id = $user->id;
		$phone->phone = "XXX";
		$AWSsmsHelper = new AWSsmsHelper();
		$phone->code = $AWSsmsHelper->generatePIN();
		//$phone->code = $this->generatePIN();
		$phone->save();
	}

	public function register(Request $request)
	{
		// Laravel validation
		$rules = [
			'name' => 'required|string|max:100',
			'title' => 'required|string|max:100',
			'email' => 'required|string|email|max:100|unique:users',
			'password' => 'required|string|min:6|confirmed',
		];
		$this->validate($request, $rules);
		
		// Using database transactions is useful here because stuff happening is actually a transaction
		// I don't know what I said in the last line! Weird!
		DB::beginTransaction();
		try {
			$user = $this->create($request->all());
			$user->tenant_id = $user->id;
			$user->save();

			// Save phone record
			$phone = new Phone();
			$phone->user_id = $user->id;
			$phone->phone = "XXX";
			$AWSsmsHelper = new AWSsmsHelper();
			$phone->code = $AWSsmsHelper->generatePIN();
			//$phone->code = $this->generatePIN();
			$phone->save();

			// After creating the user send an email with the random token generated in the create method above
			//$email = new EmailVerification(new User(['email_token' => $user->email_token, 'name' => $user->name]));
			//Mail::to($user->email)->send($email);			
			DB::commit();
			//Processregistration::dispatch(new EmailVerification(new User(['email_token' => $user->email_token, 'name' => $user->name])));
			Processregistration::dispatch($user);
			//return back();
			return view('message')->with('title', 'Registration')->with('message', 'Please check your email to confirm your account');
		} catch (Exception $e) {
			DB::rollback();
			return view('message')->with('title', 'Registration')->with('message', 'We have logged an error. We will get back to you soon.');
		}
	}

	public function verify($token)
	{
		$user = User::where('email_token', $token)->firstOrFail();

		return view('auth.verify', [
			'title' => 'Verify Your Account',
			'verificationToken' => $token
		]);
	}

	public function sendVerificationCode(Request $request)
	{
		//$phoneRegex = "/^\+\d+(-| )?\d+(-| )?\d+(-| )?\d+(-| )?\d+$/";
		$phoneRegex = "/^[\+|\(|\)|\d|\- ]*$/";
		// Laravel validation
		$rules = [
			'verificationToken' => 'required|string',
			'phone' => ['required', "regex:$phoneRegex"],
		];
		$this->validate($request, $rules);

		$token = $request->verificationToken;
		$phone = $request->phone;

		$user = User::where('email_token', $token)->firstOrFail();
		$userPhone = $user->phone();
		$userPhone->phone = $phone;
		$userPhone->save();

		// Send verification code to phone number
		$companyName = env('COMPANY_NAME', 'Bizzmo');
		$verificationCode = $user->phone()->code;
		$message = "$verificationCode is your $companyName verification code";
		$AWSsmsHelper = new AWSsmsHelper();
		if (env('SMS_PROVIDER') == 'aws') {
			$AWSsmsHelper->sendSMS($phone, $message);
		} else {
			$TwilioHelper = new TwilioHelper();
			$TwilioHelper->sendSMS($phone, $message);
		}
			
		$numberOfHiddenChars = strlen($phone) - 3;
		$stars = "";
		for ($i = 0; $i < $numberOfHiddenChars; $i++) {
			$stars .= "*";
		}
		$phone = substr_replace($phone, $stars, 0, $numberOfHiddenChars);


		return view('auth.submit_verification_code', [
			'title' => 'Verify Your Account',
			'verificationToken' => $token,
			'phone' => $phone
		]);
	}

	public function verifyAccount(Request $request)
	{
		$verificationToken = trim($request->input('verificationToken'));
		$verificationCode = trim($request->input('verificationCode'));
		$phone = trim($request->input('phone'));

		// Get user
		$user = User::where('email_token', $verificationToken)->first();

		if (isset($user)) {
			if ($verificationCode == $user->phone()->code) {
				$user->verified();
				$user->phone()->verified();
				return view('message', [
					'title' => 'Account Verified',
					'message' => 'Your account has been verified.',
					'home_link' => 'true'
				]);
			} else {
				return view('auth.submit_verification_code', [
					'title' => 'Verify Your Account',
					'verificationToken' => $verificationToken,
					'phone' => $phone,
					'error' => 'Invalid verification code'
				]);
			}
		} else {
			return view('auth.submit_verification_code', [
				'title' => 'Verify Your Account',
				'verificationToken' => $verificationToken,
				'phone' => $phone,
				'error' => 'Invalid verification token'
			]);
		}
	}

	// function generatePIN($digits = 4)
	// {
		// $i = 0; //counter
		// $pin = ""; //our default pin is blank.
		// while ($i < $digits) {
        // //generate a random number between 0 and 9.
			// $pin .= mt_rand(0, 9);
			// $i++;
		// }
		// return $pin;
	// }
}
