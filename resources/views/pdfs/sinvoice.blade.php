<?php
use App\Settings;

// Get basic info
$basicInfo = Settings::where('key', 'basicInfo')->first();
$basicInfo = json_decode($basicInfo->value);
?>
 
@include('pdfs.pdfstyle')

<?php
	$billTo = $purchaseOrder->getBillToAddress();
	$payer = $purchaseOrder->getPayerAddress();
	$soldTo = $purchaseOrder->getSoldToAddress();
	$shipTo = $purchaseOrder->getShipToAddress();
	$supplier = $purchaseOrder->getSupplierAddress();
?>

<body>
	@include('pdfs.headfoot', [
		'docnum' => $purchaseOrder->vendor_id . '-' . $purchaseOrder->sinvoice, 
		'doctitle' => 'Invoice', 
		'docname' => 'Tax Invoice',
		'suppliername' => $purchaseOrder->vendor->companyname,
		'supplieraddress' => $supplier->address . ', ' . $supplier->city . ', ' . $supplier->country,
		'supplierphone' => $purchaseOrder->vendor->phone,
		'supplierfax' => $purchaseOrder->vendor->fax,
		'TRN' => $purchaseOrder->vendor->tax,
		])
	<div class="container">		
		<table width="100%">
			<tr>
				<!-- Sold To -->
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Sold To:</b></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Customer Code: {{ $purchaseOrder->company->sapnumber }}</td></tr>
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
				<td width="33%" style="vertical-align: top;">
					<table width="100%" style="text-align: right">
						<tr><td>Invoice Date: {{ date('d.m.Y', strtotime($purchaseOrder->sinvoicedate)) }}</td></tr>
						<tr><td>PO No.: {{ $purchaseOrder->vendornumber }} (ver. {{ $purchaseOrder->version }})</td></tr>
						<tr><td>PO Date: {{ date('d.m.Y', strtotime($purchaseOrder->date)) }}</td></tr>
						<tr><td>SO No. :{{ $purchaseOrder->salesorder }}</td></tr>
						<tr><td>SO Date: {{ date('d.m.Y', strtotime($purchaseOrder->date)) }}</td></tr>
						<tr><td>Delivery No: {{ $purchaseOrder->delivery }}</td></tr>
						<tr><td>Due Date: {{ date('j/n/Y', strtotime($purchaseOrder->signed_at . ' + ' . $purchaseOrder->vendorpaymentterm['days'] . ' days')) }}</td></tr>
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
		<br/><br/><br/>
		<table width="100%" class="detailstable" cellspacing="0" style="font-size: 10px !important">
			<thead>
				<tr>
					<th width="45%" class="labels detailscell" style="border-right-color: white;"><b>Product description</b></th>
					<th width="35%" class="labels detailscell" style="border-right-color: white;"><b>MPN</b></th>
					<th width="35%" class="labels detailscell" style="border-right-color: white;"><b>Brand</b></th>
					<th width="15%" class="labels detailscell"><b>Quantity</b></th>						
					<th width="15%" class="labels detailscell"><b>Price</b></th>						
					<th width="15%" class="labels detailscell"><b>Subtotal</b></th>						
				</tr>		
			</thead>
			<tbody>
				@php
					$total = 0;
				@endphp
				@foreach ($purchaseOrder->purchaseorderitems as $purchaseorderitem)
					@php
						$unit  = $purchaseorderitem['unit'];
						$total =$total + $purchaseorderitem['quantity'] * $purchaseorderitem['price'];
					@endphp
					<tr>
						<td class="labels detailscell" style="text-align: left">{{ $purchaseorderitem['productname'] }}</td>
						<td class="labels detailscell" style="text-align: left">{{ $purchaseorderitem['mpn'] }}</td>
						<td class="labels detailscell" style="text-align: left">{{ $purchaseorderitem->brand->name }}</td>
						<td class="labels detailscell" style="text-align: right">{{ $purchaseorderitem['quantity'] }}&nbsp;{{ $unit['abbreviation'] }}</td>
						<td class="labels detailscell" style="text-align: right">{{ number_format($purchaseorderitem['price'], 2, '.', ',') }}</td>
						<td class="labels detailscell" style="text-align: right">{{ number_format($purchaseorderitem['quantity'] * $purchaseorderitem['price'], 2, '.', ',') }}</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th class="labels detailscell" style="text-align: left;" colspan="5">Total</th>
					<th class="labels detailscell" style="text-align: right">{{ number_format($total, 2, '.', ',') }}</th>
				</tr>
				<tr>
					<th class="labels detailscell" style="text-align: left;" colspan="5">VAT {{ number_format($purchaseOrder->vat, 2, '.', ',') }} %</th>
					<th class="labels detailscell" style="text-align: right">{{ number_format($total * $purchaseOrder->vat / 100, 2, '.', ',') }}</th>
				</tr>
				<tr>
					<th class="labels detailscell" style="text-align: left;" colspan="5">Grand total</th>
					<th class="labels detailscell" style="text-align: right">{{ number_format($total * (100 + $purchaseOrder->vat) / 100, 2, '.', ',') }}</th>
				</tr>
				<tr>
					<th width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Inco Terms: <b>{{ $purchaseOrder->incoterm['name']}}</b>
					</th>
				</tr>
				<tr>
					<th width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Currency: <b>{{ $purchaseOrder->currency['abbreviation']}}</b>
					</th>
				</tr>				
				<tr>
					<th width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Payment Terms: <b>{{ $purchaseOrder->vendorpaymentterm->name }}</b>
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
			<p class="para">You agree that you have reviewed the Bizzmo Standard terms and conditions of sale and that your purchase is subject to these T’s and C’s.</p>
			<p class="para">This document is Bizzmo’s system generated Document and does not require anyone's signature nor Bizzmo’s stamp on it.</p>	
		</div>

	</div>	
</body>