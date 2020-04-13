<?php
$style = [
	'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #10476a; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;'
];
?>
@extends('emails.layout')
@section('content')
	<div>
		Thanks for signing up.
		<br/><br/>
		<span>
			Please confirm your email address to get full access to Bizzmo.
		</span>
		<br/><br/><br/>
		<a href="{{ config('app.webaddress') }}/register/verify/{{ $email_token }}"
			style="{{ $style['button'] }}"
			class="button"
			target="_blank">
			Verify
		</a>
	</div>
@stop