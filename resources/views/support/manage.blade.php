@extends('layouts.app') 
@section('content')
	@if (isset($support)) 
		{{ Form::model($support, array('id' => 'frmManage')) }}
	@else
		{{ Form::open(array('id' => 'frmManage')) }}
	@endif
	<div class="row bottom-space">
		<h2 class="bm-pg-title d-inline-block">{{ $support->title }}</h2>
		@if($support->isOpen())
		<h4 class="red d-inline-block va-super">(Open)</h4>
		@else
		<h4 class="green d-inline-block va-super">(Closed)</h4>
		@endif
	</div>
	<div class="row">	<!-- row 1 -->		
		<div class="col-sm-6">  <!-- column 1 -->
			<div class="form-group"> <!-- User name -->  
				{{ Form::label('name', 'Name', ['class' => 'label-view']) }}
				<p class='form-control-static'>{{ $support->issuer_name }}</p>				
			</div> <!-- User name -->  
		</div>					<!-- end col 1 -->	
		<div class="col-sm-6">  <!-- column 2 -->
			<div class="form-group"> <!-- email -->  
				{{ Form::label('email', 'Email', ['class' => 'label-view']) }}
				<p class='form-control-static'>{{ $support->issuer_email }}</p>
			</div> <!-- email end -->  
		</div>					<!-- end col 2 -->	
	</div>				<!-- end row 1 -->		
	@if($support->company)
	<div class="row">	<!-- row 2 -->		
		<div class="col-sm-6">  <!-- column 2 -->
			<div class="form-group"> <!-- company -->  
				{{ Form::label('company', 'company', ['class' => 'label-view']) }}
				<p class='form-control-static'>{{ $support->company }}</p>
			</div> <!-- company end -->  
		</div>				<!-- end col 2 -->
	</div>				<!-- end row 2 -->	
	@endif
	<div class="row">	<!-- row 3 --> 
		<div class="col-sm-12">  <!-- column 1 -->
			<div class="form-group"> <!-- status -->  
				{{ Form::label('message', 'Message', ['class' => 'label-view']) }}
				<p class='form-control-static'>{{ $support->message }}</p>
			</div> <!-- status end -->  
		</div>					<!-- end col 1 -->	
	</div>
	@if($support->comp_acc_info || $support->supp_acc_info)
	<div class="row">	<!-- row 2 -->		
		<div class="col-sm-6">  <!-- column 2 -->
			<div class="form-group"> <!-- company -->  
				{{ Form::label('comp_acc_info', 'Company Account Info', ['class' => 'label-view']) }}
				<p class='form-control-static'>{{ $support->comp_acc_info }}</p>
			</div> <!-- company end -->  
		</div>
		<div class="col-sm-6">  <!-- column 2 -->
			<div class="form-group"> <!-- company -->  
				{{ Form::label('supp_acc_info', 'Supplier Account Info', ['class' => 'label-view']) }}
				<p class='form-control-static'>{{ $support->supp_acc_info }}</p>
			</div> <!-- company end -->  
		</div>
	</div>				<!-- end row 2 -->	
	@endif
	@if(($support->resolution && $mode == 'v') || !isset($mode) || $mode != 'v')
	<div class="row">	<!-- row 4 --> 
		<div class="col-sm-12">  <!-- Column 1 -->
			<div class="form-group"> <!-- Role Name -->  
				{{ Form::label('resolution', 'Resolution', ['class' => 'label-view']) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $support->resolution }}</p>
				@else					
					{{ Form::text('resolution', Input::old('resolution'), array('id' => 'resolution', 'class' => 'form-control')) }}			
					@if ($errors->has('resolution')) <p class="bg-danger">{{ $errors->first('resolution') }}</p> @endif					
				@endif
			</div> <!-- Role name -->  
		</div>					<!-- end col 1 -->
	</div>
	@endif
	<div class="row">	<!-- row 5 --> 
		<div class=" col-md-12"> <!-- column 1 -->
		@if (isset($mode))
			@if (Gate::allows('su_vw') || Gate::allows('su_ch'))
				<div class="col-xs-6 col-sm-3"> <!-- column 2 -->
					<a href='{{ url("/supports") }}' class="btn bm-btn fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
				</div>
			@endif
			@if (Gate::allows('su_ch') && $support->isOpen())
				<div class="col-xs-6 col-sm-3"> <!-- column 3 -->
					<a href='{{ url("/supports/" . $support->id) }}' class="btn bm-btn sun-flower fixedw_button" role="button" title="Edit">
						<span class="edit-icon-white"></span>
					</a>
				</div>
			@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn bm-btn green fixedw_button" id="lnksubmit">
				<span class="glyphicon glyphicon-ok"></span>
			</a>
		@endif 	
		</div> <!-- column 1 end -->
	</div> <!--row 5 end -->
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
			$("#frmManage").validate({
			rules: {
				resolution: {
				required: true,
				maxlength: 190
				}
			},	
			messages: {
				resolution: "Resolution is reuired and must not exceed 190 characters in length"
			}
			});
			//validation end					
	});	
	</script>
@endpush

