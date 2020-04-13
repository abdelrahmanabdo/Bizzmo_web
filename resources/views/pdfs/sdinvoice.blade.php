<?php
use App\Settings;

// Get basic info
$basicInfo = Settings::where('key', 'basicInfo')->first();
$basicInfo = json_decode($basicInfo->value);
?>

@include('pdfs.pdfstyle')

<body>
	@include('pdfs.headfoot', ['docnum' => $security->inv_no, 'doctitle' => 'Invoice', 'docname' => 'Tax Invoice' ])
	<div class="container">
		<table width="100%">
			<tr>
				<!-- Sold To -->								
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Sold To:</b></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Customer Code: {{ $security->creditrequest->company->sapnumber }}</td></tr>
						<tr><td>TRN#: {{ $security->creditrequest->company->tax }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->companyname }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->address }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->city->cityname }}, {{ $security->creditrequest->company->city->country->countryname }}</td></tr>
						<tr><td>Tel: {{ $security->creditrequest->company->phone }}</td></tr>
						<tr><td>Fax: {{ $security->creditrequest->company->fax }}</td></tr>
					</table>
				</td>
				<!-- Ship To -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Ship To/Deliver To:</b></td></tr>
						<tr><td>&nbsp;</td></tr>		
						<tr><td>&nbsp;</td></tr>	
						<tr><td>&nbsp;</td></tr>			
						<tr><td>{{ $security->creditrequest->company->companyname }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->address }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->city->cityname }}, {{ $security->creditrequest->company->city->country->countryname }}</td></tr>
						<tr><td>Tel: {{ $security->creditrequest->company->phone }}</td></tr>
						<tr><td>Fax: {{ $security->creditrequest->company->fax }}</td></tr>
						<tr><td>&nbsp;</td></tr>
					</table>
				</td>
				<!-- P.O INFO -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%" style="text-align: right">
						<tr><td>CR NO: {{ $security->creditrequest->id }}</td></tr>
						<tr><td>Invoice Date: {{ date('d.m.Y', strtotime($security->created_at)) }}</td></tr>
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
						<tr><td>Customer Code: {{ $security->creditrequest->company->sapnumber }}</td></tr>
						<tr><td>TRN#: {{ $security->creditrequest->company->tax }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->companyname }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->address }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->city->cityname }}, {{ $security->creditrequest->company->city->country->countryname }}</td></tr>
						<tr><td>Tel: {{ $security->creditrequest->company->phone }}</td></tr>
						<tr><td>Fax: {{ $security->creditrequest->company->fax }}</td></tr>
					</table>
				</td>		
				<!-- Payer -->				
				<td width="33%" style="vertical-align: top;">
					<table width="100%">
						<tr><td><b>Payer:</b></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Customer Code: {{ $security->creditrequest->company->sapnumber }}</td></tr>
						<tr><td>TRN#: {{ $security->creditrequest->company->tax }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->companyname }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->address }}</td></tr>
						<tr><td>{{ $security->creditrequest->company->city->cityname }}, {{ $security->creditrequest->company->city->country->countryname }}</td></tr>
						<tr><td>Tel: {{ $security->creditrequest->company->phone }}</td></tr>
						<tr><td>Fax: {{ $security->creditrequest->company->fax }}</td></tr>
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
					<th width="15%" class="labels detailscell"><b>Quantity</b></th>						
					<th width="15%" class="labels detailscell"><b>Price</b></th>						
					<th width="15%" class="labels detailscell"><b>Subtotal</b></th>						
				</tr>		
			</thead>
			<tbody>
				<tr>
					<td class="labels detailscell" style="text-align: left">Security Deposit</td>
					<td class="labels detailscell" style="text-align: left">&nbsp;</td>
					<td class="labels detailscell" style="text-align: right">1&nbsp;EA</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($security->amount, 2, '.', ',') }}</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($security->amount, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="4">Total</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($security->amount, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td class="labels detailscell" style="text-align: left;" colspan="4">Grand total</td>
					<td class="labels detailscell" style="text-align: right">{{ number_format($security->amount, 2, '.', ',') }}</td>
				</tr>
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="5">
						Currency: <b>{{ $security->creditrequest->currency['abbreviation']}}</b>
					</td>
				</tr>	
				<tr>
					<td width="100%" class="labels detailscell" style="text-align: left;" colspan="5">
						Payment Terms: <b>Cash</b>
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
			<p class="para">For invoices in USD, the payment can be made in AED using the conversion rate of 3.675.</p>	
		</div>

	</div>	
</body>