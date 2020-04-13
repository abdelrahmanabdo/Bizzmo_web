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
<div class="row bm-pg-header">
		<h2 class="bm-title">Routes</h2>
	</div>
	{{ Form::open(array('id' => 'frmManage', 'class' => '')) }}
	<div>
		
		@if (!isset($mode))
			<b>Start</b>
		<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- country -->  
			{{ Form::label('startcountry_id', 'Country', array('class' => 'bm-label col-md-2')) }}
			@php
				if(Input::old('startcountry_id'))
					$country_id = Input::old('startcountry_id') ? Input::old('startcountry_id') : $company->country_id;
			@endphp
			<div class="col-md-5">
				<select name="startcountry_id" class="form-control bm-select" id="startcountry_id">
				<option>Select Country</option>
				@foreach ($countries as $country)
					@if(isset($route) && $route->startcode->country->isocode == $country->isocode)
						<option value="{{ $country->isocode }}" selected>{{ $country->countryname }} ({{ $country->isocode }})</option>
					@else
						<option value="{{ $country->isocode }}" >{{ $country->countryname }} ({{ $country->isocode }})</option>
					@endif
				@endforeach
				</select>
			</div>
			<div class="col-md-5">
				<select name="startport_id" class="form-control bm-select" id="startport_id">
				<option>Select Port</option>
				@if(isset($startports) && isset($route))
					@foreach ($startports as $port)
						@if($route->start == $port->id)
							<option value="{{ $port->id }}" selected>{{ $port->PortName }}</option>
						@else
						<option value="{{ $port->id }}">{{ $port->PortName }}</option>
						@endif
					@endforeach
				@endif
				</select>
			</div>
			<!-- {{ Form::select('country_id', $countries, Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		 -->
			@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
		</div>
		<b>End</b>
		<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- country -->  
			{{ Form::label('endcountry_id', 'Country', array('class' => 'bm-label col-md-2')) }}
			@php
				if(Input::old('endcountry_id'))
					$country_id = Input::old('endcountry_id') ? Input::old('country_id') : $company->country_id;
			@endphp
			<div class="col-md-5">
				<select name="endcountry_id" class="form-control bm-select" id="endcountry_id">
				<option>Select Country</option>
				@foreach ($countries as $country)
					@if(isset($route) && $route->endcode->country->isocode == $country->isocode)
						<option value="{{ $country->isocode }}" selected>{{ $country->countryname }} ({{ $country->isocode }})</option>
					@else
						<option value="{{ $country->isocode }}" >{{ $country->countryname }} ({{ $country->isocode }})</option>
					@endif
				@endforeach
				</select>
			</div>
			<div class="col-md-5">
				<select name="endport_id" class="form-control bm-select" id="endport_id">
				<option>Select Port</option>
				@if(isset($endports) && isset($route))
					@foreach ($endports as $port)
						@if($route->end == $port->id)
							<option value="{{ $port->id }}" selected>{{ $port->PortName }}</option>
						@else
						<option value="{{ $port->id }}">{{ $port->PortName }}</option>
						@endif
					@endforeach
				@endif
				</select>
			</div>
			<!-- {{ Form::select('country_id', $countries, Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		 -->
			@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
		</div>
		<div class="row">
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				<div class="form-group">
					<div class="radio">
						<label class="checkbox">
							<input class="bm-checkbox" style="display:none" type="checkbox" name="chkActive[]" id="chkActive[]" @if(isset($route) && $route->active) checked @elseif(!isset($route)) checked @endif >
							<span class="checkmark"></span>
							<span class="bm-sublabel">Active</span>
						</label>					
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-default prev-step bm-btn')) }}
		</div>
		@else
			@if (isset($Forwarderroutes))
				{{$Forwarderroutes->start}}
				{{$Forwarderroutes->end}}
			@endif
		@endif
		{{ Form::hidden('company_id', $company_id) }}
		{{ Form::hidden('startport', old('startport_id'), array('id' => 'startport')) }}
		{{ Form::hidden('startcountry', old('startcountry_id'), array('id' => 'startcountry')) }}
		{{ Form::hidden('endport', old('endport_id'), array('id' => 'endport')) }}
		{{ Form::hidden('endcountry', old('endcountry_id'), array('id' => 'endcountry')) }}
		{{ Form::close() }}
	</div>
@stop
@push('scripts')
	<script type="text/javascript">
	$("#submit").click(function(e){
		//e.preventDefault();
		//console.log("Sub");
		if($("#startcountry_id option:selected").index() == 0){
			alert("Select Start Country");
			return false;
		}
		if($("#startport_id option:selected").index() == 0){
			alert("Select Start Port");
			return false;
		}
		if($("#endcountry_id option:selected").index() == 0){
			alert("Select End Country");
			return false;
		}
		if($("#endport_id option:selected").index() == 0){
			alert("Select End Port");
			return false;
		}
		if($('#startport_id').val() == $('#endport_id').val()){
			alert("Start and End Port can't be the same");
			return false;
		}
	});
	$("#startcountry_id").change(function(e){
		e.preventDefault();
		//console.log($("#startcountry_id").val());
		$.ajax({
			type: "GET",
			url: "/forwarder/route/getport?countrycode="+$("#startcountry_id").val(),
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			//data: '{countrycode:"' + $("#startcountry_id").val() + '"}',
			beforeSend: function () {
			},
			success: function (response) {
				// console.log(response);
				var objddStart = document.getElementById("startport_id");
				while (objddStart.length > 0) {
					objddStart.remove(0);
				}
				var optiond = document.createElement('option');
				optiond.value = '0';
				optiond.text = 'Select Port';
				objddStart.add(optiond);
				$.each(response, function () {
					optiond = document.createElement('option');
					optiond.value = this['id'];
					optiond.text = this['PointName'];
					objddStart.add(optiond);
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR.responseText);
				if (jqXHR.status == 401) {
					$(location).attr('href', "/");
					return;
				}
			}
		});
	});

	$("#endcountry_id").change(function(e){
		e.preventDefault();
		//console.log($("#endcountry_id").val());
		$.ajax({
			type: "GET",
			url: "/forwarder/route/getport?countrycode="+$("#endcountry_id").val(),
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			//data: '{countrycode:"' + $("#endcountry_id").val() + '"}',
			beforeSend: function () {
			},
			success: function (response) {
				// console.log(response);
				var objddEnd = document.getElementById("endport_id");
				while (objddEnd.length > 0) {
					objddEnd.remove(0);
				}
				var optiond = document.createElement('option');
				optiond.value = '0';
				optiond.text = 'Select Port';
				objddEnd.add(optiond);
				$.each(response, function () {
					optiond = document.createElement('option');
					optiond.value = this['id'];
					optiond.text = this['PointName'];
					objddEnd.add(optiond);
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR.responseText);
				if (jqXHR.status == 401) {
					$(location).attr('href', "/");
					return;
				}
			}
		});
	});
    </script>
@endpush