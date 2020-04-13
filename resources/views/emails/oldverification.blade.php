<?php
$style = [
	'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',
	'button--blue' => 'background-color: #3869D4;',
];

$fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;'; 

?>

@extends('emails.layout')
@section('content')
<div>
	<strong>Hello {{ $name }},</strong>
	<br/>
	<p>Thanks for signing up.</p>
	<p>Please confirm your email address to get full access to Bizzmo.</p>
	<p>Click on the below button to verify your email address </p>
	<table align="center" width="100%" cellpadding="0" cellspacing="0">
			<tr>
					<td align="center">
							<?php $actionColor = 'button--blue'; ?>

							<a href="{{ 'http://projectx.metragroup.com/register/verify/'.$email_token }}"
									style="{{ $fontFamily }} {{ $style['button'] }} {{ $style[$actionColor] }}"
									class="button"
									target="_blank">
									Verify
							</a>
					</td>
			</tr>
	</table>
</div>
@stop