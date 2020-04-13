<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'oldPassword' => 'required',
			'newPassword' => 'required|string|min:6|confirmed'
		];
	}

	/**
	 * Get the validation messages that apply to the request.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [];
	}

	/**
	 * Configure the validator instance.
	 *
	 * @param  \Illuminate\Validation\Validator  $validator
	 * @return void
	 */
	public function withValidator($validator)
	{
		$validator->after(function ($validator) {
			if ($this->has('oldPassword') && !Hash::check($this->oldPassword, \Auth::user()->password)) {
				$validator->errors()->add('oldPassword', 'Old password is incorrect');
			}
		});
	}
}
