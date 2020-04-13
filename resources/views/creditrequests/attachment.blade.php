@extends('layouts.app') 
@section('content')
	@if (isset($company)) 
		{{ Form::model($creditrequest, array('id' => 'frmManage', 'files' => true)) }}
		{{ Form::hidden('id', $company->id, array('id' => 'id', 'class' => 'form-control')) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'files' => true)) }}
	@endif

	<div class="row">	<!-- row 1 -->		
		<div class="col-md-4">  <!-- Column 1 -->
			<div class="form-group"> <!-- Company name -->				
				{{ Form::label('companyname', 'Company name') }}
				<p class='form-control-static'>{{ $creditrequest->company->companyname }}</p>				
			</div> <!-- Company name -->  
		</div>					<!-- end col 1 -->
		<div class="col-md-4">  <!-- Column 2 -->
			<div class="form-group"> <!-- address -->  
				{{ Form::label('address', 'Address') }}
				<p class='form-control-static'>{{ $creditrequest->company->address }}</p>				
			</div> <!-- address end -->  
		</div>					<!-- end col 2 -->					
		<div class="col-md-4">  <!-- Column 2 -->
			<div class="form-group"> <!-- address -->  
				{{ Form::label('country', 'Country') }}
				<p class='form-control-static'>{{ $creditrequest->company->country->countryname }}</p>				
			</div> <!-- address end -->  
		</div>					<!-- end col 2 -->	
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 3 -->
		<div class="col-sm-6">  <!-- Column 2 -->
			<a href="{{ url("/creditrequests/view/" . $creditrequest->id) }}" class="btn btn-info fixedw_button" role="button" title="View credit request">
				<span class="glyphicon glyphicon-eye-open"></span>
			</a>
		</div>					<!-- end col 2 -->		
	</div>				<!-- end row 3 -->
	<div class="row">	<!-- row 4 -->		
		@if ($title == "View attachment")
			<h4>{{ $attachment->description }}<h4>
			<img src="/{{ $attachment->path}}">
		@else
			@if ($title == "View bank attachments")
				<img src="/{{ $creditrequest->attbank}}">
			@else
				<img src="/{{ $creditrequest->attfinstat}}">
			@endif
		@endif
	</div>				<!-- end row 4 -->
	<div class="row">	<!-- row 9 --> 
		@if (isset($mode))
			 <label class="custom-file">
				<input type="file" name="attachment" class="custom-file-input">
				<span class="custom-file-control"></span>
			</label>
		@endif
	</div> <!--row 9 end -->
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
		});
	</script>
@endpush

