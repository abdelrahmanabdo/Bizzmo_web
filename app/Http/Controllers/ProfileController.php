<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Phone;
use App\Http\Requests\ChangePasswordRequest;


class ProfileController extends Controller
{
	public function index()
	{
		$user = Auth::user();

		return View('auth.profile.view')
			->with('title', "Profile")
			->with('user', $user)
			->render();
	}

	public function editProfile()
	{
		$user = Auth::user();

		return View('auth.profile.edit')
			->with('title', "Edit Profile")
			->with('user', $user);
	}

	public function saveProfile(Request $request)
	{
		//$phoneRegex = "/^\+\d+(-| )?\d+(-| )?\d+(-| )?\d+(-| )?\d+$/";
		$phoneRegex = "/^[\+|\(|\)|\d|\- ]*$/";
		$rules = [
			'name' => 'required|string|max:100',
			'title' => 'required|string|max:100',
			'phone' => ['required', "regex:$phoneRegex"],
		];
		$this->validate($request, $rules);
		
		$user = Auth::user();
		$user->name = $request->name;
		$user->title = $request->title;

		$userPhone = $user->phone();
		if($userPhone) {
			$userPhone->phone = $request->phone;
			$userPhone->save();
		} else {
			$phone = new Phone();
			$phone->user_id = $user->id;
			$phone->phone = $request->phone;
			$phone->code = $this->generatePIN();
			$phone->save();
		}
		$user->save();
		
		return View('auth.profile.edit')
			->with('title', "Edit Profile")
			->with('user', $user)
			->with('message', 'Changes saved successfully');
	}

	public function changePassword()
	{
		return View('auth.profile.change_password')
			->with('title', "Change Password");
	}

	public function savePassword(ChangePasswordRequest $request)
	{	
		$user = Auth::user();
		$user->password = bcrypt($request->newPassword);
		$user->save();
		
		return View('auth.profile.change_password')
			->with('title', "Change Profile")
			->with('message', 'Password changed successfully');
	}

	function generatePIN($digits = 4)
	{
		$i = 0; //counter
		$pin = ""; //our default pin is blank.
		while ($i < $digits) {
        //generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return $pin;
	}
}
