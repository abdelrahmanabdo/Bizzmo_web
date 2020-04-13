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
	@if (isset($quotation)) 
		{{ Form::model($quotation, array('id' => 'frmManage', 'class' => 'quotation-form')) }}
		{{ Form::hidden('id', $quotation->id) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'class' => 'quotation-form')) }}
	@endif

	@if (!isset($mode) && !isset($quotation))
	<div class="row flex-container bm-pg-header">
		<h2 class="bm-pg-title">Create Quotation</h2>
	</div>
	@endif
	
	<div class="po-form">
	<div class="row">	<!-- row 1 -->		
		<div class="col-md-3">  <!-- column 1 -->
			<div class="form-group"> <!-- quotation number -->  
				{{ Form::label('quotationname', 'Quotation no.') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->userrelation == 1 ? $quotation->vendornumber : $quotation->number }} (ver. {{ $quotation->version }})</p>
				@else					
					<p class='form-control-static'>New</p>
				@endif
			</div> <!-- quotation number -->  
		</div>					<!-- column 1 end -->
		<div class="col-md-3">  <!-- column 2 -->
			<div class="form-group"> <!-- date -->  
				{{ Form::label('date', 'Date') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->date }}</p>
				@else
					@if (isset($quotation))
						<p class='form-control-static'>{{ $quotation->date }}</p>
						{{ Form::hidden('date', old('date'), array('id' => 'date')) }}
					@else
						<p class='form-control-static'>{{ date('j/n/Y') }}</p>
						{{ Form::hidden('date', date('j/n/Y'), array('id' => 'date')) }}
						@if ($errors->has('date')) <p class="bg-danger">{{ $errors->first('date') }}</p> @endif
					@endif					
				@endif
			</div> <!-- date end -->  
		</div>					<!-- column 2 end -->
		<div class="col-md-6">  <!-- Column 3 -->
			<div class="form-group"> <!-- buyer -->  
				{{ Form::label('company_id', 'Buyer') }}

				@if (isset($mode) || (isset($quotation) && $quotation->userrelation == 1))
					<p class='form-control-static'>{{ $quotation->company->companyname }}</p>
					{{ Form::hidden('company_id', $quotation->company_id) }}
				@else
					<div class="row">
						<div class="col-xs-8" style="padding-right: 0px">
							{{ Form::select('company_id', $buyers, old('company_id'),array('id' => 'company_id', 'class' => 'form-control bm-select'))}}		
							@if ($errors->has('company_id')) <p class="bg-danger">{{ $errors->first('company_id') }}</p> @endif
						</div>
						<div class="col-xs-4 fav-icon">
							<a class="lnk-button" data-toggle="modal" data-target="#manageBuyersModal" role="button" style="margin-left: -5px;">
								<span class="white-star-icon" title="Manage favorite buyers"></span>
							</a>
						</div>
					</div>
				@endif
			</div> <!-- buyer --> 			
		</div>					<!-- column 3 end -->
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 2 -->
		<div class="col-md-3">
			<div class="form-group"> <!-- supplier -->  
				{{ Form::label('vendor_id', 'Supplier') }}
				@if (isset($quotation))	
					<p class='form-control-static'>{{ $quotation->vendor->companyname }}</p>
					{{ Form::hidden('vendor_id', $quotation->vendor_id, array('id' => 'vendor_id', 'class' => 'form-control'))}}		
				@else
					<p class='form-control-static'>{{ $vendor->companyname }}</p>
					{{ Form::hidden('vendor_id', $vendor->id, array('id' => 'vendor_id', 'class' => 'form-control'))}}		
					@if ($errors->has('vendor_id')) <p class="bg-danger">{{ $errors->first('vendor_id') }}</p> @endif
				@endif				
			</div> <!-- supplier --> 			
		</div>
		@if (isset($quotation) && isset($quotation->po_id))
		<div class="col-md-3">  <!-- column 4 -->
			<div class="form-group"> <!-- supplier -->  
				{{ Form::label('po', 'Purchase Order') }}
				@php $poView = $quotation->po->userrelation == 1 ? 'view' : 'vview' @endphp
				<br><a href="/purchaseorders/{{ $poView }}/{{$quotation->po->id}}" class='form-control-static'>#{{ $quotation->po->number }} (ver. {{ $quotation->po->version }})</p>
			</div> <!-- supplier -->
		</div>
		@endif
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 3 -->
		<div class="col-md-8">  <!-- column 1 -->
			<div class="form-group"> <!-- shipping address -->  
				{{ Form::label('shipaddress', 'Shipping address') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->shippingaddress->address }}</p>
					@if ($mode == 'h')
						@foreach ($quotation->audits as $audit)
							@if (array_key_exists('shippingaddress_id', $audit->old_values))
								<p class="small bg-warning"> {{ $shippingaddresses[array_search($audit->old_values['shippingaddress_id'], array_column($shippingaddresses, 'id'))]['address'] }} </p>
							@endif
						@endforeach						
					@endif
				@else
					<?php
				$addresses = [];
				foreach ($shippingaddresses as $address) {
					$addresses[$address->id] = "$address->partyname ($address->address)";
				}
				?>
					@if (old('shippingaddress_id') == '0')
						{{ Form::text('shipaddress', old('shipaddress'), array('id' => 'shipaddress', 'class' => 'form-control', 'style' => 'display: inline-block;')) }}
						{{ Form::select('shippingaddress_id', ['0' => 'New'], old('shippingaddress_id'),array('id' => 'shippingaddress_id', 'class' => 'form-control bm-select', 'style' => 'display: none;'))}}
						@if ($errors->has('shipaddress')) <p class="bg-danger">{{ $errors->first('shipaddress') }}</p> @endif
					@else						
						@if (isset($quotation) && $quotation->userrelation != 2)
							{{ Form::select('shippingaddress_id', $addresses, old('shippingaddress_id'),array('id' => 'shippingaddress_id', 'class' => 'form-control bm-select', 'style' => 'display: none;'))}}
							<p class='form-control-static'>{{ $quotation->shippingaddress->address}}</p>
						@else
							{{ Form::select('shippingaddress_id', $addresses, old('shippingaddress_id'),array('id' => 'shippingaddress_id', 'class' => 'form-control bm-select', 'style' => 'display: inline-block;'))}}
							@if ($errors->has('shippingaddress_id')) <p class="bg-danger">{{ $errors->first('shippingaddress_id') }}</p> @endif							
						@endif
					@endif										
				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('shippingaddress_id', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['shippingaddress_id'] }} </p>
					@endif
				@endif
			</div> <!-- shipping address end -->  			
		</div>
	</div>				<!-- end row 3 -->
	<div class="row">	<!-- row 4 -->
		<div class="col-md-3">  <!-- column 3 -->
			<div class="form-group"> <!-- shipping country -->  
				{{ Form::label('shippingcountry', 'Shipping country') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->shippingaddress->city ? $quotation->shippingaddress->city->country->countryname : $quotation->shippingaddress->country_name }}</p>
					@if ($mode == 'h')
						@foreach ($quotation->audits as $audit)
							@if (array_key_exists('shippingaddress_id', $audit->old_values))
								<p class="small bg-warning"> {{ $countries[$citycountry[$shippingaddresses[array_search($audit->old_values['shippingaddress_id'], array_column($shippingaddresses, 'id'))]['city_id']]] }} </p>
							@endif
						@endforeach						
					@endif
				@else
					@if (old('shippingaddress_id') == '0')
						{{ Form::select('country_id', $countries, old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select', 'style' => 'display: inline-block;')) }}
					@else
						@if ($shippingaddresses->count() == 0)
							<p class='form-control-static'></p>
						@else
							{{ Form::select('country_id', $countries, old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select', 'style' => 'display: none;')) }}
							@if (isset($quotation))
								<p class='form-control-static' name="shippingcountrytext" id="shippingcountrytext">{{ $quotation->shippingaddress->city ? $quotation->shippingaddress->city->country->countryname : $quotation->shippingaddress->country_name }}</p>
							@else
								<p class='form-control-static' name="shippingcountrytext" id="shippingcountrytext">{{ $shippingaddresses->first()->city->country->countryname }}</p>					
							@endif
						@endif						
					@endif
				@endif
			</div> <!-- shipping country end -->
		</div>					<!-- column 3 end -->
		<div id="otherLocationContainer" class="col-md-3" style="<?= old('country_id') != '0' ? 'display: none;' : '' ?>">
			<div class="form-group">
				{{ Form::label('countryName', 'Country name', ['style' => old('country_id') != '0' ? 'display: none;': '', 'id'=>'countryNameLabel']) }}					
				@if (old('country_id') == '0')
					{{ Form::text('otherCountry', old('otherCountry'), array('id' => 'otherCountry', 'class' => 'form-control', 'style' => 'display: inline-block;')) }}
				@else				
					{{ Form::text('otherCountry', old('otherCountry'), array('id' => 'otherCountry', 'class' => 'form-control', 'style' => 'display: none;')) }}
				@endif
				@if ($errors->has('otherCountry')) <p id="otherCountryError" class="bg-danger">{{ $errors->first('otherCountry') }}</p> @endif

				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('shippingcountry', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['shippingcountry'] }} </p>
					@endif
				@endif
			</div>
		</div>
		<div class="col-md-3">  <!-- column 4 -->
			<div class="form-group"> <!-- shipping city -->
				{{ Form::label('shippingcity', 'Shipping city')}}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->shippingaddress->city ? $quotation->shippingaddress->city->cityname : $quotation->shippingaddress->city_name }}</p>
					@if ($mode == 'h')
						@foreach ($quotation->audits as $audit)
							@if (array_key_exists('shippingaddress_id', $audit->old_values))
								<p class="small bg-warning"> {{ $cities[$shippingaddresses[array_search($audit->old_values['shippingaddress_id'], array_column($shippingaddresses, 'id'))]['city_id']] }} </p>
							@endif
						@endforeach						
					@endif
				@else
					@if (old('country_id') != '0')
						@if (old('shippingaddress_id') == '0')
							{{ Form::select('city_id', $cities, old('city_id'),array('id' => 'city_id', 'class' => 'form-control bm-select', 'style' => 'display: inline-block;')) }}
						@else
							@if ($shippingaddresses->count() == 0)
								<p class='form-control-static'></p>
							@else
								{{ Form::select('city_id', $cities, old('city_id'),array('id' => 'city_id', 'class' => 'form-control bm-select', 'style' => 'display: none;')) }}
								@if (isset($quotation))
									<p class='form-control-static' name="shippingcitytext" id="shippingcitytext">{{ $quotation->shippingaddress->city ? $quotation->shippingaddress->city->cityname : $quotation->shippingaddress->city_name }}</p>
								@else
									<p class='form-control-static' name="shippingcitytext" id="shippingcitytext">{{ $shippingaddresses->first()->city->cityname ? $shippingaddresses->first()->city->cityname : $shippingaddresses->first()->city->city_name }}</p>					
								@endif
							@endif						
						@endif
					@else
						{{ Form::select('city_id', $cities, old('city_id'),array('id' => 'city_id', 'class' => 'form-control bm-select', 'style' => 'display: none;')) }}
					@endif
					
					@if (old('country_id') == '0')
						{{ Form::text('otherCity', old('otherCity'), array('id' => 'otherCity', 'class' => 'form-control', 'style' => 'display: inline-block;')) }}
					@else				
						{{ Form::text('otherCity', old('otherCity'), array('id' => 'otherCity', 'class' => 'form-control', 'style' => 'display: none;')) }}
					@endif
					@if ($errors->has('otherCity')) <p id="otherCityError" class="bg-danger">{{ $errors->first('otherCity') }}</p> @endif

				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('shippingcity', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['shippingcity'] }} </p>
					@endif
				@endif
			</div> <!-- shipping city end -->  			
		</div>					<!-- column 4 end -->		
		@if(isset($mode) || old('shippingaddress_id') == '0' || $shippingaddresses->count() == 0 || (isset($quotation) && isset($changes)  && $quotation->isvendorchange))
		<div class="col-md-3">  <!-- column 2 -->
			<div class="form-group"> <!-- shipping po_box -->
				{{ Form::label('po_box', 'Shipping PO Box') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->shippingaddress->po_box }}</p>
					@if ($mode == 'h')
						@foreach ($quotation->audits as $audit)
							@if (array_key_exists('shippingaddress_id', $audit->old_values))
							<p class="small bg-warning"> {{ $shippingaddresses[array_search($audit->old_values['shippingaddress_id'], array_column($shippingaddresses, 'id'))]['po_box'] }} </p>
							@endif
						@endforeach						
					@endif
				@else
					@if (old('shippingaddress_id') == '0')
					{{ Form::text('po_box', old('po_box'), array('id' => 'po_box', 'class' => 'form-control', 'style' => 'display: inline-block;')) }}
						@if ($errors->has('po_box')) <p class="bg-danger">{{ $errors->first('po_box') }}</p> @endif
					@else
						@if ($shippingaddresses->count() == 0)
						<p class='form-control-static'></p>
						@else
						{{ Form::text('po_box', old('po_box'), array('id' => 'po_box', 'class' => 'form-control', 'style' => 'display: none;')) }}
							@if (isset($quotation))
							<p class='form-control-static' name="po_boxtext" id="po_boxtext">{{ $quotation->shippingaddress->po_box}}</p>
							@else
							<p class='form-control-static' name="po_boxtext" id="po_boxtext">{{ $company->shippingaddresses->first()->po_box }}</p>
							@endif
						@endif						
					@endif
				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('po_box', $changes))
					<p class="small bg-warning">vendor changed from: {{ $changes['po_box'] }} </p>
					@endif
				@endif
			</div> <!-- shipping po_box end -->  			
		</div>					<!-- column 2 end -->
		@endif
	</div>				<!-- end row 4 -->
	<div class="row">	<!-- row 5 -->
		<div class="col-md-3">  <!-- column 1 -->
			<div class="form-group"> <!-- incoterm -->  
				{{ Form::label('incoterm_id', 'Inco terms') }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $quotation->incoterm->name }}</p>
					@if ($mode == 'h')
						@foreach ($quotation->audits as $audit)
							@if (array_key_exists('incoterm_id', $audit->old_values))
								<p class="small bg-warning"> {{ $incoterms[$audit->old_values['incoterm_id']] }} </p>
							@endif
						@endforeach
						
					@endif
				@else					
					{{ Form::select('incoterm_id', $incoterms, old('incoterm_id'),array('id' => 'incoterm_id', 'class' => 'form-control bm-select'))}}		
					@if ($errors->has('incoterm_id')) <p class="bg-danger">{{ $errors->first('incoterm_id') }}</p> @endif
				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('incoterm_id', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['incoterm_id'] }} </p>
					@endif
				@endif
			</div> <!-- incoterm --> 			
		</div>					<!-- column 1 end -->
		<div class="col-md-3">  <!-- column 2 -->
			<div class="form-group"> <!-- currency -->  
				{{ Form::label('currency_id', 'Currency') }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $quotation->currency->name }}</p>
				@else					
					{{ Form::select('currency_id', $currencies, old('currency_id'),array('id' => 'currency_id', 'class' => 'form-control bm-select'))}}		
					@if ($errors->has('currency_id')) <p class="bg-danger">{{ $errors->first('currency_id') }}</p> @endif
				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('currency_id', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['currency_id'] }} </p>
					@endif
				@endif
			</div> <!-- currency --> 			
		</div>					<!-- column 2 end -->
		<div class="col-md-3">  <!-- column 3 -->
			<div class="form-group"> <!-- payment terms -->  
				{{ Form::label('paymentterm_id', 'Payment terms') }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $quotation->paymentterm->name }}</p>
					@if ($mode == 'h')
						@foreach ($quotation->audits as $audit)
							@if (array_key_exists('paymentterm_id', $audit->old_values))
								<p class="small bg-warning"> {{ $paymentterms[$audit->old_values['paymentterm_id']] }}</p>
							@endif
						@endforeach
						
					@endif
				@else
					@if (isset($quotation) && $quotation->userrelation != 2)
						{{ Form::select('paymentterm_id', $paymentterms, old('paymentterm_id'),array('id' => 'paymentterm_id', 'class' => 'form-control hidden bm-select'))}}
						<p class='form-control-static'>{{ $quotation->paymentterm->name}}</p>
					@else
						{{ Form::select('paymentterm_id', $paymentterms, old('paymentterm_id'),array('id' => 'paymentterm_id', 'class' => 'form-control bm-select'))}}
					@endif					
					@if ($errors->has('paymentterm_id')) <p class="bg-danger">{{ $errors->first('paymentterm_id') }}</p> @endif
				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('paymentterm_id', $changes)) {
							<p class="small bg-warning">vendor changed from: {{ $changes['paymentterm_id'] }} </p>
					@endif
				@endif
			</div> <!-- payment terms --> 			
		</div>					<!-- column 3 end -->
		<div class="col-md-6">  <!-- column 1 -->
			<div class="form-group"> <!-- note -->  				
				@if (isset($quotation))	
					{{ Form::label('stat_id', 'Status') }}
					<p id="qu_status" class='form-control-static bg-warning'>
						{{ $quotation->status->name }}
						@if($quotation->status->id == 14)
							<span class='text-danger'>{{substr($quotation->reason_for_rejection, 0, -1)}}</span>
						@endif
				@endif
			</div> <!-- note end -->  
		</div>					<!-- column 1 end -->
	</div>				<!-- end row 5 -->
	<div class="row">	<!-- row 6 -->		
		<div class="col-md-6">  <!-- column 1 -->
			<div class="form-group"> <!-- note -->  
				{{ Form::label('note', 'Note') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $quotation->note }}</p>
				@else										
					@if (isset($quotation))
						<p class='form-control-static'>{{ $quotation->note }}</p>
						{{ Form::hidden('note', old('note'), array('id' => 'note')) }}
					@else
						{{ Form::text('note', old('note'), array('id' => 'note', 'class' => 'form-control')) }}			
					@if ($errors->has('note')) <p class="bg-danger">{{ $errors->first('note') }}</p> @endif
					@endif					
				@endif
				@if (isset($quotation) && isset($changes)  && $quotation->isvendorchange)
					@if (array_key_exists('note', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['note'] }} </p>
					@endif
				@endif
			</div> <!-- note end -->
		</div>					<!-- column 1 end -->					
	</div>				<!-- end row 6 -->
	<div class="row">	<!-- row 7 -->		
		<div class="table-wrapper col-md-12"> <!-- column 1 -->
			<h4>Products</h4>
			<?php $itemcount = 0; ?>
			<table id="itemstable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						@if (!isset($mode))
							<th class="no-sort">
								<a href="" id="lnkitem" role="button" class="add-icon" title="Add Item" style="margin-left: 2px"></a>	
							</th>
						@endif
						@if (isset($mode))
						<th style="width: 1%;">&nbsp;</th>
						@endif
						<th>Product description&nbsp;&nbsp;</th>
						<th>MPN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>Brand&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>Unit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>Quantity</th>						
						<th>Price&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>Total</th>
					</tr>		
				</thead>
				<tbody>
					@if (old('itemid'))
						@php
							$i = 0;
							$total = 0;
							$itemcount = 0;
						@endphp
						@foreach (old('itemid') as $item)
							<tr style="{{ (old('itemdel')[$i]) ? 'display:none' : '' }}">
								<td>
									<a href="#" class="delete-icon" onclick="DelRow(this);return false;" title="Delete Product" style="margin-left: 4px" id="btnDelItem"></a>
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
									<?php
								if (isset(old('brand')[$i])) {
									$brandId = old('brand')[$i];
									$brandName = $brands[$brandId];
								} else {
									$brandId = null;
									$brandName = null;
								}
								?>
									<input name="selected_brand_id[]" id="selected_brand_id" type="hidden" class="form-control" value="{{ $brandId }}">
									<input name="selected_brand_name[]" id="selected_brand_name" type="hidden" class="form-control" value="{{ $brandName }}">
									{{ Form::select('brand[]', $brands, $brandName, array('class' => 'form-control select_brand bm-select', 'style' => 'width: 100%')) }}
									@if ($errors->has('brand.' . $i)) <p class="bg-danger">{{ $errors->first('brand.' . $i) }}</p> @endif
								</td>
								<td>
									{{ Form::select('unit_id[]', $unitsarr, old('unit_id')[$i], array('id' => 'unit_id', 'class' => 'in-table form-control bm-select'))}}
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
								@php
									$total = $total + (old('quantity')[$i] * old('price')[$i]);
								@endphp
							</tr>
						@php
							$itemcount = old('itemdel')[$i] ? $itemcount : $itemcount + 1;
							$i++;
							@endphp	
						@endforeach
					@else
						@if (isset($quotation))
							<?php $i = 0; ?>
							@foreach ($quotation->quotationitems as $item)
								<tr>
									@if (isset($mode))	
										<td style="text-align: center">
											@if ($item->audits->where('event', 'updated')->count() > 0)
											<a onclick="toggleItemHistory(this)">
												<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
											</a>
											@endif
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
									@else
										<td>
											<a href="#" class="delete-icon" onclick="DelRow(this);return false;" title="Delete Product" style="margin-left: 4px" id="btnDelOwner"></a>
											{{ Form::hidden('itemid[]', $item->id, array('id' => 'item_id')) }}
											{{ Form::hidden('itemdel[]', '', array('id' => 'itemdel', 'class' => 'form-control')) }}
										</td>
										<td>
											{{ Form::text('productname[]', $item->productname, array('id' => 'productname', 'class' => 'form-control')) }}
											@if ($errors->has('productname.' . $i)) <p class="bg-danger">{{ $errors->first('productname.' . $i) }}</p> @endif
											@if (isset($quotation) && $quotation->isvendorchange)
												@php													
													$audit = $item->audits->last();
													if (array_key_exists('productname', $audit->old_values) && $audit->id >= $quotation->audits->last()->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['productname'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::text('mpn[]', $item->mpn, array('id' => 'mpn', 'class' => 'form-control')) }}
											@if ($errors->has('mpn.' . $i)) <p class="bg-danger">{{ $errors->first('mpn.' . $i) }}</p> @endif
											@if (isset($quotation) && $quotation->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('mpn', $audit->old_values) && $audit->id >= $quotation->audits->last()->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['mpn'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::hidden('selected_brand_id[]', $item->brand->id, ['id' => 'selected_brand_id'])}}
											{{ Form::hidden('selected_brand_name[]', $item->brand->name, ['id' => 'selected_brand_name'])}}
											{{ Form::select('brand[]', [], "", array('class' => 'form-control select_brand bm-select', 'style' => 'width: 100%')) }}
											@if ($errors->has('brand.' . $i)) <p class="bg-danger">{{ $errors->first('brand.' . $i) }}</p> @endif
											@if (isset($quotation) && $quotation->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('brand', $audit->old_values) && $audit->id >= $quotation->audits->last()->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['brand'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::select('unit_id[]', $unitsarr, $item->unit_id, array('id' => 'unit_id', 'class' => 'in-table form-control bm-select'))}}		
											@if ($errors->has('unit_id.' . $i)) <p class="bg-danger">{{ $errors->first('unit_id.' . $i) }}</p> @endif
											@if (isset($quotation) && $quotation->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('unit_id', $audit->old_values) && $audit->id >= $quotation->audits->last()->id) {
														echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['unit_id'] . '</p>';
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::text('quantity[]', $item->quantity * 100, array('id' => 'quantity', 'class' => 'form-control quantity')) }}
											@if (isset($quotation) && $quotation->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('quantity', $audit->old_values) && $audit->id >= $quotation->audits->last()->id) {
														if ($audit->old_values['quantity'] != $audit->new_values['quantity']) {
															echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['quantity'] . '</p>';
														}
													}
												@endphp
											@endif
										</td>
										<td>
											{{ Form::text('price[]', $item->price * 100, array('id' => 'price', 'class' => 'form-control price')) }}
											@if (isset($quotation) && $quotation->isvendorchange)
												@php
													$audit = $item->audits->last();
													if (array_key_exists('price', $audit->old_values) && $audit->id >= $quotation->audits->last()->id) {
														if ($audit->old_values['price'] != $audit->new_values['price']) {
															echo '<p class="small bg-warning">vendor changed from: ' . $audit->old_values['price'] . '</p>';
														}
													}
												@endphp
											@endif
										</td>
										<td align="right">{{ number_format($item->subtotal, 2, '.', ',') }}</td>
									@endif
								</tr>
								<tr class="quotations-history-wrapper" style="display: none">
									<td colspan="8">
										<table class="table table-striped table-bordered po-history-table" style="text-size: 11px !important">	
											<thead style="background: #fcf8e3;">
												<tr>
													<th>On</th>
													<th>By</th>
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
													
														<tr>
															<td> {{ $audit->created_at }} </td>
															<td> {{ $audit->user->name }} </td>															
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
															<td align="right">
																@if (array_key_exists('quantity', $audit->new_values))
																	{{ number_format($audit->new_values['quantity'], 2, '.', ',') }}
																@else
																	No change
																@endif
															</td>
															<td align="right">
																@if (array_key_exists('price', $audit->new_values))
																	{{ number_format($audit->new_values['price'], 2, '.', ',') }}
																@else
																	No change
																@endif
															</td>
														</tr>
													
												@endforeach
											</tbody>
										</table>
									</td>
								</tr>
								<?php 
									$i = $i + 1;
									$itemcount = $i;
								?>
							@endforeach							
							@if (isset($mode) && count($quotation->deletedProducts()) > 0)	
								<tr>	
									<td style="text-align: center">
										<a onclick="toggleDeletedItems(this)">
											<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Deleted Products" />
										</a>
									</td>							
									<td colspan="7" align="center">
										<strong>Deleted Products</strong>
									</td>
								</tr>
								<tr class="quotations-deleted-items-wrapper" style="display: none">
									<td colspan="8">
										<table class="table table-striped table-bordered po-history-table" style="text-size: 11px !important">	
											<thead style="background: #fcf8e3;">
												<tr>
													<th>On</th>
													<th>By</th>
													<th>Product description</th>
													<th>MPN</th>
													<th>Brand</th>
													<th>Unit</th>
													<th>Quantity</th>						
													<th>Price</th>
												</tr>		
											</thead>
											<tbody>
												@foreach ($quotation->deletedProducts() as $audit)
													<tr style="background-color: rgba(208, 21, 21, 0.2);">
														<td>{{ $audit->created_at }}</td>
														<td>{{ $audit->user->name }}</td>
														<td>
															@if (array_key_exists('productname', $audit->old_values))
																{{ $audit->old_values['productname'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('MPN', $audit->old_values))
																{{ $audit->old_values['MPN'] }}
															@elseif (array_key_exists('mpn', $audit->old_values))
																{{ $audit->old_values['mpn'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('brand_id', $audit->old_values))
																{{ $brands[$audit->old_values['brand_id']] }}
															@elseif (array_key_exists('brand', $audit->old_values))
																{{ $audit->old_values['brand'] }}
															@else
																No change
															@endif
														</td>
														<td>
															@if (array_key_exists('unit_id', $audit->old_values))
																EA
															@else
																No change
															@endif
														</td>
														<td align="right">
															@if (array_key_exists('quantity', $audit->old_values))
																{{ $audit->old_values['quantity'] }}
															@else
																No change
															@endif
														</td>
														<td align="right">
															@if (array_key_exists('price', $audit->old_values))
																{{ $audit->old_values['price'] }}
															@else
																No change
															@endif
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</td>
								</tr>
							@endif
						@endif
					@endif
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
						@if (isset($quotation))
							<td align="right">{{ number_format($quotation->total, 2, '.', ',') }}</td>							
						@else
							@if (old('itemid'))
								<td align="right">{{ number_format($total, 2, '.', ',') }}</td>							
							@else
								<td align="right">0.00</td>
							@endif
						@endif						
					</tr>
					@if (isset($quotation) && $quotation->userrelation != 2)
						<tr>
							<td>&nbsp;</td>
							<td>
								Fees - 
								@if (isset($quotation))
									{{ number_format($quotation->buyup, 2, '.', ',') }}
									{{ Form::hidden('fees', $quotation->buyup, array('id' => 'fees')) }}
								@else
									@if (old('itemid'))
										{{ number_format(old('fees'), 2, '.', ',') }}
										{{ Form::hidden('fees', old('fees'), array('id' => 'fees')) }}
									@else
										{{ number_format($buyup, 2, '.', ',') }}
										{{ Form::hidden('fees', $buyup, array('id' => 'fees')) }}
									@endif
								@endif
								%
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($quotation))
								<td align="right">{{ number_format($quotation->total * $quotation->buyup / 100, 2, '.', ',') }}</td>							
							@else
								@if (old('itemid'))
									<td align="right">{{ number_format($total * old('fees') / 100, 2, '.', ',') }}</td>
								@else
									<td align="right">{{ number_format(0, 2, '.', ',') }}</td>
								@endif								
							@endif						
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								@if (isset($quotation))
									VAT {{ $quotation->vat }}<span> %</span>
									{{ Form::hidden('VAT', $quotation->vat, array('id' => 'VAT')) }}
								@else
									VAT - {{ old('vat', $vat) }}<span> %</span>
									{{ Form::hidden('VAT', old('vat', $vat), array('id' => 'VAT')) }}
								@endif								
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($quotation))
								<td align="right">{{ number_format(($quotation->total + ($quotation->total * $quotation->buyup / 100)) * $quotation->vat / 100, 2, '.', ',') }}</td>							
							@else
								@if (old('itemid'))
									<td align="right">{{ number_format(($total + ($total * old('fees') / 100)) * $vat / 100, 2, '.', ',') }}</td>
								@else
									<td align="right">{{ number_format(0, 2, '.', ',') }}</td>
								@endif								
							@endif						
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Grand Total</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($quotation))
								<td align="right">{{ number_format($quotation->total * (1 + $quotation->buyup / 100) + ($quotation->total + ($quotation->total * $quotation->buyup / 100)) * $quotation->vat / 100, 2, '.', ',') }}</td>							
							@else
								@if (old('itemid'))
									<td align="right">{{ number_format(($total + ($total * old('fees') / 100) + (($total + ($total * old('fees') / 100)) * $vat /100)), 2, '.', ',') }}</td>
								@else
									<td align="right">{{ number_format(0, 2, '.', ',') }}</td>
								@endif								
							@endif						
						</tr>
					@else
						@if (isset($quotation))
							<?php $hidden = "hidden" ?>
						@else
							<?php $hidden = "" ?>
						@endif
						<tr class="hidden">
							<td>&nbsp;</td>
							<td>
								Feesss - 
									{{ number_format(0, 2, '.', ',') }}
									{{ Form::hidden('fees', 0, array('id' => 'fees')) }}
								%
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($quotation))
								<td align="right">{{ number_format($quotation->total * 0 / 100, 2, '.', ',') }}</td>							
							@else
								@if (old('itemid'))
									<td align="right">{{ number_format($total * 0 / 100, 2, '.', ',') }}</td>
								@else
									<td align="right">{{ number_format(0, 2, '.', ',') }}</td>
								@endif								
							@endif						
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								@if (isset($quotation))
									VAT - <span id="vatspan">{{ $quotation->vat }}</span><span> %</span>
									{{ Form::hidden('VAT', $quotation->vat, array('id' => 'VAT')) }}
								@else
									VAT - <span id="vatspan">{{ old('vat', $vat) }}</span><span> %</span>
									{{ Form::hidden('VAT', old('vat', $vat), array('id' => 'VAT')) }}
								@endif								
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($quotation))
								<td align="right">{{ number_format($quotation->total * $quotation->vat / 100, 2, '.', ',') }}</td>							
							@else
								@if (old('itemid'))
									<td align="right">{{ number_format($total * $vat / 100, 2, '.', ',') }}</td>
								@else
									<td align="right">{{ number_format(0, 2, '.', ',') }}</td>
								@endif								
							@endif						
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>Grand Total</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							@if (isset($quotation))
								<td align="right">{{ number_format($quotation->total + $quotation->total  * $quotation->vat / 100, 2, '.', ',') }}</td>							
							@else
								@if (old('itemid'))
									<td align="right">{{ number_format($total + $total * $vat / 100, 2, '.', ',') }}</td>
								@else
									<td align="right">{{ number_format(0, 2, '.', ',') }}</td>
								@endif								
							@endif						
						</tr>
					@endif																		
				</tfoot>
			</table>
			<input type="hidden" name="itemcount" id="itemcount" value="{{$itemcount}}">
			@if ($errors->has('itemcount')) <p class="bg-danger">{{ $errors->first('itemcount') }}</p> @endif
		</div>					<!-- column 1 end -->
	</div>				<!-- end row 7 -->
	<div class="row">	<!-- row 8 -->		
			<div class="col-md-12"> <!-- column 1 -->
			@if (isset($mode))
				<?php
					$showRelease = Gate::allows('qu_rl', $quotation->id) && $quotation->canreleaseorder
				?>
				@if ($showRelease)
					<div class="col-xs-7" style="text-align: left"> <!-- Column 3 -->
						<div class="col-xs-2" style="padding-left: 0">
							<a href="{{ url("/quotations/orderreleasec/" . $quotation->id) }}" id="submitquotation" class="btn bm-btn green" role="button" title="Submit">Submit</a>
						</div>
						<div class="col-xs-10"> <!-- Column 1 -->
							<div class="checkbox" style="margin-top: 0">
								<label class="checkbox" style="display: inline-block">
									<input class="bm-checkbox" type="checkbox" name="cbconfirm" id ="cbconfirm">
									<span class="checkmark" style="top: 1px"></span>
									<span class="bm-sublabel">
										I hereby confirm that i have read and agreed to the Terms and Conditions.
									</span>
								</label>
								{{-- @include('quotations.terms') --}}
							</div>
						</div>
					</div>
				@endif
				<div class="<?= $showRelease ? 'col-xs-5' : 'col-xs-12' ?>" style="<?= $showRelease ? 'text-align: right' : 'text-align: center' ?>">				
					@if (Gate::allows('qu_ch') || Gate::allows('cq_ch') || Gate::allows('cq_ap'))
						@if ((Gate::allows('qu_ch', $quotation->id) || Gate::allows('cq_ch', $quotation->id)) && $quotation->canchange)
							<a href="{{ url("/quotations/" . $quotation->id) }}" class="btn bm-btn sun-flower" role="button">Edit</a>
							&nbsp;					
						@elseif (Gate::allows('cq_ch', $quotation->id) && $quotation->status_id == 24)
							<a href="{{ url("/quotations/change/" . $quotation->id) }}" class="btn bm-btn sun-flower" role="button">Edit</a>
							&nbsp;
						@endif

						@if (Gate::allows('qu_ch', $quotation->id) && $quotation->canCancel() && !$quotation->canDelete())
							<a href="{{ url("/quotations/cancel/" . $quotation->id) }}" class="btn bm-btn red" role="button" title="Cancel">Cancel</a>
							&nbsp;
						@endif
					@endif
					@if (Gate::allows('cq_ap', $quotation->id)  && $quotation->canapproveorder)
						<a href="{{ url("/quotations/rejectc/" . $quotation->id) }}" class="btn bm-btn red" role="button" title="Reject">Reject</a>
						&nbsp;
						<a href="{{ url("/quotations/approvec/" . $quotation->id) }}" class="btn bm-btn green" role="button" title="Approve">Approve</a>
						&nbsp;
					@endif
					@if ($quotation->canDelete())
						<a href="{{ url("/quotations/delete/" . $quotation->id) }}" class="btn bm-btn red" role="button" title="Delete">Delete</a>
						&nbsp;	
					@endif
					</div>
				@else
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
					<a href="" class="btn btn-info bm-btn green" id="lnksubmit" type="button" title="Save">
						Save
					</a>
				@endif
			</div> <!-- column 1 end -->
	</div>				<!-- end row 8 -->
	</div>
	
	{{ Form::close() }}
	@if(!isset($mode) || (isset($quotation) && $quotation->userrelation == 2))
		 @include('quotations.manage_buyers')
	@endif
@stop	
@push('scripts')	
	@if (isset($quotation))
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->
	@endif

	<script type="text/javascript">
		Number.prototype.format = function(n, x, s, c) {
			var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
					num = this.toFixed(Math.max(0, ~~n));
			return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
		};

		$(document).ready(function(){
			$("#submitquotation").bind('click', function(e) {
				e.preventDefault();
				if (!$("#cbconfirm").is(':checked')) {
					alert('You must check the confirmation text.');
				} else {
					@if (isset($quotation))
					window.location.replace("{{ url("/quotations/orderreleasec/" . $quotation->id) }}");
					@endif
				}
			});

			$('.select_brand').each(function(i, elm) {
				$(elm).select2({
					placeholder: 'Search for a brand',
					ajax: {
						url: '/brand/',
						dataType: 'json',
						processResults: function (data) {
						return {
							results:  $.map(data, function (item) {
								return {
									text: item.name,
									id: item.id
								}
							})
						};
						},
						cache: true
					}
				});
				
				var brand_id = $(elm).siblings('#selected_brand_id').val();
				var brand_name = $(elm).siblings('#selected_brand_name').val();

				if ($(elm).find("option[value='" + brand_name + "']").length) {
						$(elm).val(brand_name).trigger('change');
				} else {
					// Create a DOM Option and pre-select by default
					var newOption = new Option(brand_name, brand_id, true, true);
					// Append it to the select
					$(elm).append(newOption).trigger('change');
				}
			})


			Updatecity();
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});
			
			$("#company_id").change(function(){
				console.log("SSS")
				var formData = new FormData;
				formData.append('company_id', $('select[name=company_id]').val());
				formData.append('_token', $('input[name=_token]').val());
				$.ajax({					
                    url: '/companies/companyshipaddr',
                    type: 'post',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                   success: function(data){
										var j = 0;
						$('#shippingaddress_id').empty();
						$.each(data.addresses, function(i, item) {
							console.log(item)
							if (j == 0) {
								$('#shippingaddress_id').append($("<option></option>").attr("value", item.id).text(item.name).attr("selected", true));
							} else {
								$('#shippingaddress_id').append($("<option></option>").attr("value", item.id).text(item.name)).attr("selected", false);
							}
							//console.log(j + '-' + i + '-' + item);
							j = j + 1;							
						});

						// Update payment terms list
						var k = 0;
						$('#paymentterm_id').empty();
						$.each(data.paymentTerms, function(i, item) {
							if (k == 0) {
								$('#paymentterm_id').append($("<option></option>").attr("value", i).text(item).attr("selected", true));
							} else {
								$('#paymentterm_id').append($("<option></option>").attr("value", i).text(item)).attr("selected", false);
							}
							k++;							
						});

						ShippingAddressUpdate()
						PaymentTermsUpdate();
					}, // End of success function of ajax form
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                })				
			}); // $("#company_id").change end
			
			$("#country_id").change(function(){
				Updatecity();
			}); // $("#country_id").change end
			
			$("#shippingaddress_id").change(function(){
				ShippingAddressUpdate();
			}); // $("#shippingaddress_id").change end
			
			$('.quantity').mask('000000000000000.00', {reverse: true});
			$('.price').mask('000000000000000.00', {reverse: true});
			//paymentterm_id change
			$("#paymentterm_id").change(function(){
				PaymentTermsUpdate();
			});
			//paymentterm_id change end
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
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" title="Delete Product" style="margin-left: 4px" id="btnDelItem" type="button"></a>';
				row = row + '<input name="itemid[]" type="hidden" class="form-control">';
				row = row + '<input name="itemdel[]" id="itemdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="productname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="mpn[]" type="text" class="form-control"></td>';
				row = row + '<td><select name="brand[]" class="form-control select_brand bm-select" style="width: 100%"><option value="{{null}}" selected="selected"></option></select></td>';
				row = row + '<td><select name="unit_id[]" class="in-table form-control bm-select">';
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

				$('.select_brand').each(function(i, elm) {
					$(elm).select2({
						placeholder: 'Search for a brand',
						ajax: {
							url: '/brand/',
							dataType: 'json',
							processResults: function (data) {
							return {
								results:  $.map(data, function (item) {
									return {
										text: item.name,
										id: item.id
									}
								})
							};
							},
							cache: true
						}
					});
				})
			});

			// Show 5 products by default
			@if ($itemcount < 1 && isset($isCreate) && $isCreate && !$errors->has('itemcount'))
			for (let i = 0; i < 5; i++) {
				$("#lnkitem").trigger('click')
			}
			@endif
		});
		
		function DelRow(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
					var inputval = inputs[j].id;         
					if (inputval == 'itemdel') {
						// New option
						var newOption = new Option("A", 1, true, true);
						var selectBrand = tr.cells[3].getElementsByTagName("select")[0];
						
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';
						tr.cells[2].getElementsByTagName("input")[0].value='A';
						$(selectBrand).append(newOption).trigger('change');
						tr.cells[5].getElementsByTagName("input")[0].value='1';
						tr.cells[6].getElementsByTagName("input")[0].value='0';
						$("#itemcount").val(parseInt($("#itemcount").val()) - 1);
					}
				}
			tr.style.display = 'none';
			var total = OrderTotal();
		}
		
		function OrderTotal () {
		var table = document.getElementById('itemstable');
		var rowLength = table.rows.length;
		var ordertotal = 0.0;		
		if ($("#itemstable tr:visible").length > 3) {
			var row = table.rows[0];
			for(var i=1; i<row.cells.length; i+=1){		
				switch (row.cells[i].innerHTML) {
					case 'Total':
						var subtotalcell = i;
						break;
					case 'Quantity':
						var qtycell = i;
						break;
					case 'Price&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':
						var pricecell = i;
						break;
				}
			}
			var row = table.rows[rowLength - 1];
			if (row.cells[0].innerHTML == 'Grand Total' || row.cells[1].innerHTML == 'Grand Total') {
				var footrow = 4;
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
						row.cells[subtotalcell].innerHTML = (parseFloat(quantity) * parseFloat(price)).format(2, 3, ',', '.');
						ordertotal = parseFloat(ordertotal) + (parseFloat(quantity) * parseFloat(price));
					} else {
						alert("Quantity and price must be numbers");
						ordertotal = -1;
						return false;
					}
				}
			}		
		}

		var row = table.rows[table.rows.length - footrow];
		row.cells[row.cells.length - 1].innerHTML = ordertotal.format(2, 3, ',', '.');
		var row = table.rows[table.rows.length - 3]; // Fees row		
		var fees = 0;
		if ((row.cells[0].innerHTML).indexOf('Fees') != -1 || (row.cells[1].innerHTML).indexOf('Fees') != -1) {
			//alert('aa');
			if ((row.cells[0].innerHTML).indexOf('Fees') != -1) {
				var fees = row.cells[0].getElementsByTagName("input")[0].value;
			}
			if ((row.cells[1].innerHTML).indexOf('Fees') != -1) {
				var fees = row.cells[1].getElementsByTagName("input")[0].value;
			}
			row.cells[row.cells.length - 1].innerHTML = (ordertotal * fees / 100).format(2, 3, ',', '.');
		}
		
		var row = table.rows[table.rows.length - 2]; // VAT row		
		var vat = 0;
		if ((row.cells[0].innerHTML).indexOf('VAT') != -1 || (row.cells[1].innerHTML).indexOf('VAT') != -1) {
			if ((row.cells[0].innerHTML).indexOf('VAT') != -1) {
				var vat = row.cells[0].getElementsByTagName("input")[0].value;
			}
			if ((row.cells[1].innerHTML).indexOf('VAT') != -1) {
				var vat = row.cells[1].getElementsByTagName("input")[0].value;
			}
			row.cells[row.cells.length - 1].innerHTML = ((ordertotal + ordertotal * fees / 100) * vat / 100).format(2, 3, ',', '.');
		}
		
		var row = table.rows[table.rows.length - 1]; // Grand tTotal row		
		if (row.cells[0].innerHTML == 'Grand Total' || row.cells[1].innerHTML == 'Grand Total') {
			var row = table.rows[table.rows.length - 2];
			var buyup = row.cells[row.cells.length - 1].innerHTML;
			var row = table.rows[table.rows.length - 1];
			row.cells[row.cells.length - 1].innerHTML = (ordertotal + (ordertotal * fees / 100) + (ordertotal + ordertotal * fees / 100) * vat / 100).format(2, 3, ',', '.');
		}
	}
	function Updatecity () {
		var url = '/countries/cities';
			// ajax call
			$('#city_id').find('option').remove().end();
			$.ajax({
				url: url,
				type:'post',
				data: {
					'country_id':$('select[name=country_id]').val(),
					'_token': $('input[name=_token]').val()
				},
				cache: false,
				success: function(data){
					var j = 0;
					$.each(data, function(i, item) {
						if (j == 0) {
							$('#city_id').append($("<option></option>").attr("value", i).text(item).attr("selected", true));
						} else {
							$('#city_id').append($("<option></option>").attr("value", i).text(item)).attr("selected", false);
						}
						//console.log(j);
						j = j + 1;							
					});
				}, // End of success function of ajax form
				error: function(output_string){				
					alert(jxhr.responseText);
				}
			}); //ajax call end
	}

	//country_id change
	$("#country_id").change(function(){
				var table = document.getElementById('itemstable');
				var row = table.rows[table.rows.length - 1];
				var formData = new FormData;
				formData.append('country_id', $('select[name=country_id]').val());
				formData.append('_token', $('input[name=_token]').val());
				@if (isset($quotation))
					@php
						echo "formData.append('company_id', " . $quotation->vendor_id . ");";
					@endphp
				@else
					@php
						echo "formData.append('company_id', " . $vendor->id . ");";
					@endphp
				@endif					
					$.ajax({					
						url: '/companies/get-vat',
						type: 'quotationST',
						processData: false,
						contentType: false,
						cache: false,
						data: formData,
						dataType: 'JSON',
						success: function(response){
							var table = document.getElementById('itemstable');
							var row = table.rows[table.rows.length - 2];
							row.cells[row.cells.length - 1].innerHTML = response.toFixed(2);
							
							if ((row.cells[0].innerHTML).indexOf('VAT') != -1 || (row.cells[1].innerHTML).indexOf('VAT') != -1) {
								if ((row.cells[0].innerHTML).indexOf('VAT') != -1) {
									row.cells[0].getElementsByTagName("input")[0].value = response;
									row.cells[0].firstChild.nodeValue = 'VAT - ' + response.toFixed(2);
								}
								if ((row.cells[1].innerHTML).indexOf('VAT') != -1) {
									row.cells[1].getElementsByTagName("input")[0].value = response;
									row.cells[1].firstChild.nodeValue = 'VAT - ' + response.toFixed(2);
								}
								row.cells[row.cells.length - 1].innerHTML = (parseFloat(table.rows[table.rows.length - 4].cells[row.cells.length - 1].innerHTML) * parseFloat(response)).toFixed(2);
							}
							var total = OrderTotal();
						}
					})
			});

		//country_id change
	$("#country_id").change(function(){
		var countriId = $('select[name=country_id]').val();
		
		if(countriId == 0) {
			$("#otherCountry").show()
			$("#otherCountryError").show()
			$("#countryNameLabel").show()
			$("#otherLocationContainer").show()
			
			$("#otherCity").show()
			$("#city_id").hide()
		}
		else {
			$("#otherCountry").hide()
			$("#otherCountryError").hide()
			$("#countryNameLabel").hide()		
			$("#otherLocationContainer").hide()				
			
			$("#otherCity").hide()
			$("#city_id").show()
		}
	});

	function PaymentTermsUpdate() {
		var table = document.getElementById('itemstable');
		var row = table.rows[table.rows.length - 1];
		if (row.cells[0].innerHTML != 'Grand Total' && row.cells[1].innerHTML != 'Grand Total') {
		}
		var formData = new FormData;
			formData.append('paymentterm_id', $('select[name=paymentterm_id]').val());
			formData.append('_token', $('input[name=_token]').val());
			@if (isset($quotation))
				@php
					//echo "formData.append('company_id', " . $quotation->vendor_id . ");";
				@endphp
			@else
				@php
					//echo "formData.append('company_id', " . $vendor->id . ");";
				@endphp
			@endif
			formData.append('company_id', $('#company_id').val());
		$.ajax({					
			url: '/companies/getbuyup',
			type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: formData,
			dataType: 'JSON',
			success: function(response){
				//console.log('aa' + response);						
				var table = document.getElementById('itemstable');
				var row = table.rows[table.rows.length - 3];
				row.cells[row.cells.length - 1].innerHTML = response.toFixed(2);
				
				if ((row.cells[0].innerHTML).indexOf('Fees') != -1 || (row.cells[1].innerHTML).indexOf('Fees') != -1) {
					if ((row.cells[0].innerHTML).indexOf('Fees') != -1) {
						row.cells[0].getElementsByTagName("input")[0].value = response;
						row.cells[0].firstChild.nodeValue = 'Fees - ' + response.toFixed(2);
					}
					if ((row.cells[1].innerHTML).indexOf('Fees') != -1) {
						row.cells[1].getElementsByTagName("input")[0].value = response;
						row.cells[1].firstChild.nodeValue = 'Fees - ' + response.toFixed(2);
					}
					console.log(table.rows[table.rows.length - 4].cells[row.cells.length - 1].innerHTML);
					row.cells[row.cells.length - 1].innerHTML = (parseFloat(table.rows[table.rows.length - 4].cells[row.cells.length - 1].innerHTML) * parseFloat(response)).toFixed(2);
				}
				var total = OrderTotal();
			},
			error: function(e,a,b){
				console.log(e,a,b);
			}
		})
	}
	
	function ShippingAddressUpdate() {
		var formData = new FormData;
		formData.append('shippingaddress_id', $('select[name=shippingaddress_id]').val());
		formData.append('_token', $('input[name=_token]').val());
		$.ajax({					
			url: '/shippingaddressdata',
			type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: formData,
			dataType: 'JSON',
			success: function(response){
				console.log('aa' + response);
				$("#po_boxtext").text(response.po_box);
				$("#shippingcountrytext").text(response.city ? response.city.country.countryname : response.country_name);
				$("#shippingcitytext").text(response.city ? response.city.cityname : response.city_name);
				$("#VAT").val(response.vat ? response.company.vat : 0);
				$("#vatspan").text(response.vat ? response.company.vat : 0);
				var total = OrderTotal();
			},
			error: function(e,a,b){
				console.log(e,a,b);
			}
		})
	}
	
	// @if (isset($quotation))
	// $(document).ready(function(){
	// 	Echo.channel('<?= "quotation.$quotation->id" ?>').listen('.quotation.status.update', function(e) {
	// 		$("#qu_status").text(e.newStatus)
	// 		if(e.messageType == "danger")
	// 			toastr.error(e.message, "Quotation update")
	// 		else if(e.messageType == "success")
	// 			toastr.success(e.message, "Quotation update")
	// 		else
	// 			toastr.info(e.message, "Quotation update")
	// 	});
	// });
	// @endif

	function toggleItemHistory(btn) {
		if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
			$(btn).parent().parent().next(".quotations-history-wrapper").hide()

			$(btn).find("span").removeClass("glyphicon-minus-sign")
			$(btn).find("span").addClass("glyphicon-plus-sign")
		} else {
			$(btn).parent().parent().next(".quotations-history-wrapper").show()

			$(btn).find("span").removeClass("glyphicon-plus-sign")
			$(btn).find("span").addClass("glyphicon-minus-sign")
		}
	}

	function toggleDeletedItems(btn) {
		if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
			$(btn).parent().parent().next(".quotations-deleted-items-wrapper").hide()

			$(btn).find("span").removeClass("glyphicon-minus-sign")
			$(btn).find("span").addClass("glyphicon-plus-sign")
		} else {
			$(btn).parent().parent().next(".quotations-deleted-items-wrapper").show()

			$(btn).find("span").removeClass("glyphicon-plus-sign")
			$(btn).find("span").addClass("glyphicon-minus-sign")
		}
	}
	</script>
@endpush