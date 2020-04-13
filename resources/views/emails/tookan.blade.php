@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Dear {{ $company }},</strong>
	<br/><br/>
	
	@if ($job_type == 'Pickup' && $event == 'Started')
		Your order # {{ $purchaseorder->vendornumber }} pick up has started and is estimated to picked up by: {{ $purchaseorder->pickupbydate}} {{ $purchaseorder->pickupbytime->name }}
		<br/><br/>
		To view the details of your order or review your order history, visit <a href="{{ env('APP_URL') }}" target="_blank">Bizzmo.com</a> or track your order by clicking on {{ $purchaseorder->pickup_tracking_link }}
	@elseif ($job_type == 'Pickup' && $event == 'Successful')
		Your order # {{ $purchaseorder->company_id }}-{{ $purchaseorder->number }} has been picked from {{ $purchaseorder->vendor->companyname }} and is estimated to arrive by: {{ $purchaseorder->deliverbydate}} {{ $purchaseorder->deliverbytime->name }}
		<br/><br/>
		To view the details of your order or review your order history, visit <a href="{{ env('APP_URL') }}" target="_blank">Bizzmo.com</a> or track your order by clicking on {{ $purchaseorder->delivery_tracking_link }}
		<br/><br/>
		Upon delivery, you should inspect the goods for any defects or nonconformity, and if any, then do not sign the attached Proof of Delivery. By signing the attached Proof of Delivery, you will have accepted delivery and acknowledge taking receipt of the goods as attached. You hereby acknowledge that you inspected the shipment and exerted all the needed due diligence and confirm, accordingly, the conformity of the goods in quantity and quality with the placed purchase order. You hereby acknowledge that the goods as delivered are free from any apparent defect. You do accept that any hidden manufacturing or industrial defect should not be the responsibility of Bizzmo. You hereby waive any right of recourse, claim, setting off or request of compensation, against Bizzmo for quantitative or qualitative non-conformity with respect to the delivered goods.
	@elseif ($job_type == 'Delivery' && $event == 'Successful')
		@if ($company == $purchaseorder->company->companyname)
			Your order # {{ $purchaseorder->company_id }}-{{ $purchaseorder->number }} has been successfully delivered. 			
		@else
			Your order # {{ $purchaseorder->vendornumber }} has been successfully delivered to {{ $purchaseorder->company->companyname }}. 			
		@endif
		<br/><br/>
		To view the details of your order or review your order history, visit <a href="{{ env('APP_URL') }}" target="_blank">Bizzmo.com</a>.
	@endif
	
</p>

<br/><br/>
Thanks again for choosing Bizzmo. We appreciate your business.
<br/><br/>

@stop