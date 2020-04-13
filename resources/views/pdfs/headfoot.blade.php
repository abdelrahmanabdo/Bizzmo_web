<table width="100%">
	<tr>
		<td width="33%">
			@if (isset($suppliername))
				<span class="text-blue" style="text-size: 64px">Issued on Bizzmo on behalf of Supplier {{ $suppliername }}</span>
				<br/><br/>
				<span class="text-blue">{{ $supplieraddress }}</span>
			@elseif (isset($buyername))
				<span class="text-blue" style="text-size: 64px">Issued on Bizzmo on behalf of Buyer {{ $buyername }}</span>
				<br/><br/>
				
			@else
				<a href="<?= URL::to(env('APP_URL')) ?>" style="text-decoration: none">
					<img src="{{ str_replace('\\', '/',public_path('/images/logo-with-name.png')) }}" class="pull-left logo" width="180">
				</a>
			@endif
		</td>
		<td width="34%">
			<div class="text-blue" style="display: inline-block;vertical-align: middle;float: center;text-align: center">
				<br><center><span class="title">{{ $doctitle }}</span></center>
			</div>
		</td>
		<td width="33%">
			<div style="float: right;text-align:right; padding-right:5">
				<span class="text-blue" style="text-size: 32px;">{{ $docname }} #{{ $docnum }}</span>
				@if ($doctitle == 'Purchase Order' || $doctitle == 'Quotation' || $doctitle == 'Invoice')
					@if (isset($TRN))
						<br><br><span class="text-blue">TRN #{{ $TRN }}</span>
					@else
						<br><br><span class="text-blue" style="font-size: 8px;">{{ $basicInfo->tax }}</span>
					@endif
				@endif
			</div>				
		</td>
	</tr>				
</table>	
<br/><br/>
@if (isset($suppliername))
	<footer>
		<div class="text-blue" style="display: inline-block;width: 50%;font-size: 11px;">
			<b>{{ $suppliername }}</b> <br/>
			{{ $supplieraddress }}
		</div>
		<div class="text-blue" style="display: inline-block;width: 50%;font-size: 11px;">
			Tel: {{ $supplierphone }}<br/>
			Fax: {{ $supplierfax }}<br/>
		</div>
	</footer>
@elseif (isset($buyername))
	<footer>
		<div class="text-blue" style="display: inline-block;width: 50%;font-size: 11px;">
			<b>{{ $buyername }}</b> <br/>
			{{ $buyeraddress }}
		</div>
		<div class="text-blue" style="display: inline-block;width: 50%;font-size: 11px;">
			Tel: {{ $buyerphone }}<br/>
			Fax: {{ $buyerfax }}<br/>
		</div>
	</footer>

@else
<footer>
	<div class="text-blue" style="display: inline-block;width: 30%;font-size: 11px;">
		<b>{{ $basicInfo->companyName }}</b> <br/>
		{{ $basicInfo->poBox }}<br/>
		{{ $basicInfo->address }}
	</div>
	<div class="text-blue" style="display: inline-block;width: 30%;font-size: 11px;">
		Tel: {{ $basicInfo->tel }}<br/>
		Fax: {{ $basicInfo->fax }}
	</div>
</footer>
@endif