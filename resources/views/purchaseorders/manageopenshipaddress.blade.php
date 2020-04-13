@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if (isset($purchaseorder)) 
		{{ Form::model($purchaseorder, array('id' => 'frmManage')) }}
	@else
		{{ Form::open(array('id' => 'frmManage')) }}
	@endif

	<div class="row-fluid">	<!-- row 1 -->		
		<div class="col-md-2">  <!-- column 1 -->
			<div class="form-group"> <!-- po number -->  
				{{ Form::label('purchaseordername', 'PO no.') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->number }} (ver. {{ $purchaseorder->version }})</p>
				@else					
					<p class='form-control-static'>New</p>
				@endif
			</div> <!-- po number -->  
		</div>					<!-- column 1 end -->
		<div class="col-md-2">  <!-- column 2 -->
			<div class="form-group"> <!-- date -->  
				{{ Form::label('date', 'Date') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->date }}</p>
				@else										
					@if (isset($purchaseorder))
						<p class='form-control-static'>{{ $purchaseorder->date }}</p>
						{{ Form::hidden('date', old('date'), array('id' => 'date')) }}
					@else
						<p class='form-control-static'>{{ date('j/n/Y') }}</p>
						{{ Form::hidden('date', date('j/n/Y'), array('id' => 'date')) }}
						@if ($errors->has('date')) <p class="bg-danger">{{ $errors->first('date') }}</p> @endif
					@endif					
				@endif
			</div> <!-- date end -->  
		</div>					<!-- column 2 end -->
		<div class="col-md-4">  <!-- Column 3 -->
			<div class="form-group"> <!-- vendor -->  
				{{ Form::label('vendor_id', 'Supplier') }}
				@if (isset($mode) || (isset($purchaseorder) && $purchaseorder->userrelation == 2))
					<p class='form-control-static'>{{ $purchaseorder->vendor->companyname }}</p>
				@else					
					{{ Form::select('vendor_id', $vendors, old('vendor_id'),array('id' => 'vendor_id', 'class' => 'form-control'))}}		
					@if ($errors->has('vendor_id')) <p class="bg-danger">{{ $errors->first('vendor_id') }}</p> @endif
				@endif
			</div> <!-- vendor --> 			
		</div>					<!-- column 3 end -->
		<div class="col-md-4">  <!-- column 4 -->
			<div class="form-group"> <!-- vendor -->  
				{{ Form::label('company_id', 'Buyer') }}
				@if (isset($purchaseorder))	
					<p class='form-control-static'>{{ $purchaseorder->company->companyname }}</p>
					{{ Form::hidden('company_id', $purchaseorder->company_id, array('id' => 'company_id', 'class' => 'form-control'))}}		
				@else
					<p class='form-control-static'>{{ $company->companyname }}</p>
					{{ Form::hidden('company_id', $company->id, array('id' => 'company_id', 'class' => 'form-control'))}}		
					@if ($errors->has('company_id')) <p class="bg-danger">{{ $errors->first('company_id') }}</p> @endif
				@endif				
			</div> <!-- vendor --> 			
		</div>					<!-- column 4 end -->
	</div>				<!-- end row 1 -->
	<div class="row-fluid">	<!-- row 2 -->
		<div class="col-md-4">  <!-- column 1 -->
			<div class="form-group"> <!-- shipping address -->  
				{{ Form::label('shippingaddress', 'Shipping address') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->shippingaddress }}</p>
				@else										
					{{ Form::text('shippingaddress', old('shippingaddress'), array('id' => 'shippingaddress', 'class' => 'typeahead form-control', 'style' => 'display: block;')) }}			
					@if ($errors->has('shippingaddress')) <p class="bg-danger">{{ $errors->first('shippingaddress') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						$poaudit = $audit;
						if (array_key_exists('shippingaddress', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['shippingaddress'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- shipping address end -->  			
		</div>					<!-- column 1 end -->
		<div class="col-md-4">  <!-- column 2 -->
			<div class="form-group"> <!-- shipping district -->  
				{{ Form::label('shippingdistrict', 'Shipping district') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->shippingdistrict }}</p>
				@else										
					{{ Form::text('shippingdistrict', old('shippingdistrict'), array('id' => 'shippingdistrict', 'class' => 'form-control')) }}			
					@if ($errors->has('shippingdistrict')) <p class="bg-danger">{{ $errors->first('shippingdistrict') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('shippingdistrict', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['shippingdistrict'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- shipping district end -->  			
		</div>					<!-- column 2 end -->
		<div class="col-md-2">  <!-- column 1 -->
			<div class="form-group"> <!-- shipping city -->  
				{{ Form::label('shippingcity', 'Shipping city') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->shippingcity }}</p>
				@else										
					{{ Form::text('shippingcity', old('shippingcity'), array('id' => 'shippingcity', 'class' => 'form-control')) }}			
					@if ($errors->has('shippingcity')) <p class="bg-danger">{{ $errors->first('shippingcity') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('shippingcity', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['shippingcity'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- shipping city end -->  			
		</div>					<!-- column 1 end -->
		<div class="col-md-2">  <!-- column 3 -->
			<div class="form-group"> <!-- shipping country -->  
				{{ Form::label('shippingcountry', 'Shipping country') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->shippingcountry }}</p>
				@else										
					{{ Form::text('shippingcountry', old('shippingcountry'), array('id' => 'shippingcountry', 'class' => 'form-control')) }}			
					@if ($errors->has('shippingcountry')) <p class="bg-danger">{{ $errors->first('shippingcountry') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('shippingcountry', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['shippingcountry'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- shipping country end -->  			
		</div>					<!-- column 3 end -->
	</div>				<!-- end row 2 -->
	<div class="row-fluid">	<!-- row 3 -->
		<div class="col-md-2">  <!-- column 1 -->
			<div class="form-group"> <!-- incoterm -->  
				{{ Form::label('incoterm_id', 'Inco terms') }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $purchaseorder->incoterm->name }}</p>
				@else					
					{{ Form::select('incoterm_id', $incoterms, old('incoterm_id'),array('id' => 'incoterm_id', 'class' => 'form-control'))}}		
					@if ($errors->has('incoterm_id')) <p class="bg-danger">{{ $errors->first('incoterm_id') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('incoterm_id', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['incoterm_id'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- incoterm --> 			
		</div>					<!-- column 1 end -->
		<div class="col-md-2">  <!-- column 2 -->
			<div class="form-group"> <!-- currency -->  
				{{ Form::label('currency_id', 'Currency') }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $purchaseorder->currency->name }}</p>
				@else					
					{{ Form::select('currency_id', $currencies, old('currency_id'),array('id' => 'currency_id', 'class' => 'form-control'))}}		
					@if ($errors->has('currency_id')) <p class="bg-danger">{{ $errors->first('currency_id') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('currency_id', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['currency_id'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- currency --> 			
		</div>					<!-- column 2 end -->
		<div class="col-md-2">  <!-- column 3 -->
			<div class="form-group"> <!-- payment terms -->  
				{{ Form::label('paymentterm_id', 'Payment terms') }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $purchaseorder->paymentterm->name }}</p>
				@else					
					{{ Form::select('paymentterm_id', $paymentterms, old('paymentterm_id'),array('id' => 'paymentterm_id', 'class' => 'form-control'))}}		
					@if ($errors->has('paymentterm_id')) <p class="bg-danger">{{ $errors->first('paymentterm_id') }}</p> @endif
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('paymentterm_id', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['paymentterm_id'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- payment terms --> 			
		</div>					<!-- column 3 end -->
		<div class="col-md-6">  <!-- column 1 -->
			<div class="form-group"> <!-- note -->  				
				@if (isset($purchaseorder))	
					{{ Form::label('stat_id', 'Status') }}
					<p class='form-control-static'>{{ $purchaseorder->status->name }}</p>
				@endif
			</div> <!-- note end -->  
		</div>					<!-- column 1 end -->
	</div>				<!-- end row 3 -->
	<div class="row-fluid">	<!-- row 4 -->		
		<div class="col-md-6">  <!-- column 4 -->
			<div class="form-group"> <!-- note -->  
				{{ Form::label('note', 'Note') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $purchaseorder->note }}</p>
				@else										
					@if (isset($purchaseorder))
						<p class='form-control-static'>{{ $purchaseorder->note }}</p>
						{{ Form::hidden('note', old('note'), array('id' => 'note')) }}
					@else
						{{ Form::text('note', old('note'), array('id' => 'note', 'class' => 'form-control')) }}			
					@if ($errors->has('note')) <p class="bg-danger">{{ $errors->first('note') }}</p> @endif
					@endif					
				@endif
				@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
					@php
						$audit = $purchaseorder->audits->last();
						if (array_key_exists('note', $audit->old_values)) {
							echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['note'] . '</p>';
						}
					@endphp
				@endif
			</div> <!-- note end -->  
		</div>					<!-- column 4 end -->		
	</div>				<!-- end row 4 -->
	<div class="row-fluid">	<!-- row 5 -->		
		<div class=" col-md-12"> <!-- column 1 -->
			<h4>Products</h4>
			<?php $itemcount = 0; ?>
			<table id="itemstable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						@if (!isset($mode))
							<th class="no-sort" class="col-md-1">
								<a href="" id="lnkitem" role="button" class="btn btn-info"><span class="glyphicon glyphicon-plus" title="Add item"></span></a>	
							</th>
						@endif
						<th class="col-md-3">Product description</th>
						<th class="col-md-2">MPN</th>
						<th class="col-md-1">Unit</th>
						<th class="col-md-2">Quantity</th>						
						<th class="col-md-2">Price</th>
						<th class="col-md-1">Total</th>
					</tr>		
				</thead>
				<tbody>
					@if (old('itemid'))
						@php
							$i = 0;
						@endphp
						@foreach (old('itemid') as $item)
							<tr style="{{ (old('itemdel')[$i]) ? 'display:none' : '' }}">
								<td>
									<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelItem"><span class="glyphicon glyphicon-trash"></span></a>
									{{ Form::hidden('itemid[]', old('itemid')[$i], array('id' => 'item_id')) }}
									{{ Form::hidden('itemdel[]', old('itemdel')[$i], array('id' => 'itemdel', 'class' => 'form-control')) }}
								</td>
								<td>
									{{ Form::text('productname[]', old('productname')[$i], array('id' => 'productname', 'class' => 'form-control')) }}
									@if ($errors->has('productname.' . $i)) <p class="bg-danger">{{ $errors->first('productname.' . $i) }}</p> @endif
								</td>
								<td>
									{{ Form::text('mpn[]', old('mpn')[$i], array('id' => 'mpn', 'class' => 'form-control')) }}
									@if ($errors->has('mpn.' . $i)) <p class="bg-danger">{{ $errors->first('mpn.' . $i) }}</p> @endif
								</td>
								<td>
									{{ Form::select('unit_id[]', $unitsarr, old('unit_id')[$i], array('id' => 'unit_id', 'class' => 'form-control'))}}		
									@if ($errors->has('unit_id.' . $i)) <p class="bg-danger">{{ $errors->first('unit_id.' . $i) }}</p> @endif
								</td>
								<td>
									{{ Form::text('quantity[]', old('quantity')[$i], array('id' => 'quantity', 'class' => 'form-control quantity')) }}
									@if ($errors->has('quantity.' . $i)) <p class="bg-danger">{{ $errors->first('quantity.' . $i) }}</p> @endif
								</td>
								<td>
									{{ Form::text('price[]', old('price')[$i], array('id' => 'price', 'class' => 'form-control price')) }}
									@if ($errors->has('price.' . $i)) <p class="bg-danger">{{ $errors->first('price.' . $i) }}</p> @endif
								</td>
								<td align="right">{{ number_format(old('quantity')[$i] * old('price')[$i], 2, '.', ',') }}</td>
							</tr>
						@php
							$i++;
							$itemcount = $i;
						@endphp	
						@endforeach
					@else
						@if (isset($purchaseorder))
							<?php $i = 0 ; ?>
							@foreach ($purchaseorder->purchaseorderitems as $item)
								<tr>							
									@if (isset($mode))								
										<td>{{ $item->productname }}</td>
										<td>{{ $item->mpn }}</td>
										<td>{{ $item->unit->name }}</td>
										<td align="right">{{ number_format($item->quantity, 2, '.', ',') }}</td>
										<td align="right">{{ number_format($item->price, 2, '.', ',') }}</td>
										<td align="right">{{ number_format($item->subtotal, 2, '.', ',') }}</td>
									@else
										<td>
											<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelOwner"><span class="glyphicon glyphicon-trash" type="button"></span></a>
											{{ Form::hidden('itemid[]', $item->id, array('id' => 'item_id')) }}
											{{ Form::hidden('itemdel[]', '', array('id' => 'itemdel', 'class' => 'form-control')) }}
										</td>
										<td>
											{{ Form::text('productname[]', $item->productname, array('id' => 'productname', 'class' => 'form-control')) }}
											@if ($errors->has('productname.' . $i)) <p class="bg-danger">{{ $errors->first('productname.' . $i) }}</p> @endif
											@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
												@php													
													$audit = $item->audits->last();
													if (array_key_exists('productname', $audit->old_values) && $audit->id >= $poaudit->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['productname'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::text('mpn[]', $item->mpn, array('id' => 'mpn', 'class' => 'form-control')) }}
											@if ($errors->has('mpn.' . $i)) <p class="bg-danger">{{ $errors->first('mpn.' . $i) }}</p> @endif
											@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('mpn', $audit->old_values) && $audit->id >= $poaudit->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['mpn'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::select('unit_id[]', $unitsarr, $item->unit_id, array('id' => 'unit_id', 'class' => 'form-control'))}}		
											@if ($errors->has('unit_id.' . $i)) <p class="bg-danger">{{ $errors->first('unit_id.' . $i) }}</p> @endif
											@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('unit_id', $audit->old_values) && $audit->id >= $poaudit->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['unit_id'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::text('quantity[]', $item->quantity, array('id' => 'quantity', 'class' => 'form-control quantity')) }}
											@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('quantity', $audit->old_values) && $audit->id >= $poaudit->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['quantity'] . '</p>';
													}
												@endphp
											@endif
										</td>
											<td>{{ Form::text('price[]', $item->price, array('id' => 'price', 'class' => 'form-control price')) }}</td>
											<td align="right">{{ number_format($item->subtotal, 2, '.', ',') }}</td>
											@if (isset($purchaseorder) && $purchaseorder->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('price', $audit->old_values) && $audit->id >= $poaudit->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['price'] . '</p>';
													}
												@endphp
											@endif
									@endif
								</tr>
								<?php $i = $i + 1 ; 
									$itemcount = $i;
								?>								
							@endforeach							
						@endif
					@endif
				</tbody>
				<tfoot>
					<tr>
						@if (!isset($mode))
							<td>&nbsp;</td>
						@endif
						<td>Total</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						@if (isset($purchaseorder))
							<td align="right">{{ number_format($purchaseorder->total, 2, '.', ',') }}</td>							
						@else
							<td align="right">&nbsp;</td>
						@endif						
					</tr>
					@if (!isset($purchaseorder) || (isset($purchaseorder) && $purchaseorder->userrelation != 2))
						<tr>
							@if (!isset($mode))
								<td>&nbsp;</td>
							@endif
							<td>Buyup %</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($purchaseorder))
								<td align="right">{{ number_format($purchaseorder->buyup, 2, '.', ',') }}</td>							
							@else
								<td align="right">{{ number_format($buyup, 2, '.', ',') }}</td>
							@endif						
						</tr>
						<tr>
							@if (!isset($mode))
								<td>&nbsp;</td>
							@endif
							<td>Grand Total</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($purchaseorder))
								<td align="right">{{ number_format($purchaseorder->total * (1 + $purchaseorder->buyup / 100), 2, '.', ',') }}</td>							
							@else
								<td align="right">&nbsp;</td>
							@endif						
						</tr>
					@endif
				</tfoot>
			</table>
			<input type="hidden" name="itemcount" id="itemcount" value="{{$itemcount}}">
			@if ($errors->has('itemcount')) <p class="bg-danger">{{ $errors->first('itemcount') }}</p> @endif
		</div>					<!-- column 1 end -->
	</div>				<!-- end row 5 -->
	<div class="row-fluid">	<!-- row 6 -->		
		<div class=" col-md-12"> <!-- column 1 -->
			@if (isset($mode))
				@if (Gate::allows('po_cr'))
					<div class="col-xs-3"> <!-- Column 1 -->			
						<a href="{{ url("/purchaseorders/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>						
					</div> <!-- Column 1 end -->
				@endif
				@if (Gate::allows('po_sc'))
					<div class="col-xs-3"> <!-- Column 2 -->
						<a href="{{ url("/purchaseorders") }}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				@endif
				@if (Gate::allows('po_ch', $purchaseorder->id) && $purchaseorder->status_id == 13)
					<div class="col-xs-3"> <!-- Column 3 -->
						<a href="{{ url("/purchaseorders/" . $purchaseorder->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
					</div>
					@if ($mode == 'c')
						<div class="col-xs-3"> <!-- Column 3 -->
							<a href="{{ url("/purchaseorders/crejectc/" . $purchaseorder->id) }}" class="btn btn-danger fixedw_button" role="button" title="Reject"><span class="glyphicon glyphicon-remove"></span></a>
						</div>
					@endif
				@endif
				@if (Gate::allows('vp_ap', $purchaseorder->id))
					<div class="col-xs-3"> <!-- Column 3 -->
						@if ($mode == 'a')
							<a href="{{ url("/purchaseorders/approvec/" . $purchaseorder->id) }}" class="btn btn-success fixedw_button" role="button" title="Approve"><span class="glyphicon glyphicon-ok"></span></a>
						@elseif ($mode =='r')
							<a href="{{ url("/purchaseorders/rejectc/" . $purchaseorder->id) }}" class="btn btn-danger fixedw_button" role="button" title="Reject"><span class="glyphicon glyphicon-remove"></span></a>
						@endif
					</div>
				@endif
				@if (Gate::allows('po_rl', $purchaseorder->id) && $purchaseorder->canreleaseorder)
					<div class="col-xs-3"> <!-- Column 3 -->
						<a href="{{ url("/purchaseorders/orderreleasec/" . $purchaseorder->id) }}" class="btn btn-success fixedw_button" role="button" title="Release"><span class="glyphicon glyphicon-check"></span></a>
					</div>
				@endif
				@if (Gate::allows('po_rc') && $purchaseorder->status_id == 4)
					<div class="col-xs-3"> <!-- Column 3 -->
						<a href="{{ url("/purchaseorders/creditrelease/" . $purchaseorder->id) }}" class="btn btn-success fixedw_button" role="button" title="Release"><span class="glyphicon glyphicon-check"></span></a>
					</div>
				@endif
			@else
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
				<a href="" class="btn btn-primary fixedw_button" id="lnksubmit" type="button" title="Save">
					<span class="glyphicon glyphicon-ok"></span>
				</a>
			@endif
		</div> <!-- column 1 end -->
	</div>				<!-- end row 6 -->
	{{ Form::close() }}	
@stop	
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
			e.preventDefault();
		   	$("#submit").click();
			});			

			$('.quantity').mask('000000000000000.00', {reverse: true});
			$('.price').mask('000000000000000.00', {reverse: true});
			//paymentterm_id change
			$("#paymentterm_id").change(function(){
				if (row.cells[0].innerHTML != 'Grand Total' && row.cells[1].innerHTML != 'Grand Total') {
				}
				var formData = new FormData;
					formData.append('paymentterm_id', $('select[name=paymentterm_id]').val());
					formData.append('_token', $('input[name=_token]').val());
					@if (isset($purchaseorder))
						@php
							echo "formData.append('company_id', " . $purchaseorder->company_id . ");";
						@endphp
					@else
						@php
							echo "formData.append('company_id', " . $company->id . ");";
						@endphp
					@endif					
                $.ajax({					
                    url: '/companies/getbuyup',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response){
						console.log('aa' + response);						
						var table = document.getElementById('itemstable');
						var row = table.rows[table.rows.length - 2];
						row.cells[row.cells.length - 1].innerHTML = response.toFixed(2);
						var total = OrderTotal();
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                })
			});
			//paymentterm_id change end
			//typeahead code
			var substringMatcher = function(strs) {
			  return function findMatches(q, cb) {
				var matches, substringRegex;
				// an array that will be populated with substring matches
				matches = [];
				// regex used to determine if a string contains the substring `q`
				substrRegex = new RegExp(q, 'i');
				// iterate through the pool of strings and for any string that
				// contains the substring `q`, add it to the `matches` array
				$.each(strs, function(i, str) {
				  if (substrRegex.test(str)) {
					matches.push(str);
				  }
				});
				cb(matches);
			  };
			};

			var addresses = [
			@php
				if (isset($company)) {
					foreach ($company->shippingaddresses as $shippingaddress) {
						echo "'" . $shippingaddress->address . "',";
					}
				}
			@endphp
			];
						
			$('#shippingaddress').typeahead({
			  hint: true,
			  highlight: true,
			  minLength: 1
			},
			{
			  name: 'addresses',
			  source: substringMatcher(addresses)
			});
			
			$('.typeahead').bind('typeahead:select', function(ev, suggestion) {
				console.log('Selection: ', suggestion);
				
				var formData = new FormData;
				formData.append('shippingaddress', suggestion);
				formData.append('_token', $('input[name=_token]').val());
				$.ajax({					
                    url: '/shippingaddress',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(address){
						$('#shippingdistrict').val(address.district);
						$('#shippingcity').val(address.city);
						$('#shippingcountry').val(address.country);
						//console.log(address.city);						
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			});
			//typeahead code end

			$( ".quantity, .price" ).change(function() {
				//alert( "Handler for .change() called." );
				var total = OrderTotal();
			});
			
			$("#lnkitem").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('itemstable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelItem" type="button"><span class="glyphicon glyphicon-trash" title="Delete item"></span></a>';
				row = row + '<input name="itemid[]" type="hidden" class="form-control">';
				row = row + '<input name="itemdel[]" id="itemdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="productname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="mpn[]" type="text" class="form-control"></td>';
				row = row + '<td><select name="unit_id[]" class="form-control">';
				@php
					if (isset($units)) {
						foreach ($units as $unit) {
							echo "row = row + '<option value=" . $unit->id . ">" . $unit->abbreviation . "</option>';";
						}
					}
				@endphp
				row = row + '</select></td>';
				row = row + '<td><input name="quantity[]" type="text" class="form-control quantity" onchange="OrderTotal()"></td>';				
				row = row + '<td><input name="price[]" type="text" class="form-control price" onchange="OrderTotal()"></td>';
				row = row + '<td class="text-right">0.00</td>';
				row = row + '</tr>';
				$('#itemstable').append(row);
				$("#itemcount").val(parseInt($("#itemcount").val()) + 1);
				$('.quantity').mask('000000000000000.00', {reverse: true});
				$('.price').mask('000000000000000.00', {reverse: true});
			});
		});
		
		function DelRow(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
					var inputval = inputs[j].id;                
					if (inputval == 'itemdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';
						tr.cells[2].getElementsByTagName("input")[0].value='A';
						tr.cells[4].getElementsByTagName("input")[0].value='1';
						tr.cells[5].getElementsByTagName("input")[0].value='1';
						$("#itemcount").val(parseInt($("#itemcount").val()) - 1);
					}
				}
			tr.style.display = 'none';
		}
		
		function OrderTotal () {
		var table = document.getElementById('itemstable');
		var rowLength = table.rows.length;
		var ordertotal = 0.0;		
		if ($("#itemstable tr:visible").length > 2) {
			var row = table.rows[0];
			for(var i=1; i<row.cells.length; i+=1){		
				switch (row.cells[i].innerHTML) {
					case 'Total':
						var subtotalcell = i;
						break;
					case 'Quantity':
						var qtycell = i;
						break;
					case 'Price':
						var pricecell = i;
						break;
				}
			}
			if (row.cells[0].innerHTML == 'Grand Total' || row.cells[1].innerHTML == 'Grand Total') {
				var footrow = 3;
			} else {
				var footrow = 1;
			}
			for (var i = 1; i < rowLength - footrow; i += 1){
				var row = table.rows[i];
				//alert(row.style.display);
				if (row.style.display != 'none') {
					var cellLength = row.cells.length;
					var quantity = row.cells[qtycell].getElementsByTagName("input")[0].value;
					var price = row.cells[pricecell].getElementsByTagName("input")[0].value;					
					if (quantity == '') {
						quantity = 0.00;
					}
					if (price == '') {
						price = 0.00;
					}
					if (isNumber(quantity) && isNumber(price)) {
						row.cells[subtotalcell].innerHTML = (parseFloat(quantity) * parseFloat(price)).toFixed(2);
						ordertotal = parseFloat(ordertotal) + parseFloat(quantity) * parseFloat(price);
					} else {
						alert("Quantity and price must be numbers");
						ordertotal = -1;
						return false;
					}
					if (quantity <= 0) {
						alert("Quantity must be more than 0");
						ordertotal = -1;
						return false;
					}
				}
			}		
		}
		var row = table.rows[table.rows.length - footrow];
		row.cells[row.cells.length - 1].innerHTML = ordertotal.toFixed(2);
		var row = table.rows[table.rows.length - 1];
		if (row.cells[0].innerHTML == 'Grand Total' || row.cells[1].innerHTML == 'Grand Total') {
			var row = table.rows[table.rows.length - 2];
			var buyup = row.cells[row.cells.length - 1].innerHTML;
			var row = table.rows[table.rows.length - 1];
			row.cells[row.cells.length - 1].innerHTML = (ordertotal * (1 + buyup / 100)).toFixed(2);
		}
	}

	</script>
@endpush