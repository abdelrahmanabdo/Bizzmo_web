@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{!! Form::open(array('id' => 'frmManage')) !!}
	@if (!$unconfirmed)
		<div class="row-fluid">	<!-- row 1 -->		
			<div class="col-md-4 col-lg-4">  <!-- Column 1 -->
				<div class="form-group"> <!-- Company name -->  
					{!! Form::label('companyname', 'Vendor name') !!}
					{!! Form::text('companyname', Input::get('companyname'), array('id' => 'companyname', 'class' => 'form-control')) !!}			
					{!! Form::hidden('id', Input::old('id'), array('id' => 'id')) !!}
					@if ($errors->has('companyname')) <p class="bg-danger">{!! $errors->first('companyname') !!}</p> @endif
				</div> <!-- Company name -->  
			</div>					<!-- end col 1 -->
			<div class="col-md-4 col-lg-4">  <!-- Column 2 -->
				<div class="form-group"> <!-- country -->  
					{!! Form::label('country_id', 'Country') !!}
					{!! Form::select('country_id', $countries, Input::get('country_id'),array('id' => 'country_id', 'class' => 'form-control'))!!}		
					@if ($errors->has('country_id')) <p class="bg-danger">{!! $errors->first('country_id') !!}</p> @endif
				</div> <!-- country end -->  
			</div>					<!-- end col 2 -->
			<div class="col-md-4 col-lg-4">  <!-- Column 3 -->
				<div class="form-group"> <!-- currency -->  
					{!! Form::label('city_id', 'City') !!}
					{!! Form::select('city_id', $cities, Input::get('city_id'),array('id' => 'city_id', 'class' => 'form-control'))!!}
					@if ($errors->has('city_id')) <p class="bg-danger">{!! $errors->first('city_id') !!}</p> @endif
				</div> <!-- currency end -->  
			</div>				<!-- end col 3 -->
		</div>				<!-- end row 1 -->
		<div class="row-fluid">	<!-- row 2 -->
			<div class="col-md-4 col-lg-4">  <!-- Column 1 -->
				<div class="form-group"> <!-- Active -->  
					{!! Form::label('active', 'Active only') !!}
						<div class="checkbox"> <!-- Active --> 
							<label>
								{!! Form::checkbox('active', Input::old('active'), Input::get('active'), array('id' => 'active')) !!}			
							<label>
						</div>
					@if ($errors->has('active')) <p class="bg-danger">{!! $errors->first('active') !!}</p> @endif
				</div> <!-- Active -->  
			</div>	<!-- end col 1 -->
			
		</div>				<!-- end row 2 -->
		<div class="row-fluid">	<!-- row 3 --> 
		<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
			@if (isset($mode))
			@else
				{!! Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) !!}
				<a href="" class="btn btn-info fixedw_button" id="lnksubmit">
					<span class="glyphicon glyphicon-search" title="Search"></span>
				</a>
			@endif    
			
		</div> <!-- Column 1 end -->
		</div> <!--row 3 end -->
	@endif
	{!! Form::close() !!}
	<div class="row-fluid">	<!-- row 4 -->
		<div class="col-md-12 col-lg-12"> 	<!-- Column 1 -->
			&nbsp;
		</div> 				<!-- Column 1 end -->

	</div> 			<!-- row 4 end -->
	@if (isset($vendors))
		<div class="row-fluid">	<!-- row 5 -->
		<table id="mytable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="no-sort" width="10%">
					@if (Gate::allows('vn_cr') && !$unconfirmed)
						<a href="{!! url("/vendors/create") !!}" role="button"><span class="glyphicon glyphicon-plus" title="Add"></span></a>	
					@endif
				</th>
				<th>Name</th>
				<th>Email</th>
				<th>City</th>
				<th>Country</th>
				<th>Active</th>
			</tr>		
		</thead>
		<tbody>			
			  @foreach ($vendors as $vendor)
				<tr>
					<td>
						@if ($unconfirmed  || (Gate::allows('vn_cr') && $vendor->confirmed == 0 ))
							@if (Gate::allows('vn_cr'))
								<a href="{!! url("/vendors/view/" . $vendor->id) !!}" role="button"><span class="glyphicon glyphicon-ok" title="Confirm"></span></a>	
								&nbsp;
							@endif
						@else
							@if (Gate::allows('vn_ch', $vendor->id))
								<a href="{!! url("/vendors/" . $vendor->id) !!}" role="button"><span style="color:orange" class="glyphicon glyphicon-pencil" title="Edit"></span></a>	
								&nbsp;
							@endif
							@if (Gate::allows('vn_vw', $vendor->id))
								<a href="{!! url("/vendors/view/" . $vendor->id) !!}" role="button"><span class="glyphicon glyphicon-eye-open" title="View"></span></a>
							@endif
						@endif
					</td>
					<td> {!! $vendor->companyname !!} </td>
					<td> {!! $vendor->email !!} </td>
					<td> {!! $vendor->city->cityname !!} </td>
					<td> {!! $vendor->country->countryname !!} </td>
					<td> @if ($vendor->active == 1) Yes @else No @endif </td>
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