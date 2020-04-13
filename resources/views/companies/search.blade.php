@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if(isset($title))	
	<div class="">
		<h2 class="bm-title">{{ $title }}</h2>
		<br/>
	</div>
	@endif
	{{ Form::open(array('id' => 'frmManage')) }}
	@if (!$unconfirmed)
		<div class="row">	<!-- row 1 -->		
			<div class="col-md-4 col-lg-4">  <!-- column 1 -->
				<div class="form-group"> <!-- Company name -->  
					{{ Form::label('companyname', 'Company name', array('class' => 'bm-label')) }}
					{{ Form::text('companyname', Input::get('companyname'), array('id' => 'companyname', 'class' => 'form-control')) }}			
					{{ Form::hidden('id', Input::old('id'), array('id' => 'id')) }}
					@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
				</div> <!-- Company name -->  
			</div>					<!-- end col 1 -->
			<div class="col-md-4 col-lg-4">  <!-- column 2 -->
				<div class="form-group"> <!-- country -->  
					{{ Form::label('country_id', 'Country', array('class' => 'bm-label')) }}
					{{ Form::select('country_id', $countries, Input::get('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		
					@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
				</div> <!-- country end -->  
			</div>					<!-- end col 2 -->
			<div class="col-md-4 col-lg-4">  <!-- column 3 -->
				<div class="form-group"> <!-- currency -->  
					{{ Form::label('city_id', 'City', array('class' => 'bm-label')) }}
					{{ Form::select('city_id', $cities, Input::get('city_id'),array('id' => 'city_id', 'class' => 'form-control bm-select'))}}
					@if ($errors->has('city_id')) <p class="bg-danger">{{ $errors->first('city_id') }}</p> @endif
				</div> <!-- currency end -->  
			</div>				<!-- end col 3 -->
		</div>				<!-- end row 1 -->
		<div class="row-fluid row">	<!-- row 2 -->
			<div class="col-md-4 col-lg-4">  <!-- column 1 -->
				<div class="form-group"> <!-- currency -->  
					{{ Form::label('companytype_id', 'Company type', array('class' => 'bm-label')) }}
					{{ Form::select('companytype_id', $companytypes, Input::get('companytype_id'),array('id' => 'companytype_id', 'class' => 'form-control bm-select'))}}
					@if ($errors->has('companytype_id')) <p class="bg-danger">{{ $errors->first('companytype_id') }}</p> @endif
				</div> <!-- currency end -->  
			</div>				<!-- end col 1 -->
			<div class="col-md-4 col-lg-4">
			<div class="form-group">
				<label class="bm-label">Status</label>  
				<div class="form-horizontal">
					<div class="radio"> <!-- Active -->  
						<label class="checkbox">
							{{ Form::checkbox('active', Input::old('active'), Input::get('active'), array('id' => 'active', 'class' => 'bm-checkbox')) }}			
							<span class="checkmark"></span>
							<span class="bm-sublabel">Active only</span> 
						</div>
					</label>
					@if ($errors->has('active')) <p class="bg-danger">{{ $errors->first('active') }}</p> @endif
				</div>
				</div>
			</div>	<!-- end col 1 -->
			
		</div>				<!-- end row 2 -->
		<div class="row-fluid row">	<!-- row 3 --> 
		<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
			@if (isset($mode))
			@else
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
				<a href="" class="btn btn-info fixedw_button bm-btn" id="lnksubmit">
					<span class="glyphicon glyphicon-search" title="Search"></span>
				</a>
			@endif    
			
		</div> <!-- Column 1 end -->
		</div> <!--row 3 end -->
	@endif
	{{ Form::close() }}
	@if (!isset($companies))
		<p>You don't have companies yet</p>
	@else
		<div class="row-fluid">	<!-- row 5 -->
		<table id="mytable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Country</th>
				<th>Credit limit</th>
				@if (Gate::allows('pt_as') || Gate::allows('pt_vw'))
					<th>Buyer Payment Terms</th>
					<th>Supplier Payment Terms</th>
					<th>Supplier Delivery Types</th>
				@endif
				<th>Active</th>
				<th class="no-sort" width="17%">
					@if (Gate::allows('co_cr') && !$unconfirmed)
						<a href="{{ url("/companies/create") }}" class="add-icon" role="button" title="Add"></a>	
					@endif
				</th>
			</tr>		
		</thead>
		<tbody>			
			  @foreach ($companies as $company)
				<tr>
					<td> {{ $company->companyname ?? '' }} </td>
					<td> {{ $company->companytype->name ?? '' }} </td>					
					<td> {{ $company->country->countryname  ?? ''}} </td>
					<td align="right"> {{ number_format($company->creditlimit, 2, '.', ',') }} </td>
					@if (Gate::allows('pt_as') || Gate::allows('pt_vw'))
						<td>
							@if ($company->companytype_id == 1 || $company->companytype_id == 3)
								@if ($company->paymentterms->count() == 0)
									&nbsp;
								@else
									@foreach ($company->paymentterms as $paymentterm)
									{{ $paymentterm->name }}<br>
									@endforeach
								@endif
							@else
								&nbsp;
							@endif
						</td>
						<td>
							@if ($company->companytype_id == 2 || $company->companytype_id == 3)
								{{ $company->vendorpaymentterm->name }}
							@else
								&nbsp;
							@endif
						</td>
						<td style="white-space: nowrap">
							@if ($company->companytype_id == 2 || $company->companytype_id == 3)
								@if ($company->deliverytypes->count() == 0)
									&nbsp;
								@else
									@foreach ($company->deliverytypes as $deliverytype)
									{{ $deliverytype->name }}<br>
									@endforeach
								@endif
							@else
								&nbsp;
							@endif
						</td>
					@endif
					<td> @if ($company->active) Yes @else No @endif </td>
					<td>
					@if ($unconfirmed || (Gate::allows('co_co') && !$company->confirmed))
						@if (Gate::allows('co_cr') || Gate::allows('co_ch'))
							@if ($company->iscomplete)
								<a href="{{ url('/companies/view/' . $company->id) }}" role="button"><span class="confirm-icon" title="Confirm"></span></a>	
							@else
								@if (Gate::allows('co_cr', $company->id))
									<a href="{{ url('/companies/' . $company->id) . '/BasicInfo' }}" role="button"><span class="glyphicon glyphicon-repeat" title="Complete company data"></span></a>									
								@endif
							@endif
						@endif
					@else
						@if (Gate::allows('co_vw', $company->id))
							<a href="{{ url('/companies/view/' . $company->id) }}" role="button"><span class="view-icon" title="View"></span></a>
						@endif
						@if (Gate::allows('co_ch', $company->id))
							<a href="{{ url('/companies/' . $company->id) }}" role="button"><span class="edit-icon" title="Edit"></span></a>
							@if (!$company->vendors->count())
								<a href="{{ url('/companies/mysuppliers/' . $company->id) }}" role="button"><span class="star-icon" title="Manage favorite suppliers"></span></a>										
							@else
								<a href="{{ url('/companies/mysuppliers/' . $company->id) }}" role="button"><span class="empty-star-icon" title="Add favorite suppliers"></span></a>
							@endif
						@endif							
					@endif
					@if (Gate::allows('pt_as') && $company->active == 1 && ($company->companytype_id == 1 || $company->companytype_id == 3))
						<a href="{{ url('/companies/paymentterms/' . $company->id) }}" role="button"><span class="glyphicon glyphicon-usd" title="Buyer payment terms"></span></a>
					@else
						@if (Gate::allows('pt_as'))
							<a href="#" disabled="" role="button"><span style="color: gray; !important;" class="glyphicon glyphicon-usd grey gray" title="Company is a supplier. Cannot assign buyer payment terms"></span></a>
						@endif						
					@endif
					@if (Gate::allows('pt_as') && $company->active == 1 && ($company->companytype_id == 2 || $company->companytype_id == 3))						
						<a href="{{ url('/companies/supplierpaymentterms/' . $company->id) }}" role="button"><span style="color: green; !important;" class="glyphicon glyphicon-usd" title="Supplier payment terms"></span></a>
						<a href="{{ url('/companies/supplierdelivery/' . $company->id) }}" role="button"><span style="color: green; !important;" class="glyphicon glyphicon-lock" title="Supplier delivery type"></span></a>
					@else
						@if (Gate::allows('pt_as'))
							<a href="#"  disabled="" role="button"><span style="color: gray; !important;" class="glyphicon glyphicon-usd grey gray" title="Company is a buyer. Cannot assign supplier payment terms"></span></a>
						@endif						
					@endif
						
					</td>
				</tr>	
			  @endforeach			
		</tbody>
		</table>
		</div>				<!-- end row 5 -->
	@endif
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
			e.preventDefault();
		   	$("#submit").click();
			});	
			//data tables end
			$("#country_id").change(function(){
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
						$('#city_id').append($("<option></option>").attr("value", 0).text('All'));
						$.each(data, function(i, item) {
							$('#city_id').append($("<option></option>").attr("value", i).text(item));
						});
					}, // End of success function of ajax form
					error: function(output_string){				
						alert(jxhr.responseText);
					}
				}); //ajax call end
			}); // $("#country_id").change end
		});
	</script>
@endpush 