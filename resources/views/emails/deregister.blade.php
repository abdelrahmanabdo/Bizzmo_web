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
		<strong style="float: left">Hello {{ $company }},</strong>
		<br/><br/>
		<p>We have received a request from you to deregister your company. Once deregistered, the company account will be disabled and you will have to register again to activate the account.</p>
		<br/><br/>
		<p>If you did not ask for the deregistration, please send us an email on  <a href="mailto:fraud@bizzmo.com">fraud@bizzmo.com</a></p>
		<br/><br/>
		<span>
			Click the below link to deregister.
		</span>
		<br/><br/><br/>
		<a href="{{ config('app.webaddress') }}/?id={{ $id }}&token={{ $token }}"
			style="{{ $style['button'] }}"
			class="button"
			target="_blank">
			Deregister
		</a>
		<br/><br/>
	</div>
@stop