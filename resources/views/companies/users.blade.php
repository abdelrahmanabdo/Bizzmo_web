@extends('layouts.master') 
@section('content') 
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});
			//iCheck
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			 });
			//iCheck end
			//validation
			$("#frmManage").validate({
			rules: {				
				companyname: {
				required: true,
				maxlength: 60
				}
			},	
			messages: {
				companyname: "Length between 1 and 60"
			}
			});			


		});
	</script>
	@if (isset($company)) 
		{!! Form::model($company, array('id' => 'frmManage')) !!}
		{!! Form::hidden('id', $company->id, array('id' => 'id', 'class' => 'form-control')) !!}
	@else
		{!! Form::open(array('id' => 'frmManage')) !!}
	@endif

	<div class="row-fluid">	<!-- row 1 -->
		<div class="col-md-6 col-lg-6">  <!-- Column 1 -->
			<div class="form-group"> <!-- Role Name -->  
				{!! Form::label('name', 'Name') !!}
				<p class='form-control-static'>{!! $company->companyname !!}</p>
			</div> <!-- Role name -->  
		</div>					<!-- end col 1 -->					
		<div class="col-md-2 col-lg-2">  <!-- Column 2 -->
			<div class="form-group"> <!-- Active -->  
				{!! Form::label('active', 'Active') !!}
					@if ($company->active)
						<p class='form-control-static'>Yes</p>
					@else
						<p class='form-control-static'>No</p>
					@endif				
			</div> <!-- Active -->
		</div>					<!-- end col 2 -->
	</div>				<!-- end row 1 -->
	<div class="row-fluid">	<!-- row 2 -->
		<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
		<table id="mytable" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>User</th>
					<th>Assign</th>
				</tr>		
			</thead>
			<tbody>					
				@foreach ($users as $user)
					<tr>				
						<td>{{ $user->name }} </td>
						<td>
						<?php $ismember = 0; ?>
						@foreach ( $company->users as $companyuser )
							@if ( $user->id == $companyuser->id )
								<?php $ismember = 1; ?>										
							@endif
						@endforeach
						@if ( $ismember == 0 ) 
							{!! Form::checkbox('cbuser[]', $user->id) !!}
						@else
							{!! Form::checkbox('cbuser[]', $user->id, true) !!}
						@endif
						</td>
					</tr>	
				@endforeach					
			</tbody>
		</table>
		</div> <!-- Column 1 end -->
	</div> <!-- row 2 end -->	
	<div class="row-fluid">	<!-- row 3 --> 
	<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
		@if (isset($mode))
			<div class="col-md-4 col-lg-4"> <!-- Column 1 -->
				<a href="{!! url("/companys/create") !!}" class="btn btn-primary fixedw_button" company="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>						
			</div> <!-- Column 1 end -->
			<div class="col-md-4 col-lg-4"> <!-- Column 2 -->
				<a href="{!! url("/companys") !!}" class="btn btn-primary fixedw_button" company="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
			</div>
			<div class="col-md-4 col-lg-4"> <!-- Column 3 -->
				<a href="{!! url("/companys/" . $company->id) !!}" class="btn btn-primary fixedw_button" company="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
			</div>
		@else
			{!! Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button')) !!}
			<a href="" class="btn btn-primary fixedw_button" id="lnksubmit">
				<span class="glyphicon glyphicon-ok"></span>
			</a>
		@endif    
		
	</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
	{!! Form::close() !!}
@stop
 

