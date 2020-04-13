<?php
$style = [
	'reset-table-container' => 'font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box; margin-bottom: 30px; margin-top: 30px',
	'reset-table' => 'font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box;border-radius: 3px;box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);color: #FFF;display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;background-color: #3097D1;border-top: 10px solid #3097D1;border-right: 18px solid #3097D1;border-bottom: 10px solid #3097D1;border-left: 18px solid #3097D1'
];
$resetLink = url(config('app.url') . route('password.reset', $token, false));
?>
@extends('emails.layout')
@section('content')
<div>
	<strong style="float: left">Hello,</strong>
	<br/>
	<p>
		You are receiving this email because we received a password reset request for your account.
	</p>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td align="center" style="font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box">
					<table border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box">
						<tbody>
							<tr>
								<td style="font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box">
									<a href="{{$resetLink}}" style="{{$style['reset-table']}}" target="_blank" tabindex="-1" rel="external">Reset Password</a>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<br/>

	<p>
		If you did not request a password reset, no further action is required.
	</p>
	<p>
		If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: 
		{{ $resetLink }}
	</p>
</div>
@stop