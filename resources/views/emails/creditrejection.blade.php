@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $name }},</strong>
	<br/><br/>
	Thanks for applying for a credit line.<br>
	<p>
		We regret to inform you that your request for a credit line of {{ $askedlimit }} has been declined
	</p>
</p>
@stop