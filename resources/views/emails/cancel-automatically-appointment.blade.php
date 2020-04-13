@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $company }},</strong>
	<br/><br/>
    <p>Your <strong>{{$date}}</strong> appointment has been automatically cancelled</p>
</p>
@stop