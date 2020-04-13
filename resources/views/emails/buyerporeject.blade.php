@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Dear {{ $company }},</strong>
	<br/><br/>
	Your order has not been accepted or was cancelled on Bizzmo.com. Be sure to search and explore other opportunities on the Bizzmo that would be right for you!
	<br/><br/>
	This transaction is subject to the agreement (“General Terms and Conditions of Sale”) signed by you and Bizzmo. For more information, please contact us by sending an e-mail to customerservices@bizzmo.com
	<br/><br/>

	<br/><br/>
	Thanks again for choosing Bizzmo. We appreciate your business.
	<br/><br/>
	
</p>
@stop