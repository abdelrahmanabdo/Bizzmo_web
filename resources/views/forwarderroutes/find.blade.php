@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
@stop
@section('content')	
{{ Form::open(array('id' => 'frmManage', 'class' => 'shipping-add-form')) }}
<div class="row bm-pg-header">
		<h2 class="bm-pg-title">Find Route</h2>
		<!-- <div class="col-md-4">
			<a href="/forwarder/route/create" id="lnksubmit" class="btn btn-info bm-btn"><span title="New Address">Create A New Route</span></a>
		</div> -->
	</div>
	<div class="row">
		<table id="listtable" class="table table-striped table-bordered table-hover table-condensed">
		<thead>
			<tr>
				<th>Start Country</th>
				<th>Start Port</th>
				<th>End Country</th>
				<th>End Port</th>
			</tr>		
		</thead>
		<tbody>
			<tr>
				<td>
					<select name="startcountry_id" class="form-control bm-select" id="startcountry_id">
						<option>Select Country</option>
						@foreach ($countries as $country)
							<option {{ old('startcountry_id', $country->isocode) == (isset($_POST['startcountry_id'])?$_POST['startcountry_id']:'') ? 'selected' : '' }} value="{{ $country->isocode }}" >{{ $country->countryname }} ({{ $country->isocode }})</option>
						@endforeach
					</select>
				</td>
				<td>
					<select name="startport_id" class="form-control bm-select" id="startport_id">
						<option>Select Port</option>
						@if(isset($startports))
							@foreach ($startports as $port)
							<option {{ old('startport_id', $port->id) == (isset($_POST['startport_id'])?$_POST['startport_id']:'') ? 'selected' : '' }} value="{{ $port->id }}" >{{ $port->PointName }} ({{ $port->id }})</option>
							@endforeach
						@endif
					</select>
				</td>
				<td>
					<select name="endcountry_id" class="form-control bm-select" id="endcountry_id">
						<option>Select Country</option>
						@foreach ($countries as $country)
							<option {{ old('endcountry_id', $country->isocode) == (isset($_POST['endcountry_id'])?$_POST['endcountry_id']:'') ? 'selected' : '' }} value="{{ $country->isocode }}" >{{ $country->countryname }} ({{ $country->isocode }})</option>
						@endforeach
					</select>
				</td>
				<td>
					<select name="endport_id" class="form-control bm-select" id="endport_id">
						<option>Select Port</option>
						@if(isset($endports))
						@foreach ($endports as $port)
						<option {{ old('endport_id', $port->id) == (isset($_POST['endport_id'])?$_POST['endport_id']:'') ? 'selected' : '' }} value="{{ $port->id }}" >{{ $port->PointName }} ({{ $port->id }})</option>
						@endforeach
						@endif
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
	<div class="row">
			{{ Form::submit('Find', array('id' => 'submit', 'class' =>'btn btn-default prev-step bm-btn')) }}
		
	</div>
	
	{{ Form::close() }}
	{{ Form::open(array('action' => "forwarderrouteconroller@shipInq",'id' => 'frmManage', 'class' => 'shipping-add-form')) }}	
	<input name="poid" id="poid" type="hidden" value="{{ $poid }}">
	
	<table id="tblResult" class="table table-striped table-bordered table-hover table-condensed">
	<thead>
	<tr>
	<th>
	Company Name
	</th>
	<th>
		Select
	</th>
	</tr>
	</thead>
	<tbody>
	@if(isset($routes))
	@foreach ($routes as $route)
		<tr>
<td>
{{$route->company->companyname}}
</td>
<td>
<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- companytype -->
					<div class="radio">
						<label class="checkbox">
							<input class="bm-checkbox" type="checkbox" name="companyId[]" id="companyId[]" value="{{$route->company->id}}" >
							<span class="checkmark"></span>
						</label>					
					</div>
				</div>
</td>
		</tr>
	@endforeach
	@endif
	</tbody>
	</table>
	<table class="table table-striped table-bordered table-hover table-condensed">
	<thead>
	<tr>
<th>
Total Size
</th>
<th>
Total Volume
</th>
<th>
Number Of Boxes
</th>
	</tr>
</thead>
<tbody>
<tr>
<td>
<input type="text" id="size" name="size" class="form-control">
</td>
<td>
<input type="text" id="volume" name="volume" class="form-control">
</td>
<td>
<input type="text" id="boxes" name="boxes" class="form-control">
</td>
</tr>
</tbody>
	</table>
	<div class="row">

	{{ Form::submit('Send Shipment Inquiry', array('id' => 'submit', 'class' =>'btn btn-default prev-step bm-btn')) }}
		</div>
	</div>
	{{ Form::close() }}
@stop
@push('scripts')
<script type="text/javascript">
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