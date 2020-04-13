<?php
use App\Settings;

// Get basic info
$basicInfo = Settings::where('key', 'basicInfo')->first();
$basicInfo = json_decode($basicInfo->value);
?>

@include('pdfs.pdfstyle')

<?php
	$billTo = $quotation->getBillToAddress();
	$payer = $quotation->getPayerAddress();
	$soldTo = $quotation->getSoldToAddress();
	$shipTo = $quotation->getShipToAddress();
	$supplier = $quotation->getSupplierAddress();
?>

<body>
	@include('pdfs.headfoot', [
		'docnum' => $quotation->vendor_id . '-' . $quotation->number . ' (ver. ' . $quotation->version . ')' , 
		'doctitle' => 'Quotation', 
		'docname' => 'Supplier Quotation',
		'suppliername' => $quotation->vendor->companyname,
		'supplieraddress' => $supplier->address . ', ' . $supplier->city . ', ' . $supplier->country,
		'supplierphone' => $quotation->vendor->phone,
		'supplierfax' => $quotation->vendor->fax,
		'TRN' => $quotation->vendor->tax,
	])		
	<div class="container">
		<table width="100%">
			<tr>
				<!-- Sold To -->								
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Sold To:</b></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Customer Code: {{ $quotation->company->sapnumber }}</td></tr>
						<tr><td>TRN#: {{ $soldTo->tax }}</td></tr>
						<tr><td>{{ $soldTo->party_name }}</td></tr>
						<tr><td>{{ $soldTo->address }}, {{ $soldTo->district }}</td></tr>
						<tr><td>{{ $soldTo->city }}, {{ $soldTo->country }}</td></tr>
						<tr><td>Tel: {{ $soldTo->phone }}</td></tr>
						<tr><td>Fax: {{ $soldTo->fax }}</td></tr>
					</table>
				</td>
				<!-- Ship To -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Ship To/Deliver To:</b></td></tr>
						<tr><td>&nbsp;</td></tr>		
						<tr><td>{{ $shipTo->party_name }}</td></tr>
						<tr><td>{{ $shipTo->address }}</td></tr>
						<tr><td><?= isset($shipTo->address) ? "PO Box: $shipTo->address, " : "" ?>{{  $shipTo->city }}, {{  $shipTo->country }}</td></tr>
						<tr><td>Tel: {{ $shipTo->phone }}</td></tr>
						<tr><td>Fax: {{ $shipTo->fax }}</td></tr>
						<tr><td>Delivery Location: {{ $shipTo->delivery_address }}</td></tr>
						<tr><td>{{ $shipTo->delivery_city }}</td></tr>
						<tr><td>{{ $shipTo->delivery_country }}</td></tr>
						<tr><td>&nbsp;</td></tr>
					</table>
				</td>
				<!-- P.O INFO -->
				<td width="33%" style="vertical-align: top;">
					<table width="100%" style="text-align: right">
						<tr><td>Quotation Date: {{ date('d.m.Y', strtotime($quotation->date)) }}</td></tr>
					</table>
				</td>				
			</tr>
			<tr><td>&nbsp;</td></tr>		
			<tr>				
				<!-- Bill To -->								
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Bill To/Payer:</b></td></tr>
						<tr><td>&nbsp;</td></tr>						
						<tr><td>Bizzmo</td></tr>
						<tr><td>POBox 61188, Jebel Ali</td></tr>
						<tr><td>Dubai, United Arab Emirates</td></tr>
						<tr><td>Tel: +97148863360 </td></tr>
						<tr><td>Fax: +97148863656</td></tr>
						<tr><td>TRN: {{ $basicInfo->tax }}</td></tr>
					</table>
				</td>				
			</tr>
		</table>
		<br/><br/>
		<table width="100%" class="detailstable" cellspacing="0" style="font-size: 10px !important">
			<thead>
				<tr>
					<th width="45%" class="labels detailscell" style="border-right-color: white;"><b>Product description</b></th>
					<th width="25%" class="labels detailscell" style="border-right-color: white;"><b>MPN</b></th>
					<th width="15%" class="labels detailscell" style="border-right-color: white;"><b>Brand</b></th>
					<th width="10%" class="labels detailscell"><b>Quantity</b></th>						
					<th width="10%" class="labels detailscell"><b>Price</b></th>						
					<th width="15%" class="labels detailscell"><b>Subtotal</b></th>						
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
						<td class="labels detailscell" style="text-align: left">{{ $quotationitem['productname'] }}</td>
						<td class="labels detailscell" style="text-align: left">{{ $quotationitem['mpn'] }}</td>
						<td class="labels detailscell" style="text-align: left">{{ $quotationitem->brand->name }}</td>
						<td class="labels detailscell" style="text-align: right">{{ $quotationitem['quantity'] }}&nbsp;{{ $unit['abbreviation'] }}</td>
						<td class="labels detailscell" style="text-align: right">{{ number_format($quotationitem['price'], 2, '.', ',') }}</td>
						<td class="labels detailscell" style="text-align: right">{{ number_format($quotationitem['quantity'] * $quotationitem['price'], 2, '.', ',') }}</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th class="labels detailscell" style="text-align: left;" colspan="5">Total</th>
					<th class="labels detailscell" style="text-align: right">{{ number_format($total, 2, '.', ',') }}</th>
				</tr>
				<tr>
					<th class="labels detailscell" style="text-align: left;" colspan="5">VAT {{ number_format($quotation->vat, 2, '.', ',') }} %</th>
					<th class="labels detailscell" style="text-align: right">{{ number_format($total * $quotation->vat / 100, 2, '.', ',') }}</th>
				</tr>
				<tr>
					<th class="labels detailscell" style="text-align: left;" colspan="5">Grand total</th>
					<th class="labels detailscell" style="text-align: right">{{ number_format($total * (100 + $quotation->vat) / 100, 2, '.', ',') }}</th>
				</tr>
				<tr>
					<th width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Inco Terms: <b>{{ $quotation->incoterm['name']}}</b>
					</th>
				</tr>
				<tr>
					<th width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Currency: <b>{{ $quotation->currency['abbreviation']}}</b>
					</th>
				</tr>				
				<tr>
					<th width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Payment Terms: <b>{{ $quotation->vendor->vendorpaymentterm->name }}</b>
					</th>
				</tr>		
			</tfoot>
		</table>
		<br/><br/>
		<div style="color: gray;font-size: 8.5px !important;">
			<p class="para">These commodities, technology or software were exported in accordance with the US Export Administration Regulations. Diversion contrary to U.S. law prohibited. The purchaser agrees to
				indemnify the seller and hold the seller harmless from and against all claims, liability, and obligation whatsoever (including, but not limited to, reasonable attorneys’ fees) arising out of the
				transfer of these commodities across national boundaries without proper government licenses and authorizations. Reexport/retransfer without prior authorization from the US Bureau of Export
				Administration is prohibited. Export, reexport, sale or retransfer to military end-users or end-uses in prohibited destinations and proliferation end-users and end-uses is strictly prohibited without
				prior authorization from the US government.</p>
			<p class="para">You agree that you have reviewed the Bizzmo Supply Agreement -  General terms and conditions of sale and that your sales is subject to these T’s and C’s.</p>
			<p class="para">This document is Bizzmo’s system generated Document and does not require anyone's signature nor Bizzmo’s stamp on it.</p>	
		</div>

	</div>	
</body>