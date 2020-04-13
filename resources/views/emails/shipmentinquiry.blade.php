@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $company }},</strong>
	<br/><br/>
	You have a new shipment inquiry!
	<br/>
</p>
<br/>
@stop