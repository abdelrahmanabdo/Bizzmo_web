@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $company }},</strong>
	<br/><br/>
    Delivery note <strong>{{ $number }}</strong> is ready. 
    <p>To acknowledge reciept, click <a href="http://projectx.metragroup.com/dsigning/{{ $authcode }}">here</a> to sign the delivery.</p>
</p>
@stop