@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $name }},</strong>
	<br/><br/>
	Our credit team has accepted your site visit request.
	<br/>
	The visit will be on {{ date("j/n/Y",strtotime($date)) }} at {{ $timeslot }}
	<br/>
	Attached is an ics file for that appointment which you can open in Outlook.
	<br/>
</p>
<br/>
@stop