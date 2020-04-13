@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Dear {{ $company }},</strong>
	<br/><br/>
	Congratulations! You have successfully negotiated a deal on Bizzmo.com
	<br/><br/>
	We'd like to thank you again for your order. Your order # {{ $purchaseorder->vendornumber }} (ver. {{ $purchaseorder->version }}) has now been accepted! Below you will find your order details.
	<br/><br/>



<?php
use App\Settings;

// Get basic info
$basicInfo = Settings::where('key', 'basicInfo')->first();
$basicInfo = json_decode($basicInfo->value);
?>


<?php
	$billTo = $purchaseorder->getBillToAddress();
	$payer = $purchaseorder->getPayerAddress();
	$soldTo = $purchaseorder->getSoldToAddress();
	$shipTo = $purchaseorder->getShipToAddress();
	$supplier = $purchaseorder->getSupplierAddress();
?>
	
	<table width="100%">
		<tr>
			<td width="50%" style="vertical-align: top;">
				<img src="{{ asset('images/logo-with-name.png') }}" class="pull-left logo" width="200">
			</td>
			<td width="50%" style="vertical-align: top;">
				<div style="float: right;text-align: right">
					<b class="text-blue">Bizzmo PO #  {{ $purchaseorder->vendornumber }} (ver. {{ $purchaseorder->version }})</b>
					<br><span>TRN# {{ $basicInfo->tax }}</span>
				</div>
			</td>
		</tr>		
		<tr><td>&nbsp;</td></tr>			
		<tr>
			<td width="50%">&nbsp;</td>
			<td width="50%" style="vertical-align: top;">
				<table width="100%" style="text-align: right">
					<tr><td>PO Date: {{ date('d.m.Y', strtotime($purchaseorder->date)) }}</td></tr>
					<tr><td>SO No.:{{ $purchaseorder->salesorder }}</td></tr>
					<tr><td>SO Date: {{ date('d.m.Y', strtotime($purchaseorder->date)) }}</td></tr>
				</table>
			</td>
		</tr>		
	</table>
	<br/><br/><br/>
	
	<table width="100%">
		<tr>
			<!-- Bill To -->
			<td width="50%" style="vertical-align: top;">
				<table width="100%">
					<tr><td><b><strong>Bill To/Payer:</strong></b></td></tr>
					<tr><td>Bizzmo</td></tr>
						<tr><td>POBox 61188, Jebel Ali</td></tr>
						<tr><td>Dubai, United Arab Emirates</td></tr>
						<tr><td>Tel: +97148863360 </td></tr>
						<tr><td>Fax: +97148863656</td></tr>
						<tr><td>TRN: {{ $basicInfo->tax }}</td></tr>
				</table>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<!-- Ship To -->				
			<td width="50%" style="vertical-align: top;">
				<table width="100%">
					<tr><td><b><strong>Ship To/Deliver To:</strong></b></td></tr>					
					<tr><td>{{ $shipTo->party_name }}</td></tr>
					<tr><td>{{ $shipTo->address }}</td></tr>
					<tr><td><?= isset($shipTo->address) ? "PO Box: $shipTo->address, " : "" ?>{{  $shipTo->city }}, {{  $shipTo->country }}</td></tr>
					<tr><td>Tel: {{ $shipTo->phone }}</td></tr>
					<tr><td>Fax: {{ $shipTo->fax }}</td></tr>
					<tr><td>Delivery Location: {{ $shipTo->delivery_address }}</td></tr>
					<tr><td>{{ $shipTo->delivery_city }}</td></tr>
					<tr><td>{{ $shipTo->delivery_country }}</td></tr>
				</table>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<!-- Sold To -->								
			<td width="50%">
				<table width="100%">
					<tr><td><strong><b>Sold To:</b></strong></td></tr>
					<tr><td>Customer Code: {{ $purchaseorder->company->sapnumber }}</td></tr>
					<tr><td>TRN#: {{ $soldTo->tax }}</td></tr>
					<tr><td>{{ $soldTo->party_name }}</td></tr>
					<tr><td>{{ $soldTo->address }}, {{ $soldTo->district }}</td></tr>
					<tr><td>{{ $soldTo->city }}, {{ $soldTo->country }}</td></tr>
					<tr><td>Tel: {{ $soldTo->phone }}</td></tr>
					<tr><td>Fax: {{ $soldTo->fax }}</td></tr>
				</table>
			</td>
		</tr>
	</table>

	<br/><br/><br/>
	<table width="100%" class="detailstable" cellspacing="0" border="1">
		<thead>
			<tr>
				<th width="45%" class="labels detailscell" style="border-right-color: white;"><b>Product description</b></th>
				<th width="35%" class="labels detailscell" style="border-right-color: white;"><b>MPN</b></th>
				<th width="15%" class="labels detailscell"><b>Quantity</b></th>						
				<th width="15%" class="labels detailscell"><b>Price</b></th>						
				<th width="15%" class="labels detailscell"><b>Subtotal</b></th>						
			</tr>		
		</thead>
		<tbody>
			@php
				$total = 0;
			@endphp
			@foreach ($purchaseorder->purchaseorderitems as $purchaseorderitem)
				@php
					$unit  = $purchaseorderitem['unit'];
					$total =$total + $purchaseorderitem['quantity'] * $purchaseorderitem['price'];
				@endphp
				<tr>
					<td class="labels detailscell">{{ $purchaseorderitem['productname'] }}</td>
					<td class="labels detailscell">{{ $purchaseorderitem['mpn'] }}</td>
					<td class="labels detailscell" style="text-align: right">{{ $purchaseorderitem['quantity'] }}&nbsp;{{ $unit['abbreviation'] }}</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($purchaseorderitem['price'], 2, '.', ',') }}</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($purchaseorderitem['quantity'] * $purchaseorderitem['price'], 2, '.', ',') }}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<?php
				$vatVal = 1 * number_format(($total) * $purchaseorder->vat / 100, 2, '.', '');
				$grandTotal = $total + $vatVal;
			?>
			<tr>
				<th class="labels detailscell" style="text-align: left" colspan="4">Total</th>
				<th class="labels detailscell" style="text-align: right">{{ number_format($total, 2, '.', ',') }}</th>
			</tr>
			<tr>
				<th class="labels detailscell" style="text-align: left" colspan="4">VAT {{ $purchaseorder->vat }}%</th>
				<th class="labels detailscell" style="text-align: right">{{ number_format($vatVal, 2, '.', ',') }}</th>
			</tr>
			<tr>
				<th class="labels detailscell" style="text-align: left" colspan="4">Grand total</th>
				<th class="labels detailscell" style="text-align: right">{{ number_format($grandTotal, 2, '.', ',') }}</th>
			</tr>
			<tr>
				<th width="100%" class="labels detailscell" style="text-align: left;" colspan="5">
					Inco Terms: <b>{{ $purchaseorder->incoterm['name']}}</b>
				</th>
			</tr>
			<tr>
				<th width="100%" class="labels detailscell" style="text-align: left;" colspan="5">
					Currency: <b>{{ $purchaseorder->currency['abbreviation']}}</b>
				</th>
			</tr>	
			<tr>
				<th width="100%" class="labels detailscell" style="text-align: left;" colspan="5">
					Payment Terms: <b>{{ $purchaseorder->vendor->vendorpaymentterm->name }}</b>
				</th>
			</tr>		
		</tfoot>
	</table>
	<br/><br/><br/>
	<b><strong>Important things to know:</strong></b>
	<ul>
		<li>Your sale is subject to the agreement (“Supply Agreement - General Terms and Conditions of Sale”) signed by you and Bizzmo.</li>
		<li>Bizzmo cannot be responsible for pricing or other errors, and reserves the right to cancel any orders arising from such errors.</li>
		<li>Each order number represents a separate purchase and will be processed and submitted for payment authorization separately.</li>
	</ul>
	<br/><br/>
	Thanks again for choosing Bizzmo. We appreciate your business.
	<br/><br/>
	
</p>
@stop