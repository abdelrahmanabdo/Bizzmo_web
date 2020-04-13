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
	@include('pdfs.headfoot', ['docnum' => $quotation->vendornumber . ' (ver. ' . $quotation->version . ')' , 'doctitle' => 'Quotation', 'docname' => 'Bizzmo Quotation' ])		
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
						<tr><td>Customer Code: {{ $quotation->company->sapnumber }}</td></tr>
						<tr><td>TRN#: {{ $billTo->tax }}</td></tr>
						<tr><td>{{ $billTo->party_name }}</td></tr>
						<tr><td>{{ $billTo->address }}, {{ $billTo->district }}</td></tr>
						<tr><td>{{ $billTo->city }}, {{ $billTo->country }}</td></tr>
						<tr><td>Tel: {{ $billTo->phone }}</td></tr>
						<tr><td>Fax: {{ $billTo->fax }}</td></tr>
					</table>
				</td>		
				<!-- Payer -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Supplier:</b></td></tr>
						<tr><td>&nbsp;</td></tr>						
						<tr><td>Vendor Code: {{ $quotation->vendor->sapvendornumber }}</td></tr>
						<tr><td>TRN#: {{ $supplier->tax }}</td></tr>
						<tr><td>{{ $supplier->party_name }}</td></tr>
						<tr><td>{{ $supplier->address }}, {{ $supplier->district }}</td></tr>
						<tr><td>{{ $supplier->city }}, {{ $supplier->country }}</td></tr>
						<tr><td>Tel: {{ $supplier->phone }}</td></tr>
						<tr><td>Fax: {{ $supplier->fax }}</td></tr>
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
					<th width="10%" class="labels detailscell"><b>Subtotal</b></th>						
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
				<?php
					$buyupVal = 1 * number_format($total * $quotation->buyup / 100, 2, '.', '');
					$vatVal = 1 * number_format(($total + $buyupVal) * $quotation->vat / 100, 2, '.', '');
					$grandTotal = $total + $buyupVal + $vatVal;
				?>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">Total</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($total, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">Fees {{ $quotation->buyup }}%</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($buyupVal, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">VAT {{ $quotation->vat }}%</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($vatVal, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">Grand total</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($grandTotal, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Inco Terms: <b>{{ $quotation->incoterm['name']}}</b>
					</td>
				</tr>
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Currency: <b>{{ $quotation->currency['abbreviation']}}</b>
					</td>
				</tr>	
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Payment Terms: <b>{{ $quotation->paymentterm['name']}}</b>
					</td>
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