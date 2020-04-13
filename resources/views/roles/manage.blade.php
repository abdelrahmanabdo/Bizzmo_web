@extends('layouts.app') 
@section('content') 	
	@if (isset($role)) 
		{!! Form::model($role, array('id' => 'frmManage')) !!}
		{!! Form::hidden('id', $role->id, array('id' => 'id', 'class' => 'form-control')) !!}
	@else
		{!! Form::open(array('id' => 'frmManage')) !!}
		{!! Form::hidden('client_id', Auth::user()->client_id, array('id' => 'client_id')) !!}
	@endif

	<div class="row-fluid">	<!-- row 1 -->
		<div class="col-md-6">  <!-- Column 1 -->
			<div class="form-group"> <!-- Role Name -->  
				{!! Form::label('name', 'Role') !!}
				@if (isset($mode))	
					<p class='form-control-static'>{!! $role->rolename !!}</p>
				@else					
					{!! Form::text('rolename', Input::old('rolename'), array('id' => 'rolename', 'class' => 'form-control')) !!}			
					@if ($errors->has('rolename')) <p class="bg-danger">{!! $errors->first('rolename') !!}</p> @endif					
				@endif
			</div> <!-- Role name -->  
		</div>					<!-- end col 1 -->
		<div class="col-md-6">  <!-- Column 2 -->
			<div class="form-group"> <!-- company -->  
				{!! Form::label('company_id', 'Company') !!}
				@if (Auth::user()->isSysadmin)
					<p class='form-control-static'>{{ config('app.companyname') }}</p>
					{!! Form::hidden('company_id', 0, array('id' => 'company_id')) !!}
				@else
					@if (isset($mode))	
						<p class='form-control-static'>{!! $role->company->companyname !!}</p>
					@else					
						{!! Form::select('company_id', $companies, Input::old('company_id'),array('id' => 'company_id', 'class' => 'form-control'))!!}
						@if ($errors->has('company_id')) <p class="bg-danger">{!! $errors->first('company_id') !!}</p> @endif					
					@endif
				@endif
			</div> <!-- company end -->  
		</div>					<!-- end col 2 -->		
	</div>				<!-- end row 1 -->
	<div class="row-fluid">	<!-- row 2 -->
		<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
		<table id="mytable" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Permissions</th>
					@if (isset($mode))	
						<th>Users</th>
					@else
						<th>Assign</th>
					@endif
				</tr>		
			</thead>
			<tbody>
				@if (isset($role)) 
					@if (isset($mode))						
							<tr>
								<td>
									@foreach ( $rolepermissions as $rolepermission )
										{!! $rolepermission->display_name !!}<br>
									@endforeach
								</td>
								<td>
									<table id="myUsers" class="table table-striped table-bordered table-hover table-condensed">
										@foreach ( $role->users as $user )
											<tbody>
												<td>{!! $user->name !!}</td>
											</tbody>
										@endforeach
									</table>
								</td>
							</tr>						
					@else
						@foreach ($permissions as $permission)
							<tr>				
								<td>{{ $permission->display_name }} </td>
								<td>
								<?php $ismember = 0; ?>
								@foreach ( $rolepermissions as $rolepermission )
									@if ( $rolepermission->id == $permission->id )
										<?php $ismember = 1; ?>										
									@endif
								@endforeach
								@if ( $ismember == 0 ) 
									{!! Form::checkbox('cbpermission[]', $permission->id) !!}
								@else
									{!! Form::checkbox('cbpermission[]', $permission->id, true) !!}
								@endif
								</td>
							</tr>	
						@endforeach			
					@endif
				@else		  
				  @foreach ($permissions as $permission)
					<tr>				
						<td> {{ $permission->display_name }} </td>
						<td>						
						{!! Form::checkbox('cbpermission[]', $permission->id) !!}
						</td>
					</tr>	
				  @endforeach			
				@endif
			</tbody>
		</table>
		</div> <!-- Column 1 end -->
	</div> <!-- row 2 end -->	
	<div class="row-fluid">	<!-- row 3 --> 
	<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
		@if (isset($mode))
			@if ($mode == 'd')
				<div class="col-md-3 col-lg-3"> <!-- Column 1 -->
					<a href="{!! url("/roles/delete/" . $role->id) !!}" class="btn btn-primary btn-danger fixedw_button" role="button" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>						
				</div> <!-- Column 1 end -->
			@else
				<div class="col-md-3 col-lg-3"> <!-- Column 1 -->
					<a href="{!! url("/roles/create") !!}" class="btn btn-primary fixedw_button" role="button" title="New"><span class="glyphicon glyphicon-plus"></span></a>						
				</div> <!-- Column 1 end -->
				<div class="col-md-3 col-lg-3"> <!-- Column 2 -->
					<a href="{!! url("/roles") !!}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
				</div>
				<div class="col-md-3 col-lg-3"> <!-- Column 3 -->
					<a href="{!! url("/roles/" . $role->id) !!}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
				</div>				
			@endif
		@else
			{!! Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) !!}
			<a href="" class="btn btn-primary fixedw_button" id="lnksubmit">
				<span class="glyphicon glyphicon-ok"></span>
			</a>
		@endif    
		
	</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
	{!! Form::close() !!}
@stop
@push('scripts')
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
				rolename: {
				required: true,
				maxlength: 60
				}
			},	
			messages: {
				rolename: "Length between 1 and 60"
			}
			});			


		});
	</script>
@endpush 

