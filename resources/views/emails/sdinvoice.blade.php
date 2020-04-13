@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Hello {{ $company }},</strong>
	<br/><br/>
	Invoice <strong>{{ $number }}</strong> is ready.
	<br/><br/>
	Please make payment to Standard Chartered Bank, Dubai, UAE, in accordance with the below instructions:
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;1)	For USD transfers
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;Account Name: Bizzmo FZE
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;Account Number: 01-3898865-01 
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;IBAN: AE900440000101389886501
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;2)	For AED transfers
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;Account Name: Bizzmo FZE
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;Account Number: 01-3898865-01
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;IBAN: AE310440000001389886501

</p>
@stop