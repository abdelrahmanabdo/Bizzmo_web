@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
	<style>
		.po-history-table th, .po-history-table td {
			font-size: 11px;
		}
	</style>
@stop
@section('content')

	<div class="po-form form-group">
		<div class="row">	
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('id', 'PO no.') }}
					<p class='form-control-static'>{{ $purchaseorder->userrelation == 2 ? $purchaseorder->vendornumber : $purchaseorder->number }} (ver. {{ $purchaseorder->version }})</p>
				</div> 
			</div>					
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('date', 'Date') }}
					<p class='form-control-static'>{{ $purchaseorder->date }}</p>
				</div> 
			</div>					
			<div class="col-md-4">  
				<div class="form-group"> 
					{{ Form::label('vendor_id', 'Supplier') }}
					<p class='form-control-static'>{{ $purchaseorder->vendor->companyname }}</p>
				</div> 
			</div>					
			<div class="col-md-4">  
				<div class="form-group"> 
					{{ Form::label('company_id', 'Buyer') }}
					<p class='form-control-static'>{{ $purchaseorder->company->companyname }}</p>	
				</div> 
			</div>					
		</div>				
		<div class="row">	
			<div class="col-md-8">  
				<div class="form-group"> 
					{{ Form::label('shipaddress', 'Shipping address') }}
					<p class='form-control-static'>{{ $purchaseorder->shippingaddress->address }}</p>
				</div> 
			</div>
		</div>				
		<div class="row">	
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('shippingcountry', 'Shipping country') }}
					<p class='form-control-static'>{{ $purchaseorder->shippingaddress->city ? $purchaseorder->shippingaddress->city->country->countryname : $purchaseorder->shippingaddress->country_name }}</p>
				</div> 
			</div>					
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('shippingcity', 'Shipping city')}}
					<p class='form-control-static'>{{ $purchaseorder->shippingaddress->city ? $purchaseorder->shippingaddress->city->cityname : $purchaseorder->shippingaddress->city_name }}</p>
				</div> 
			</div>
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('po_box', 'Shipping PO Box') }}
					<p class='form-control-static'>{{ $purchaseorder->shippingaddress->po_box }}</p>
				</div> 
			</div>
		</div>				
		<div class="row">	
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('incoterm_id', 'Inco terms') }}
					<p class='form-control-static'>{{ $purchaseorder->incoterm->name }}</p>
				</div> 
			</div>					
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('currency_id', 'Currency') }}
					<p class='form-control-static'>{{ $purchaseorder->currency->name }}</p>
				</div> 
			</div>					
			<div class="col-md-3">  
				<div class="form-group"> 
					{{ Form::label('paymentterm_id', 'Payment terms') }}
					<p class='form-control-static'>{{ $purchaseorder->paymentterm->name }}</p>
				</div> 
			</div>					
			<div class="col-md-6">  
				<div class="form-group"> 
					{{ Form::label('stat_id', 'Status') }}
					<p id="po_status" class='form-control-static bg-warning'>
						{{ $purchaseorder->status->name }}
						@if($purchaseorder->status->id == 14)
							<span class='text-danger'>{{substr($purchaseorder->reason_for_rejection, 0, -1)}}</span>
						@endif
					</p>
				</div> 
			</div>					
		</div>				
		<div class="row">	
			<div class="col-md-6">  
				<div class="form-group"> 
					{{ Form::label('note', 'Note') }}
					<p class='form-control-static'>{{ $purchaseorder->note }}</p>
				</div> 
			</div>
				@if ($purchaseorder->attachments->count() > 0)
					@foreach ($purchaseorder->attachments as $attachment)
						@if ($attachment->attachmenttype_id == 14)
							<div class="col-md-3">  
								<div class="form-group"> 
										{{ Form::label('stat_id', 'Delivery note') }}<br>
										<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a>
								</div> 
							</div>					
						@elseif($attachment->attachmenttype_id == 19 && $attachment->status == "Signed & Downloaded") 
							<div class="col-md-3">  
								<div class="form-group"> 
										{{ Form::label('stat_id', 'Signed delivery note') }}<br>
										<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">Signed delivery note</a>
								</div> 
							</div>
						@endif
						@if ($attachment->attachmenttype_id == 15 && $purchaseorder->userrelation != 2)
							<div class="col-md-3">  
								<div class="form-group"> 
										{{ Form::label('stat_id', 'Buyer invoice') }}<br>
										<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a>
								</div> 
							</div>					
						@endif
						@if ($attachment->attachmenttype_id == 16 && $purchaseorder->userrelation != 1)
							<div class="col-md-3">  
								<div class="form-group"> 
										{{ Form::label('stat_id', 'Supplier invoice') }}<br>
										<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a>
								</div> 
							</div>					
						@endif
					@endforeach
				@endif
		</div>				
		<div class="row">	
			<div class="table-wrapper col-md-12"> 
				<h4>Products</h4>
				<table id="itemstable" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width: 1%">&nbsp;</th>
							<th>Product description</th>
							<th>MPN</th>
							<th>Brand</th>
							<th>Unit</th>
							<th>Quantity</th>						
							<th>Price</th>
							<th>Total</th>
						</tr>		
					</thead>
					<tbody>
						@foreach ($purchaseorder->purchaseorderitems as $item)
							<tr>
								<td style="text-align: center">
									<a onclick="toggleItemHistory(this)">
										<span class="glyphicon glyphicon-plus-sign" />
									</a>
								</td>
								<td>
									{{ $item->productname }}
								</td>
								<td>
									{{ $item->mpn }}
								</td>
								<td>
									{{ $item->brand->name }}
								</td>
								<td>
									{{ $item->unit->name }}
								</td>
								<td align="right">
									{{ number_format($item->quantity, 2, '.', ',') }}
								</td>
								<td align="right">
									{{ number_format($item->price, 2, '.', ',') }}
								</td>
								<td align="right">{{ number_format($item->subtotal, 2, '.', ',') }}</td>
							</tr>
							<tr class="po-history-wrapper" style="display: none">
								<td colspan="8">
									<table class="table table-striped table-bordered po-history-table" style="text-size: 11px !important">	
										<thead style="background: burlywood">
											<tr>
												<th>Product description</th>
												<th>MPN</th>
												<th>Brand</th>
												<th>Unit</th>
												<th>Quantity</th>						
												<th>Price</th>
											</tr>		
										</thead>
										<tbody>
											@foreach($item->audits as $audit)
												@if ($audit->event !== 'updateds')
													<tr>
														<td>
															@if (array_key_exists('productname', $audit->new_values))
																{{ $audit->new_values['productname'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('MPN', $audit->new_values))
																{{ $audit->new_values['MPN'] }}
															@elseif (array_key_exists('mpn', $audit->new_values))
																{{ $audit->new_values['mpn'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('brand_id', $audit->new_values))
																{{ $brands[$audit->new_values['brand_id']] }}
															@elseif (array_key_exists('brand', $audit->new_values))
																{{ $audit->new_values['brand'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('unit_id', $audit->new_values))
																EA
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('quantity', $audit->new_values))
																{{ $audit->new_values['quantity'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('price', $audit->new_values))
																{{ $audit->new_values['price'] }}
															@else
																No change
															@endif
														</td>
													</tr>
												@endif
											@endforeach	
										</tbody>
									</table>
								</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td>&nbsp;</td>
							<td>Total</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right">{{ number_format($purchaseorder->total, 2, '.', ',') }}</td>					
						</tr>
						@if ($purchaseorder->userrelation != 2)
							<tr>
								<td>&nbsp;</td>
								<td>Fees - {{ number_format($purchaseorder->buyup, 2, '.', ',') }} %</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">{{ number_format($purchaseorder->total * $purchaseorder->buyup / 100, 2, '.', ',') }}</td>						
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>VAT {{ $purchaseorder->vat }} %</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">{{ number_format(($purchaseorder->total + ($purchaseorder->total * $purchaseorder->buyup / 100)) * $purchaseorder->vat / 100, 2, '.', ',') }}</td>				
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Grand Total</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right">{{ number_format($purchaseorder->total * (1 + $purchaseorder->buyup / 100) + ($purchaseorder->total + ($purchaseorder->total * $purchaseorder->buyup / 100)) * $purchaseorder->vat / 100, 2, '.', ',') }}</td>												
							</tr>
						@endif
					</tfoot>
				</table>
			</div>					
		</div>
		<div class="row">	
				<div class="col-md-12"> 
					<?php
					$showRelease = Gate::allows('po_rl', $purchaseorder->id) && $purchaseorder->canreleaseorder
					?>
					@if ($showRelease)
						<div class="col-xs-7" style="text-align: left"> 
							<div class="col-xs-2" style="padding-left: 0">
								<a href="{{ url("/purchaseorders/orderreleasec/" . $purchaseorder->id) }}" id="submitPO" class="btn bm-btn green fixedw_button" role="button" title="Submit"><span class="glyphicon glyphicon-ok"></span></a>
							</div>
							<div class="col-xs-10"> 
								<div class="checkbox">
									<label class="checkbox" style="display: inline-block">
										<input class="bm-checkbox" type="checkbox" name="cbconfirm" id ="cbconfirm">
										<span class="checkmark" style="top: 1px"></span>
										<span class="bm-sublabel">
											I hereby confirm that i have read and agreed to the
										</span>
									</label>
									@include('purchaseorders.terms')
								</div>
							</div>
						</div>
					@endif
					<div class="<?= $showRelease ? 'col-xs-5' : 'col-xs-12' ?>" style="<?= $showRelease ? 'text-align: right' : 'text-align: center' ?>">		
						@if (Gate::allows('po_vw'))
						<a href="{{ url("/purchaseorders/changes/" . $purchaseorder->id) }}" class="btn bm-btn fixedw_button" role="button" title="Changes"><span class="glyphicon glyphicon-random"></span></a>
						&nbsp;
						@endif
						@if (Gate::allows('po_ch') || Gate::allows('vp_ch') || Gate::allows('vp_ap'))
							@if (Gate::allows('po_ch', $purchaseorder->id) && $purchaseorder->canchange)
								<a href="{{ url("/purchaseorders/" . $purchaseorder->id) }}" class="btn bm-btn sun-flower fixedw_button" role="button"><span class="edit-icon-white" title="Edit"></span></a>
								&nbsp;					
							@elseif (Gate::allows('vp_ch', $purchaseorder->id) && $purchaseorder->status_id == 7)
								<a href="{{ url("/purchaseorders/change/" . $purchaseorder->id) }}" class="btn bm-btn sun-flower fixedw_button" role="button"><span class="edit-icon-white" title="Edit"></span></a>
								&nbsp;
							@endif

							@if (Gate::allows('po_ch', $purchaseorder->id) && $purchaseorder->canCancel() && !$purchaseorder->canDelete())
								<a href="{{ url("/purchaseorders/crejectc/" . $purchaseorder->id) }}" class="btn bm-btn red fixedw_button" role="button" title="Reject"><span class="glyphicon glyphicon-remove"></span></a>
								&nbsp;
							@endif
							@if (Gate::allows('po_rl', $purchaseorder->id) && $purchaseorder->canResubmit())
								<a href="{{ url("/purchaseorders/resubmitc/" . $purchaseorder->id) }}" class="btn bm-btn green fixedw_button" role="button" title="Resubmit For Credit Check"><span class="glyphicon glyphicon-ok"></span></a>
								&nbsp;
							@endif
						@endif
						@if (Gate::allows('vp_ap', $purchaseorder->id)  && $purchaseorder->canapproveorder)
							<a href="{{ url("/purchaseorders/rejectc/" . $purchaseorder->id) }}" class="btn bm-btn red fixedw_button" role="button" title="Reject"><span class="glyphicon glyphicon-remove"></span></a>
							&nbsp;
							<a href="{{ url("/purchaseorders/approvec/" . $purchaseorder->id) }}" class="btn bm-btn green fixedw_button" role="button" title="Approve"><span class="glyphicon glyphicon-ok"></span></a>
							&nbsp;
						@endif
						@if (Gate::allows('po_rc') && ($purchaseorder->status_id == 4 || $purchaseorder->status_id == 14))
							@if ($purchaseorder->status_id == 4)
								<a href="{{ url("/purchaseorders/credit-reject/" . $purchaseorder->id) }}" class="btn bm-btn red fixedw_button" role="button" title="Reject"><span class="glyphicon glyphicon-remove"></span></a>
								&nbsp;
							@endif
							<a href="{{ url("/purchaseorders/creditrelease/" . $purchaseorder->id) }}" class="btn bm-btn green fixedw_button" role="button" title="Credit Release"><span class="glyphicon glyphicon-ok"></span></a>
							&nbsp;	
						@endif
						@if ($purchaseorder->canDelete())
							<a href="{{ url("/purchaseorders/delete/" . $purchaseorder->id) }}" class="btn bm-btn red fixedw_button" role="button" title="Delete"><span class="delete-icon-white"></span></a>
							&nbsp;	
						@endif
					</div>
			</div> 
		</div>	
	</div>			
@stop	
@push('scripts')	
	@if (isset($purchaseorder))
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	@endif

	<script type="text/javascript">
		$(document).ready(function(){
			Echo.channel('<?= "po.$purchaseorder->id" ?>').listen('.po.status.update', function(e) {
				$("#po_status").text(e.newStatus)
				if(e.messageType == "danger")
					toastr.error(e.message, "Purchase order update")
				else if(e.messageType == "success")
					toastr.success(e.message, "Purchase order update")
				else
					toastr.info(e.message, "Purchase order update")
			});
		});

		function toggleItemHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".po-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".po-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
	</script>
@endpush