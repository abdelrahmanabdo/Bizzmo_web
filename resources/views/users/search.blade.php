@extends('layouts.app', ['hideRightMenuAndExtend' => true]) 
@section('content')
	@if (!isset($hideconditions))
		{{ Form::open(array('id' => 'frmManage')) }}
			<div class="col-md-12">	<!-- row 1 -->
				@if(isset($title))	
				<h2 class="bm-pg-title">{{ $title }}</h2>
				<br/>
				@endif
				<div class="col-sm-6">  <!-- column 1 -->
					<div class="form-group"> <!-- User name -->  
						{{ Form::label('name', 'Name') }}
						{{ Form::text('name', Input::get('name'), array('id' => 'name', 'class' => 'form-control')) }}			
						{{ Form::hidden('id', Input::old('id'), array('id' => 'id')) }}
						@if ($errors->has('name')) <p class="bg-danger">{{ $errors->first('name') }}</p> @endif
					</div> <!-- User name -->  
				</div>					<!-- end col 1 -->			
				<div class="col-sm-6">  <!-- column 2 -->
					<div class="form-group"> <!-- User name -->  
						{{ Form::label('email', 'Email') }}
						{{ Form::text('email', Input::get('email'), array('id' => 'email', 'class' => 'form-control')) }}			
						@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
					</div> <!-- User name -->  
				</div>					<!-- end col 2 -->
			</div>				<!-- end row 1 -->
			<div class="row" style="margin-bottom: 10px;">	<!-- row 2 --> 
				<div class="col-sm-12">
					<div class="form-horizontal">
						<div class="radio"> <!-- Active -->  
							<label class="checkbox">
								<input id="active" class="bm-checkbox" name="active" type="checkbox">			
								<span class="checkmark"></span>
								<span class="bm-sublabel">Active only</span> 
							</label>
						</div>
					</div>  <!-- column 1 -->
					@if ($errors->has('active')) <p class="bg-danger">{{ $errors->first('active') }}</p> @endif
				</div>	<!-- end col 1 -->	
			</div>				<!-- end row 2 -->
			<div class="">	<!-- row 3 --> 
				<div class="col-sm-12"> <!-- column 1 -->
					@if (isset($mode))
					@else
						{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
						<a href="" class="btn bm-btn" id="lnksubmit">
							Search
						</a>
					@endif    		
				</div> <!-- column 1 end -->
			</div> <!--row 3 end -->
		{{ Form::close() }}
	@endif
	@if (isset($users))
		<div class="">	<!-- row 4 -->
			<div class="col-sm-12"> <!-- column 1 -->
				@include('users.users_table', ['users' => $users])
			</div>
		</div>				<!-- end row 4 -->
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
	});
	</script>
@endpush 

