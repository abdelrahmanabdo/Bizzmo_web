@extends('layouts.app')
@section('title')
	@if (isset($title))
		{{ $title }}		
	@endif
@stop 
@section('content')
	{{ Form::open(array('id' => 'frmManage')) }}
		@if(isset($title))	
			<div class="row">
				<h2 class="bm-pg-title">{{ $title }}</h2>
				<br/>
			</div>
		@endif
		<div class="row-fluid">	<!-- row 1 -->		
			<div class="col-md-3">  <!-- column 1 -->
				<div class="form-group"> <!-- from date -->  
					{{ Form::label('fromdate', 'From') }}
					{{ Form::text('fromdate', old('fromdate', $fromdate), array('id' => 'fromdate', 'class' => 'form-control')) }}			
					@if ($errors->has('fromdate')) <p class="bg-danger">{{ $errors->first('fromdate') }}</p> @endif
				</div> <!-- from date end -->  
			</div>					<!-- column 1 end -->
			<div class="col-md-3">  <!-- column 2 -->
				<div class="form-group"> <!-- to date -->  
					{{ Form::label('todate', 'To') }}
					{{ Form::text('todate', old('todate', $todate), array('id' => 'todate', 'class' => 'form-control')) }}			
					@if ($errors->has('todate')) <p class="bg-danger">{{ $errors->first('todate') }}</p> @endif
				</div> <!-- to date end -->  
			</div>					<!-- column 2 end -->		
		</div>				<!-- end row 1 -->
		<div class="row-fluid">	<!-- row 2 --> 
			<div class="col-md-12"> <!-- Column 1 -->
				@if (isset($mode))
				@else
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
					<a href="" class="btn btn-info fixedw_button bm-btn search-btn" id="lnksubmit">
						<span class="glyphicon glyphicon-search" title="Search"><span class="text visible-xs visible-sm">Search</span></span>
					</a>
				@endif    			
			</div> <!-- Column 1 end -->
		</div> <!--row 2 end -->
		<div class="row-fluid">	<!-- row 3 -->
			<div class="col-sm-12"> <!-- column 1 -->
				<table id="listtable" class="table table-striped table-bordered table-hover table-tight dataTable">
				<thead>
					<tr>
						<th>User</th>
						<th>Email</th>
						<th>IP Address</th>
						<th>User Agent</th>
						<th>Time</th>
					</tr>
				</thead>
				<tbody>			
					@foreach ($logs as $log)
					<tr>
						<td> {{ $log->user()["name"] }} </td>
						<td> {{ $log->email }} </td>
						<td> {{ $log->ip_address }} </td>
						<td> {{ $log->user_agent }} </td>
						<td> {{ $log->created_at }} </td>
					</tr>	
					@endforeach			
				</tbody>
				</table>
			</div>
		</div> <!--row 3 end -->	
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
			$( "#fromdate" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
			$( "#todate" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
				
		});
	</script>
@endpush 
