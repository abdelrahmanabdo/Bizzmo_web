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
	@include('pdfs.headfoot', ['docnum' => $purchaseOrder->binvoice, 'doctitle' => 'Invoice', 'docname' => 'Tax Invoice' ])
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
						<tr><td>{{ $shipTo->delivery_inco }}</td></tr>
						<tr><td>&nbsp;</td></tr>
					</table>
				</td>
				<!-- P.O INFO -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%" style="text-align: right">
						<tr><td>Invoice Date: {{ date('d.m.Y', strtotime($purchaseOrder->binvoicedate)) }}</td></tr>
						<tr><td>P.O NO: {{ $purchaseOrder->company_id }}-{{ $purchaseOrder->number }} (ver. {{ $purchaseOrder->version }})</td></tr>
						<tr><td>P.O Date: {{ date('d.m.Y', strtotime($purchaseOrder->date)) }}</td></tr>
						<tr><td>SO No. :{{ $purchaseOrder->salesorder }}</td></tr>
						<tr><td>SO Date: {{ date('d.m.Y', strtotime($purchaseOrder->date)) }}</td></tr>
						<tr><td>Delivery NO: {{ $purchaseOrder->delivery }}</td></tr>
						<tr><td>Due Date: {{ date('j/n/Y', strtotime($purchaseOrder->signed_at . ' + ' . $purchaseOrder->paymentterm['days'] . ' days')) }}</td></tr>
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
						<tr><td>Customer Code: {{ $purchaseOrder->company->sapnumber }}</td></tr>
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
						<tr><td><b>Supplier</b></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Vendor Code: {{ $purchaseOrder->vendor->sapvendornumber }}</td></tr>
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
				<?php
					$buyupVal = 1 * number_format($total * $purchaseOrder->buyup / 100, 2, '.', '');
					$vatVal = 1 * number_format(($total + $buyupVal) * $purchaseOrder->vat / 100, 2, '.', '');
					$grandTotal = $total + $buyupVal + $vatVal;
				?>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">Total</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($total, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">Fees {{ $purchaseOrder->buyup }}%</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($buyupVal, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">VAT {{ $purchaseOrder->vat }}%</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($vatVal, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="5">Grand total</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($grandTotal, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Inco Terms: <b>{{ $purchaseOrder->incoterm['name'] }}</b>
					</td>
				</tr>
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Currency: <b>{{ $purchaseOrder->currency['abbreviation'] }}</b>
					</td>
				</tr>	
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="6">
						Payment Terms: <b>{{ $purchaseOrder->paymentterm['name'] }}</b>
					</td>
				</tr>		
			</tbody>
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
			<p class="para">If you have acknowledged and accepted on the Notice of Assignment of our Debts favoring Standard Chartered Bank, please make payment to Standard Chartered Bank, Dubai, UAE in accordance with the below instructions:</p>
			<p class="para"><b>1) USD payments</b></p>
			<p class="para">Payments should be made via SWIFT/ Telegraphic Transfer through Standard Chartered Bank, New York (SWIFT-SCBLUS33XXX), (ABA-256), FED Wire FW026002561 Account No. 3582-088678-001 of Standard Chartered Bank, Dubai (SWIFT- SCBLAEADXXX) with them for further credit to Account No. USD 48-3898865-01 IBAN NO AE120440000148389886501 Account Name “BIZZMO FZE”.  Please always quote your Company’s name, and Invoice No. for all payments made.</p>	
			<p class="para"><b>2) AED payments</b></p>
			<p class="para">a) For checks: To be Drawn in favour of SCB COLL A/C O/A “BIZZMO FZE” A/C 48-3898865-01.</p>	
			<p class="para">b) For bank transfers: Payment must be made through U.A.E. Central Bank, Dubai. A/C of Standard Chartered Bank, Dubai, with them for further credit to Account Number AED 48-3898865-01 IBAN NO AE500440000048389886501 Account Name “BIZZMO FZE” RS Collection A/C.</p>	
			<p class="para">For invoices in USD, the payment can be made in AED using the conversion rate of 3.675.</p>	
		</div>
	</div>	
</body>