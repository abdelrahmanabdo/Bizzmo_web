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
		<h2 class="bm-pg-title">Shipping Inquiries</h2>
		<!-- <div class="col-md-4">
			<a href="/forwarder/route/create" id="lnksubmit" class="btn btn-info bm-btn"><span title="New Address">Create A New Route</span></a>
		</div> -->
	</div>
	<div class="row">
		<table id="listtable" class="table table-striped table-bordered table-hover table-condensed">
		<thead>
			<tr>
				<th>Company Name</th>
				<th>Size</th>
				<th>Volume</th>
				<th>Boxes</th>
			</tr>		
		</thead>
		<tbody>
		@foreach ($shippinginquiries as $shippinginquiry)
			<tr>
				<td>
				{{$shippinginquiry->company->companyname}}
				</td>
				<td>
				{{$shippinginquiry->size}}
				</td>
				<td>
				{{$shippinginquiry->volume}}
				</td>
				<td>
				{{$shippinginquiry->boxes}}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

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