@extends('layouts.app') 
@section('content')
	{!! Form::open(array('id' => 'frmManage')) !!}
	<div class="row">	<!-- row 1 -->
		<div class="col-sm-6">  <!-- column 1 -->
			<div class="form-group"> <!-- role -->  
				{!! Form::label('company_id', 'Company') !!}
				{!! Form::select('company_id', $companies, Input::get('company_id'),array('id' => 'company_id', 'class' => 'form-control'))!!}
				@if ($errors->has('company_id')) <p class="bg-danger">{!! $errors->first('company_id') !!}</p> @endif
			</div> <!-- role end -->  
		</div>				<!-- end col 1 -->
		<div class="col-sm-6">  <!-- column 2 -->
			<div class="form-group"> <!-- Role name -->  
				{!! Form::label('rolename', 'Name') !!}
				{!! Form::text('rolename', Input::get('rolename'), array('id' => 'rolename', 'class' => 'form-control')) !!}			
				{!! Form::hidden('id', Input::old('id'), array('id' => 'id')) !!}
				@if ($errors->has('rolename')) <p class="bg-danger">{!! $errors->first('rolename') !!}</p> @endif
			</div> <!-- Role name -->  
		</div>				<!-- end col 2 -->
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 2 -->		
		<div class="col-md-4">  <!-- Column 2 -->
			<div class="form-group">  <!-- active -->
				{!! Form::label('showdetails', 'Show permissions') !!}
				<div class="checkbox">							
					@if ($showdetails == 1)
						<label>
							<input type="checkbox" name="showdetails" id="showdetails" checked>
						</label>
					@else
						<label>
							<input type="checkbox" name="showdetails" id="showdetails">
						</label>
					@endif		
				</div>
			</div>  <!-- active end -->
		</div>					<!-- end col 2 -->
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 3 -->
		{!! Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) !!}
		<a href="" class="btn btn-info fixedw_button" id="lnksubmit">
			<span class="glyphicon glyphicon-search"></span>
		</a>
	</div>				<!-- end row 3 -->
	{!! Form::close() !!}
	@if (isset($roles))
	<div class="row">	<!-- row 4 -->
		<div class="col-sm-12">  <!-- column 1 -->
			<table id="mytable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="no-sort" width="10%">
							@if (Gate::allows('ro_cr'))
								<a href="{{ url("/roles/create") }}" type="button"><span class="glyphicon glyphicon-plus" title="جديد"></span></a>
							@else
								&nbsp
							@endif
						</th>
						<th>Role</th>
						<th>Building</th>
						@if ($showdetails)
							<th>Permissions</th>
						@endif
						<th>Users</th>
					</tr>		
				</thead>
				<tbody>
					  @foreach ($roles as $role)
						<tr>
							<td>
								@if (Gate::allows('ro_vw', $role->id))
									<a href="{{ url("/roles/view/" . $role->id) }}"  role="button"><span class="glyphicon glyphicon-eye-open blue" title="رؤية"></span></a>	
									&nbsp;
								@endif
								@if (Gate::allows('ro_ch', $role->id))
									<a href="{{ url("/roles/" . $role->id) }}"  role="button"><span style="color:orange" class="glyphicon glyphicon-pencil orange" title="تعديل"></span></a>	
									&nbsp;
								@endif
								@if (Gate::allows('ro_dl', $role->id))
									<a href="/roles/deletec/{{ $role->id }}" role="button"><span class="glyphicon glyphicon-trash orange" title="حذف"></span></a>
								@endif
							</td>
							<td> {!! $role->rolename !!} </td>
							<td> {!! $role->company_id == 0 ? config('app.companyname') : $role->company->companyname !!} </td>
							@if ($showdetails)
								<td> 
									@foreach ( $role->permissions as $permission )
										{{ $permission->display_name }}<br>
									@endforeach
								</td>
							@endif
							<td> 
								@foreach ( $role->users as $user )
									{!! $user->name !!} <br>
								@endforeach
							</td>
						</tr>	
					  @endforeach			
				</tbody>
			</table>
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