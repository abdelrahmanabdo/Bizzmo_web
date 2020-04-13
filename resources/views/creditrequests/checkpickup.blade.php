@extends('layouts.app')
@section('title')

@stop
@section('styles')
	<style>

	</style>
@stop
@section('content')	
	{{ Form::open(array('id' => 'frmManage')) }}
	<div style="margin: 60px;">	
	<div class="row flex-container bm-pg-header">
		<h2 class="bm-pg-title">Check Pickup</h2>
	</div>
	<div class="row">	<!-- row 1 -->
		<div class="col-md-12">  <!-- col 1 -->
			<p class="bm-label">We will pickup the check from you on {{ date_format(date_create($security->pickupbydate), 'j/n/Y') }} {{ $security->pickupbytime->name }}</p>
		</div>		
	</div>
	@if (!isset($mode))
		<div class="row">	<!-- row 2 -->
			<div class="col-md-12">  <!-- col 1 -->
				<p class="bm-label">If you would like to change, choose other date and time and click save. </p>
			</div>		
		</div>	
		<div class="row">	<!-- row 3 -->
			<div class="col-sm-6"> <!-- date -->	
				<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- date -->  
					{{ Form::label('pickupbydate', 'Pickup date', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
					<div class="col-sm-6">
						{{ Form::text('pickupbydate', old('pickupbydate', date_format(date_create($security->pickupbydate), 'j/n/Y')), array('id' => 'pickupbydate', 'class' => 'form-control')) }}			
						@if ($errors->has('pickupbydate')) <p class="bg-danger">{{ $errors->first('pickupbydate') }}</p> @endif
					</div>
				</div> 
			</div> <!-- date end -->
			<div class="col-sm-6"> <!-- date -->	
				<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- date -->  
					{{ Form::label('pickupbytime_id', 'Pickup time', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
					<div class="col-sm-6">
						{{ Form::select('pickupbytime_id', $timelist, Input::old('pickupbytime_id', $security->pickupbytime_id),array('id' => 'pickupbytime_id', 'class' => 'form-control bm-select'))}}		
						@if ($errors->has('pickupbytime_id')) <p class="bg-danger">{{ $errors->first('pickupbytime_id') }}</p> @endif
					</div>
				</div> 
			</div> <!-- date end -->
		</div>
		<div class="row">	<!-- row 4 -->
			<div class="col-sm-offset-11"> <!-- Column 1 -->
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn bm-btn blue fixedw_button hidden')) }}						
				<a href="" class="btn btn-info fixedw_button bm-btn green" id="lnksubmit" type="button" title="Save">
					Save
				</a>
			</div>
		</div>
	@endif
	</div>
	{{ Form::close() }}
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});
			
			$( "#pickupbydate" ).datepicker({ 
				format: "d/m/yyyy",
				startDate: "1d",
				autoclose: true,
			});
		});
	</script>
@endpush