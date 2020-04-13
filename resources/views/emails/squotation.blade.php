@extends('emails.layout')
@section('content')
<p>
	<strong style="float: left">Dear {{ $company }},</strong>
	<br/><br/>
	Thank you for placing a request to sell on Bizzmo.com
	<br/>
	We have received and are processing your quotation. We wanted to take this opportunity to give you some important information.
	<br/><br/>
	<strong><b>Your quote information is listed below.</b></strong> Please verify that it is accurate. If it is incorrect or you need to change your order login to Bizzmo.com. If you <strong><b>did not</b></strong> make or authorize this transaction, please email us immediately at fraudrisk@bizzmo.com.
	<br/><br/>
	Once the order is processed and approved, you should receive another confirmation e-mail that your order has been accepted. To track your order and view your order details, login to <a href="{{ env('APP_URL') }}" target="_blank">Bizzmo.com</a>!
	<br/><br/>		

<?php
use App\Settings;

// Get basic info
$basicInfo = Settings::where('key', 'basicInfo')->first();
$basicInfo = json_decode($basicInfo->value);
?>


<?php
	$billTo = $quotation->getBillToAddress();
	$payer = $quotation->getPayerAddress();
	$soldTo = $quotation->getSoldToAddress();
	$shipTo = $quotation->getShipToAddress();
	$supplier = $quotation->getSupplierAddress();
?>


	<table width="100%">
		<tr>
			<td width="50%">
				<b class="text-blue" style="text-size: 64px">Issued On Bizzmo for Supplier {{ $quotation->vendor->companyname }}</b>
			</td>
			<td width="50%">
				<div style="float: right;text-align: right">
					<b class="text-blue" style="text-size: 32px">Supplier Quotation # {{ $quotation->vendor_id }}-{{ $quotation->number }}</b>
					<br>
					<b class="text-blue" style="text-size: 32px">TRN {{ $quotation->vendor->tax }}</b>
				</div>				
			</td>
		</tr>	
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<td width="50%">
				<div style="float: right;text-align: right">
					Quotation Date: {{ date('d.m.Y', strtotime($quotation->date)) }}
				</div>				
			</td>
		</tr>
	</table>
	<br/><br/><br/>
	<table width="100%">
		<tr>
			<!-- Ship To -->				
			<td width="50%">
				<table width="100%">
					<tr><td><strong><b>Ship To/Deliver To:</b></strong></td></tr>
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
					<tr><td><strong><b>Bill To/Payer:</b></strong></td></tr>
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
			<td width="50%">
				<table width="100%">
					<tr><td><strong><b>Sold To:</b></strong></td></tr>
					<tr><td>Customer Code: {{ $quotation->company->sapnumber }}</td></tr>
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
				<th width="35%" style="border-right-color: white;"><b>Product description</b></th>
				<th width="20%" style="border-right-color: white;"><b>MPN</b></th>
				<th width="15%"><b>Quantity</b></th>						
				<th width="15%"><b>Price</b></th>						
				<th width="15%"><b>Subtotal</b></th>						
			</tr>		
		</thead>
		<tbody>
			@php
				$total = 0;
			@endphp
			@foreach ($quotation->quotationitems as $quotationitem)
				@php
					$unit  = $quotationitem['unit'];
					$total =$total + $quotationitem['quantity'] * $quotationitem['price'];
				@endphp
				<tr>
					<td>{{ $quotationitem['productname'] }}</td>
					<td>{{ $quotationitem['mpn'] }}</td>
					<td style="text-align: right">{{ $quotationitem['quantity'] }}&nbsp;{{ $unit['abbreviation'] }}</td>
					<td style="text-align: right">{{ number_format($quotationitem['price'], 2, '.', ',') }}</td>
					<td style="text-align: right">{{ number_format($quotationitem['quantity'] * $quotationitem['price'], 2, '.', ',') }}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th style="text-align: left" colspan="4">Total</th>
				<th style="text-align: right">{{ number_format($total, 2, '.', ',') }}</th>
			</tr>
			<tr>
				<th style="text-align: left" colspan="4">VAT {{ number_format($quotation->vat, 2, '.', ',') }} %</th>
				<th style="text-align: right">{{ number_format($total * $quotation->vat / 100, 2, '.', ',') }}</th>
			</tr>
			<tr>
				<th style="text-align: left"  colspan="4">Grand total</th>
				<th style="text-align: right">{{ number_format($total * (100 + $quotation->vat) / 100, 2, '.', ',') }}</th>
			</tr>
			<tr>
				<th width="100%"  style="text-align: left;" colspan="5">
					Inco Terms: <b>{{ $quotation->incoterm['name']}}</b>
				</th>
			</tr>
			<tr>
				<th width="100%"  style="text-align: left;" colspan="5">
					Currency: <b>{{ $quotation->currency['abbreviation']}}</b>
				</th>
			</tr>				
			<tr>
				<th width="100%"  style="text-align: left;" colspan="5">
					Payment Terms: <b>{{ $quotation->vendor->vendorpaymentterm->name }}</b>
				</th>
			</tr>		
		</tfoot>
	</table>
	<br/><br/>
	
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