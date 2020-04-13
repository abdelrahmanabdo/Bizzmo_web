@extends('layouts.app', ['hideRightMenu' =>  true , 'hideLeftMenu' =>  true]) 
@section('content')
<div class="form-container">
	<div class="row  form-header-container">
		<a href="javascript:history.go(-1)" class="back-arrow">
			<img src="{{asset('images/arrow-left.svg')}}" />
		</a>
		<div class="form-header-title-container">
			<h2 class="form-header-title">{{isset($user) ? 'Edit User' : 'Create New User'}} </h2>
			@if(!isset($user))<h4 class="form-header-hint"> You can easily add new User to your Users .</h4>@endif
		</div>
	</div>
	<div class="tabbable-panel">
	<div class="tab-content row">
@php $labelClass = '' @endphp
@if (isset($user)) 
	{{ Form::model($user, array('id' => 'frmManage', 'class' => 'user-form')) }}
	{{ Form::hidden('id', $user->id, array('id' => 'id', 'class' => 'form-control')) }}
	@php
		$labelClass = (isset($mode) && $mode == 'v') ? 'label-view' : '';
	@endphp
@else
	{{ Form::open(array('id' => 'frmManage', 'class' => 'user-form')) }}
@endif
	<div class="">	<!-- row 1 -->
		<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-2'}} ">  <!-- column 1 -->
			<div class="upload-image-container">
				<div class="uploader logoContainer">
					@isset($profile)
					   <img src="{{asset($profile->logo)}}" />
					@endisset
					{{Form::file('avatar' ,array('class'=>'inputFile' , 'id' => 'logoUploader'))}}
				</div>
			</div>
		</div>
		<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-5'}} ">  <!-- column 1 -->
			<div class="form-group text-input"> <!-- User name -->  
				{{ Form::label('username', 'Name', ['class' => isset($mode) ? 'control-label bm-label col-sm-2':'form-label']) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $user->name }}</p>
				@else					
					@if (isset($assign))
						<p class='form-control-static'>{{ $user->name }}</p>
						{{ Form::hidden('name', Input::old('name'), array('id' => 'name', 'class' => 'form-control')) }}
					@else
						{{ Form::text('name', Input::old('name'), array('id' => 'name', 'class' => 'form-control')) }}
					@endif					
					@if ($errors->has('name')) <p class="bg-danger">{{ $errors->first('name') }}</p> @endif					
				@endif
			</div> <!-- User name -->  
		</div>					<!-- end col 1 -->
		<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-5'}}">  <!-- column 2 -->
			<div class="form-group text-input"> <!-- email -->  
				{{ Form::label('email', 'Email', ['class' =>  isset($mode) ? 'control-label bm-label col-sm-2':'form-label']) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $user->email }}</p>
				@else					
					@if (isset($assign))
						<p class='form-control-static'>{{ $user->email }}</p>
						{{ Form::hidden('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control')) }}			
					@else
						{{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control')) }}			
					@endif					
					@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
				@endif
			</div> <!-- email end -->  
		</div>					<!-- end col 2 -->	
		<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-5'}}">  <!-- column 3 -->
			<div class="form-group text-input"> <!-- title -->  
				{{ Form::label('title', 'Job title', ['class' =>  isset($mode) ? 'control-label bm-label col-sm-2':'form-label']) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $user->title }}</p>
				@else
					@if (isset($assign))
						<p class='form-control-static'>{{ $user->title }}</p>
						{{ Form::hidden('title', Input::old('title'), array('id' => 'title', 'class' => 'form-control')) }}			
					@else
						{{ Form::text('title', Input::old('title'), array('id' => 'title', 'class' => 'form-control')) }}			
					@endif					
					@if ($errors->has('title')) <p class="bg-danger">{{ $errors->first('title') }}</p> @endif
				@endif
			</div> <!-- title end -->  
		</div>					<!-- end col 3 -->	
	</div>				<!-- end row 1 -->		
	<div class="">	<!-- row 2 -->
		@if (!isset($user))	
			<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-5'}}">  <!-- column 1 -->
				<div class="form-group text-input"> <!-- password -->  
					{{ Form::label('password', 'Password' , array('class'=> isset($mode) ? 'control-label bm-label col-sm-2':'form-label'))  }}							
					<input type="password" class="form-control" id= "password" name="password" value="{{ isset($user) ? $client->password : old('password') }}">									
					@if ($errors->has('password')) <p class="bg-danger">{{ $errors->first('password') }}</p> @endif	
				</div> <!-- password --> 			
			</div>					<!-- end col 1 -->		
			<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-5'}}">  <!-- column 2 -->
				<div class="form-group text-input"> <!-- password confirmation -->  
					{{ Form::label('password_confirmation', 'Confirm password', array('class'=>  isset($mode) ? 'control-label bm-label col-sm-2':'form-label')) }}
					<input type="password" class="form-control" id= "password_confirmation" name="password_confirmation" value="{{ isset($user) ? $client->password_confirmation : old('password_confirmation') }}">									
					@if ($errors->has('password_confirmation')) <p class="bg-danger">{{ $errors->first('password_confirmation') }}</p> @endif					
					
				</div> <!-- password confirmation end -->  
			</div>					<!-- end col 2 -->			
		@endif
		<div class="{{isset($mode) ? 'col-sm-12' : 'col-sm-4'}}">  <!-- column 1 -->
			 <div class="form-group col-sm-12" style='display : flex ; align-items : center'> <!-- Active -->  
				{{ Form::label('active', 'Active', ['class' =>  isset($mode) ? 'control-label bm-label col-sm-2':'col-sm-4' ]) }}
				@if (isset($mode))	
					@if ($user->active)
						<p class='form-control-static'>Yes</p>
					@else
						<p class='form-control-static'>No</p>
					@endif
				@else
				<div class="form-horizontal col-sm-8">
					<div class="radio"> <!-- Active -->  
						<label class="checkbox">
							{{ Form::checkbox('active', Input::old('active'), true, ['id' => 'active', 'class' => 'bm-checkbox']) }}			
							<span class="checkmark"></span>
							@if ($errors->has('active')) <p class="bg-danger">{{ $errors->first('active') }}</p> @endif
							
						</label>
					</div>
				</div>
				@endif
			</div> <!-- Active -->  
		</div>
	</div>				<!-- end row 2 -->	
	<div class="">	<!-- row 3 -->
		@if (!isset($mode))	
			<div class="col-sm-6 hidden">  <!-- column 1 -->
				<div class="form-group"> <!-- company -->  
					{{ Form::label('company_id', 'Company', ['class' => $labelClass]) }}
					{{ Form::select('company_id', $companies, Input::old('company_id'),array('id' => 'company_id', 'class' => 'hidden bm-select form-control'))}}
					@if ($errors->has('company_id')) <p class="bg-danger">{{ $errors->first('company_id') }}</p> @endif
				</div> <!-- company end -->  
			</div>				<!-- end column 1 -->
			<div class="col-sm-5 col-xs-offset-2">  <!-- column 2 -->
				<div class="form-group text-input"> <!-- role -->  
					{{ Form::label('role_id', 'Role', ['class' => isset($mode) ? 'control-label bm-label col-sm-2':'form-label'																																						]) }}
					<div class="flex-container">
						{{ Form::select('role_id', $roles, Input::old('role_id'),array('id' => 'role_id', 'class' => 'bm-select form-control input-with-icon' , 'placeholder' => ''))}}					
						<a href="" id="lnkrole" role="button" class="add-button"></a>					
					</div>
					@if ($errors->has('role_id')) <p class="bg-danger">{{ $errors->first('role_id') }}</p> @endif
					@if (old('del'))
						@php
							$i = 0;
							$count = 0;
						@endphp
						@foreach (old('del') as $item)
							@if (old('del')[$i] != 1)
								@php
									$count = $count + 1;
								@endphp
							@endif
						@endforeach
						{{ Form::hidden('rolecount', $count, array('id' => 'rolecount')) }}
					@else
						@if (isset($user))
							{{ Form::hidden('rolecount', $user->roles->count(), array('id' => 'rolecount')) }}								
						@else
							{{ Form::hidden('rolecount', 0, array('id' => 'rolecount')) }}								
						@endif
					@endif					
					@if ($errors->has('rolecount')) <p class="bg-danger">{{ $errors->first('rolecount') }}</p> @endif
				</div> <!-- role end -->  			
			</div>				<!-- end col 2 -->
		@endif
	</div>				<!-- end row 3 -->	
	<div class="">	<!-- row 4 -->
		<div class="col-sm-12 biz-mb-3">  <!-- column 1 -->
			<table id="userstable" class="table table-striped table-bordered table-hover table-tight dataTable @if(!isset($user) && old('del') == null) hidden @endif">
				<thead>
					@if (!isset($mode))
						<th class="no-sort" width="10%">				
							&nbsp;
						</th>					
					@endif
					<th class="hidden">Company</th>
					<th>Role</th>					
				</thead>
				<tbody>
				@if (old('del'))
					@php
						$i = 0;
					@endphp
					@foreach (old('del') as $item)
						<tr style="{{ (old('del')[$i]) ? 'display:none' : '' }}">
						@if (!isset($mode))
							<td>
								<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
								{{ Form::hidden('roleid[]', old('roleid')[$i], array('id' => 'roleid')) }}
								{{ Form::hidden('companyid[]', old('companyid')[$i], array('id' => 'companyid')) }}
								{{ Form::hidden('del[]', old('del')[$i], array('id' => 'del')) }}
								{{ Form::hidden('newrow[]', old('newrow')[$i], array('id' => 'newrow')) }}
							</td>
						@endif
						<td class="hidden"><input name="companyname[]" type="hidden" value="{{ old('companyname')[$i] }}">{{ old('companyname')[$i] }}</td>
						<td><input name="rolename[]" type="hidden" value="{{ old('rolename')[$i] }}">{{ old('rolename')[$i] }}</td>
						</tr>
							@php
								$i++;
							@endphp	
					@endforeach
				@else
					@if (isset($user))				
						@foreach ($user->roles as $role)
							<tr>
								@if (!isset($mode))
									<td>
										<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
										{{ Form::hidden('roleid[]', $role->id, array('id' => 'roleid')) }}
										{{ Form::hidden('companyid[]', $role->company_id, array('id' => 'companyid')) }}
										{{ Form::hidden('del[]', '', array('id' => 'del')) }}
										{{ Form::hidden('newrow[]', '', array('id' => 'newrow')) }}
									</td>
								@endif
								
								<td class="hidden"><input name="companyname[]" type="hidden" value="{{ $role->company_id == 0 ? config('app.companyname'): $role->company->companyname }}">{{ $role->company_id == 0 ? config('app.companyname'): $role->company->companyname }}</td>
								<td><input name="rolename[]" type="hidden" value="{{ $role->rolename }}">{{ $role->rolename }}</td>
							</tr>
						@endforeach
					@endif
				@endif			
				</tbody>
			</table>
		</div>
	</div>				<!-- end row 4 -->		
	<div class="">	<!-- row 5 --> 
		<div class="col-md-12"> <!-- column 1 -->
		@if (isset($mode))
			<div class="center"> <!-- column 1 -->			
				@if (Gate::allows('usXXas'))
					<a href='{{ url("/users/create") }}' class="biz-button colored-default" role="button" title="Create" style="margin: 0 5px">Add</a>
				@endif
				<a href='{{ url("/users/" . $user->id) }}' class="biz-button colored-yellow" role="button" title="Edit" style="margin: 0 5px">Edit</a>
				@if (Gate::allows('usXXas'))
					<a href='{{ url("/users/assign/" . $user->id) }}' class="biz-button bm-btn blue fixedw_button" role="button" title="Assign" style="margin: 0 5px"><span class="glyphicon glyphicon-sunglasses"></span></a>
				@endif
			</div>
			<!-- <div class="col-xs-6 col-sm-4"> 
			</div>
			<div class="col-xs-6 col-sm-4"> 
			</div> -->
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'biz-button colored-default hidden' )) }}
			<a href="" class="biz-button colored-default" style="float:right" id="lnksubmit">
				Save
			</a>
		@endif 	
		</div> <!-- column 1 end -->
	</div> <!--row 5 end -->
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
			//validation
			$("#frmManage1").validate({
			rules: {
				name: {
				required: true,
				maxlength: 60
				},
				email: {
				required: true,
				email: true
				}
			},	
			messages: {
				username: "Length between 1 and 60",
				email: "Must be valid email"
			}
			});
			//validation end			
		$("#company_id").change(function(){
			var url = '/companies/roles';
			// ajax call
			$('#role_id').find('option').remove().end();
			$.ajax({
				url: url,
				type:'post',
				data: {
					'company_id':$('select[name=company_id]').val(),
					'_token': $('input[name=_token]').val()
				},
				cache: false,
				success: function(data){
					$.each(data, function(i, item) {
						$('#role_id').append($("<option></option>").attr("value", item.id).text(item.rolename));
					});
				}, // End of success function of ajax form
				error: function(output_string){				
					alert(jxhr.responseText);
				}
			}); //ajax call end
		}); // $("#company_id").change end
		//lnkrole click

		@if(isset($mode) || isset($user))
				$('.text-input').addClass('focused');
		@endif
		$("#lnkrole").bind('click', function(e) {
			$('#userstable').removeClass('hidden');
			e.preventDefault();
			var table = document.getElementById('userstable');
			var rowLength = table.rows.length;
			for (var i = 1; i < rowLength; i += 1) {
				var row = table.rows[i];
				var inputs = row.cells[0].getElementsByTagName("input");
				if (row.style.display != 'none' && inputs.length) {
					if ((inputs[0].value.trim() == $("#role_id").val()) && (inputs[1].value.trim() == $("#company_id").val())) {
						alert('This role is already assigned to this user');
						return false;
					}
				}
			}
			var row = '<tr>';							
			row = row + '<td>';
			row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDel" type="button" title="delete"></a>';
			row = row + '<input name="roleid[]" type="hidden" value="' + $("#role_id").val() + '">';
			row = row + '<input name="companyid[]" type="hidden" value="' + $("#company_id").val() + '">';
			row = row + '<input name="del[]" id="del" type="hidden">';
			row = row + '<input name="newrow[]" id="newrow" type="hidden" value="1">';
			row = row + '</td>';
			row = row + '<td class="hidden"><input name="companyname[]" type="hidden" value="' + $("#company_id option:selected").text() + '">' + $("#company_id option:selected").text() + '</td>';
			row = row + '<td><input name="rolename[]" type="hidden" value="' + $("#role_id option:selected").text() + '">' + $("#role_id option:selected").text() + '</td>';
			row = row + '</tr>';
			$('#userstable').append(row);
			if (document.getElementById('rolecount').value == '') {
				document.getElementById('rolecount').value = 1;
			} else {
				document.getElementById('rolecount').value = parseInt(document.getElementById('rolecount').value) + 1;
			}
			//alert (document.getElementById('rolecount').value);
		});
		//lnkrole click end
	});
	//DelRow
	function DelRow(lnk) {
		var tr = lnk.parentNode.parentNode;
		var td = lnk.parentNode;
		var inputs = td.getElementsByTagName("input");	
		var inputslengte = inputs.length;
		for(var j = 0; j < inputslengte; j++){
				var inputval = inputs[j].id;                
				if (inputval == 'del') {
					inputs[j].value  = 1;
				}
			}
		tr.style.display = 'none';
		document.getElementById('rolecount').value = parseInt(document.getElementById('rolecount').value) - 1;
		if(document.getElementById('rolecount').value == 0) {
			$('#userstable').addClass('hidden');

		}
	}
	//DelRow end
	</script>
@endpush

