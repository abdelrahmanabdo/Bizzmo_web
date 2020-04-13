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
	@include('pdfs.headfoot', ['docnum' => $purchaseOrder->delivery, 'doctitle' => 'Proof of Delivery', 'docname' => 'Proof of Delivery' ])
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
				<!-- P.O INFO -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%" style="text-align: right">
						<tr><td>Bizzmo PO No. :{{ $purchaseOrder->vendornumber }} (ver. {{ $purchaseOrder->version }})</td></tr>
						<tr><td>Buyer PO No. :{{ $purchaseOrder->company_id }}-{{ $purchaseOrder->number }} (ver. {{ $purchaseOrder->version }})</td></tr>
						<tr><td>PO Date: {{ date('d.m.Y', strtotime($purchaseOrder->date)) }}</td></tr>
						<tr><td>SO No. :{{ $purchaseOrder->salesorder }}</td></tr>
						<tr><td>SO Date: {{ date('d.m.Y', strtotime($purchaseOrder->date)) }}</td></tr>
						@if (isset($purchaseOrder->quotation))
							<tr><td>Bizzmo Quotation No.: {{ $purchaseOrder->quotation->vendornumber }} (ver. {{ $purchaseOrder->quotation->version }})</td></tr>
							<tr><td>Supplier Quotation No.: {{ $purchaseOrder->quotation->vendor_id }}-{{ $purchaseOrder->quotation->number }} (ver. {{ $purchaseOrder->quotation->version }})</td></tr>
						@endif
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
				<!-- Supplier -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Supplier:</b></td></tr>
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
					<th width="25%" class="labels detailscell" style="border-right-color: white;"><b>MPN</b></th>
					<th width="15%" class="labels detailscell" style="border-right-color: white;"><b>Brand</b></th>
					<th width="15%" class="labels detailscell"><b>Quantity</b></th>						
				</tr>		
			</thead>
			<tbody>
				@foreach ($purchaseOrder->purchaseorderitems as $purchaseorderitem)
					@php
						$unit  = $purchaseorderitem['unit'];						
					@endphp
					<tr>
						<td class="labels detailscell" style="text-align: left">{{ $purchaseorderitem['productname'] }}</td>
						<td class="labels detailscell" style="text-align: left">{{ $purchaseorderitem['mpn'] }}</td>
						<td class="labels detailscell" style="text-align: left">{{ $purchaseorderitem->brand->name }}</td>
						<td class="labels detailscell" align="right">{{ $purchaseorderitem['quantity'] }}&nbsp;{{ $unit['abbreviation'] }}</td>
					</tr>
				@endforeach
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="4">
						Inco Terms: <b>{{ $purchaseOrder->incoterm['name']}}</b>
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
			<p class="para">I, the undersigned, acknowledge taking receipt of the goods as detailed below/above. I hereby acknowledge that I inspected the shipment and exerted all the needed due diligence and confirm, accordingly, the conformity of the goods in quantity and quality with the placed purchase order. I hereby acknowledge that the goods as delivered are free from any apparent defect. I do accept that any hidden manufacturing or industrial defect should not be the responsibility of Bizzmo. I hereby waive any right of recourse, claim, setting off or request of compensation, against Bizzmo for quantitive or qualitative non-conformity with respect to the delivered goods.</p>	
		</div>

		<table width="100%" cellspacing="0">
			<tr>
				<td width="70%">
					&nbsp;
				</td>
				<td width="30%">
					Signer Name:
					@if($provider != 'docusign')
						<div style="width: 100%;height: 25px;background: #fff;margin-top: 10px">
							@if($provider != 'docusign')
								<div style="font-size: 30px;color: #fff;line-height: 20px">
									<!-- font color like the background color (RightSignature) -->
									[ t:a:r ]
								</div>
							@endif
						</div>
					@endif
				</td>
			</tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td width="70%">
					&nbsp;
				</td>
				<td width="30%">
					Signature:
					@if($provider != 'docusign')
						<div style="width: 100%;height: 25px;background: #fff;margin-top: 10px">
							@if($provider != 'docusign')
								<div style="font-size: 43px;color: #eeeeee">
									<!-- font color like the background color (RightSignature) -->
									[ s:a:r ]
								</div>
							@endif
						</div>						
					@endif
				</td>
			</tr>
		</table>		
	</div>	
</body>