@extends('layouts.app')
@section('title')
	@if (isset($title))
		{{ $title }}
	@endif
@stop
@section('content')	
	{{ Form::open(array('id' => 'frmManage', 'class' => 'form-horizontal')) }}
	<div class="row flex-container bm-pg-header">	<!-- row 1 -->
		<h2 class="bm-pg-title">{{ $title}}</h2>
	</div>
		<div class="col-sm-12">
			<div class="form-group"> <!-- company -->  
				{{ Form::label('company_id', 'Company', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
				<p class='form-control-static'>{{ $company->companyname }}</p>
			</div> 
		</div><!-- company -->
		<div class="col-sm-12"> <!-- limit -->
			<div class="form-group"> <!-- limit -->  
				{{ Form::label('limit', 'Credit limit', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
				<div><span class='form-control-static'>{{ $company->creditlimit }}</span></div>
			</div> 
		</div> <!-- limit end -->
		@if (!isset($mode))
		<div class="col-sm-12"> <!-- new limit -->	
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- newlimit -->  
				{{ Form::label('newlimit', 'New Credit Limit', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
				@if (isset($mode))	
					<div><span class='form-control-static'>{{ $company->creditlimit }}</span></div>
				@else
				<div class="col-sm-6">
					{{ Form::text('newlimit', old('newlimit'), array('id' => 'newlimit', 'class' => 'form-control')) }}			
					@if ($errors->has('newlimit')) <p class="bg-danger">{{ $errors->first('newlimit') }}</p> @endif
				</div>
				@endif
			</div> 
		</div>
		@endif
	<div class="row">	<!-- row 3 --> 
		<div class="col-sm-offset-11"> <!-- Column 1 -->
			@if (!isset($mode))
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary hidden')) }}
			<a href="" class="btn btn-info fixedw_button bm-btn green" id="lnksubmit">
				Save
			</a>
			@endif    
			
		</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
	{{ Form::close() }}
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});				
			
			//validation
			$("#frmManage1").validate({
			rules: {
				name: {
				required: true,
				maxlength: 60
				},
				buyup: {
				required: true,
				number: true
				}
			},	
			messages: {
				name: "Length between 1 and 60",
				buyup: "Fees must be numeric"
			}
			});			
		});
	</script>
@endpush