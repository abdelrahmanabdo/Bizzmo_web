@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $company }},</strong>
	<br/><br/>
	Invoice <strong>{{ $number }}</strong> is ready. 
</p>
@stop