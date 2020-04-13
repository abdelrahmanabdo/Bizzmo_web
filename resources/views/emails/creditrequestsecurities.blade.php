<?php
$style = [
    /* Layout ------------------------------ */
    'body' => 'margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;',
    'email-wrapper' => 'width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;',
    /* Masthead ----------------------- */
    'email-masthead' => 'padding: 25px 0; text-align: center;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',
    'email-body' => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
    'email-body_inner' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0;',
    'email-body_cell' => 'padding: 35px;',
    'email-footer' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
    'email-footer_cell' => 'color: #AEAEAE; padding: 35px; text-align: center;',
    /* Body ------------------------------ */
    'body_action' => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
    'body_sub' => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',
    /* Type ------------------------------ */
    'anchor' => 'color: #10476a;',
    'header-1' => 'margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;',
    'paragraph' => 'margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;',
    'paragraph-sub' => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
    'paragraph-center' => 'text-align: center;',
    /* Buttons ------------------------------ */
    'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #10476a; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',
    'button--green' => 'background-color: #22BC66;',
    'button--red' => 'background-color: #dc4d2f;',
    'button--blue' => 'background-color: #10476a;',
];

$fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;'; 
?>

@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $name }},</strong>
	<br/><br/>
	@if ($document == 'Security check')
        To be able to approve your credit request number {{ $id }}, please prepare a check for an amount of {{ $currency }} {{ number_format($amount, 2, '.', ',') }}.
		We will collect it on {{ date_format(date_create($check->pickupbydate), 'j/n/Y') }} {{ $check->pickupbytime->name }}. If you want to change the pickup time, click <a href="{{ env('APP_URL') }}/checkpickup/{{ $check->id }}/{{ $check->authcode }}" target="_blank">here</a>
		<br/><br/>
		Please issue the check in the name of "Bizzmo FZE". 
	@elseif ($document == 'Margin Deposit Cash')
        To be able to approve your credit request number {{ $id }}, please send us a Margin Deposit Cash for an amount of {{ $currency }} {{ number_format($amount, 2, '.', ',') }}. 
		<br/><br/>
		Please issue the check in the name of "Bizzmo FZE". 
    @else
        To be able to approve your credit request, click <a href="http://projectx.metragroup.com/signing/{{ $verificationcode }}">here</a> to sign the {{ $document }}.  
    @endif	
</p>
<br/>
@stop