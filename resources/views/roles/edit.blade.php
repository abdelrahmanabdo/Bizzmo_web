@extends('layouts.app')
@section('content') 
	@if (isset($role)) 
		{{ Form::model($role, array('id' => 'frmManage')) }}
		{{ Form::hidden('id', $role->id, array('id' => 'id', 'class' => 'form-control')) }}
		{{ Form::hidden('permissionnum', $role->permissions->count(), array('id' => 'permissionnum')) }}
	@else
		{{ Form::open(array('id' => 'frmManage')) }}
		{{ Form::hidden('client_id', Auth::user()->client_id, array('id' => 'client_id')) }}
		{{ Form::hidden('permissionnum', 0, array('id' => 'permissionnum')) }}
	@endif
	
	<div class="row">	<!-- row 1 -->
		<div class="col-sm-6">  <!-- column 1 -->
			<div class="form-group"> <!-- company_id -->  
					{{ Form::label('company_id', 'Company') }}
					@if (Auth::user()->isSysadmin)
						<p class='form-control-static'>{{ config('app.companyname') }}</p>
						{{ Form::hidden('company_id', 0, array('id' => 'company_id')) }}
					@else
						@if (isset($mode))
								<p class='form-control-static'>{{ $category->company->companyname }}</p>
						@else
							@if (isset($category))
								<p class='form-control-static'>{{ $category->company->companyname }}</p>
								{{ Form::hidden('company_id', $category->company_id, array('id' => 'company_id')) }}					
							@else
								{{ Form::select('company_id', $companies, Input::old('company_id'),array('id' => 'company_id', 'class' => 'form-control'))}}		
							@endif
						@endif
					@endif					
				</div> <!-- company_id end --> 
		</div>					<!-- end col 1 -->
		<div class="col-sm-6">  <!-- column 2 -->
			<div class="form-group"> <!-- Role Name -->  
				{{ Form::label('name', 'Role name') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $role->rolename }}</p>
				@else					
					{{ Form::text('rolename', Input::old('rolename'), array('id' => 'rolename', 'class' => 'form-control')) }}			
					@if ($errors->has('rolename')) <p class="bg-danger">{{ $errors->first('rolename') }}</p> @endif
				@endif
			</div> <!-- Role name -->  
		</div>					<!-- end col 2 -->		
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 2 -->
		<div class="col-sm-5">  <!-- column 3 -->
			<div class="form-group"> <!-- used permissions --> 				
				<label for="fltTarget">Used permissions</label>
				<input  class="form-control" type="text" id="fltTarget" name="fltTarget">	
				<select class="form-control" id="sbTarget" name= "sbTarget[]" multiple="multiple" size="15">
					@if (old('sbTarget'))
						@foreach(old('sbTarget') as $item)
							@foreach ($permissions as $permission)
								@if ($permission->id == $item)
									<option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
								@endif
							@endforeach
						@endforeach
					@else
						@if (isset($rolepermissions))
							@foreach ($rolepermissions as $permission)
								<option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
							@endforeach
						@endif
					@endif	
				</select>
				@if ($errors->has('permissionnum')) <p class="bg-danger">{{ $errors->first('permissionnum') }}</p> @endif
			</div> <!-- used permissions -->  
		</div>					<!-- end col 3 -->
		
		<div class="col-sm-2">  <!-- column 2 -->
			<div class="hidden-xs">
				<p class='form-control-static'>&nbsp;</p>
				<p class='form-control-static'>&nbsp;</p>
			</div>
			<div class="btn-group btn-group-justified" role="group">
				<a href="#" class="btn btn-primary btn-block" role="button" title="Remove all" id="leftall"><span class="glyphicon glyphicon-forward"></span></a>
				
				<a href="#" class="btn btn-primary btn-block" role="button" title="Remove selected" id="left"><span class="glyphicon glyphicon-chevron-right"></span></a>
				<a href="#" class="btn btn-primary btn-block" role="button" title="Add selected" id="right"><span class="glyphicon glyphicon-chevron-left"></span></a>					
				
				<a href="#" class="btn btn-primary btn-block" role="button" title="Add all" id="rightall"><span class="glyphicon glyphicon-backward"></span></a>
			</div>
		</div>
		
		<div class="col-sm-5">  <!-- column 1 -->
			<div class="form-group"> <!-- available permissions -->  
				<label for="fltSource">Available permissions</label>
				<input  class="form-control" type="text" id="fltSource" name="fltSource">
				<select class="form-control" id="sbSource" name="sbSource[]" multiple="multiple" size="15">
					@if (old('sbTarget'))						
						@foreach ($permissions as $permission)
							@php $add = 1; @endphp
							@foreach(old('sbTarget') as $item)								
								@if ($permission->id == $item)
									@php $add = 0; @endphp									
								@endif
							@endforeach
							@if ($add == 1)
								<option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
							@endif
						@endforeach
					@else
						@foreach ($permissions as $permission)
							<option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
						@endforeach
					@endif					
				</select>
			</div> <!-- available permissions -->  
		</div>					<!-- end col 1 -->					
		
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 3 --> 
		<div class="col-md-12"> <!-- column 1 -->
			@if (isset($mode))
				@if ($mode == 'd')
					<div class="col-md-3 col-lg-3"> <!-- column 1 -->
						<a href="{{ url('/roles/delete/' . $role->id) }}" class="btn btn-primary btn-danger fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-trash"></span></a>						
					</div> <!-- column 1 end -->
				@else
					<div class="col-md-3 col-lg-3"> <!-- column 1 -->
						<a href="{{ url("/roles/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>
					</div> <!-- column 1 end -->
					<div class="col-md-3 col-lg-3"> <!-- column 2 -->
						<a href="{{ url("/roles") }}" class="btn btn-primary fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
					<div class="col-md-3 col-lg-3"> <!-- column 3 -->
						<a href="{{ url("/roles/" . $role->id) }}" class="btn btn-primary fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
					</div>				
				@endif
			@else
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
				<a href="" class="btn btn-primary fixedw_button" id="lnksubmit" title="Save">
					<span class="glyphicon glyphicon-ok"></span>
				</a>
			@endif    
			
		</div> <!-- column 1 end -->
	</div> <!--row 3 end -->
	{{ Form::close() }}
@stop
 @push('scripts')	
 	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#permissionnum").val($('#sbTarget option').length);
				//alert($('#permissionnum').val());
				//return true;
				$("#fltSource").val('').click();
				$("#fltTarget").val('').click();
				$('#sbTarget option').prop('selected', true);
				$("#submit").click();
			});
			$("#company_id").change(function(){
				Updatepermissions();
			}); // $("#country_id").change end
			//filter source
			$("#fltSource").on("keyup click input",function(){
				//alert($("#fltSource").val());
				fltSource = $("#fltSource").val().toLowerCase();
				$('#sbSource option').each(function () {
					if (fltSource == '') {
						this.style.display = '';
					} else {
						if (this.text.toLowerCase().indexOf(fltSource) == -1) {
							this.style.display = 'none';
						} else {
							this.style.display = '';
						}
					}					
				});				
			});
			//filter source end
			//filter target
			$("#fltTarget").on("keyup click input",function(){
				//alert($("#fltTarget").val());
				fltTarget = $("#fltTarget").val().toLowerCase();
				$('#sbTarget option').each(function () {
					if (fltTarget == '') {
						this.style.display = '';
					} else {
						if (this.text.toLowerCase().indexOf(fltTarget) == -1) {
							this.style.display = 'none';
						} else {
							this.style.display = '';
						}
					}					
				});				
			});
			//filter target end
			//validation
			$("#frmManage").validate({
			rules: {				
				rolename: {
				required: true,
				maxlength: 60
				}
			},	
			messages: {
				rolename: "Role name cannot exceed 60 characters"
			}
			});	
		});
		$(function () { function moveItems(origin, dest) {
			$(origin).find(':selected').appendTo(dest);
		}		 
		function moveAllItems(origin, dest) {
			//$(origin).children().appendTo(dest);
			$(origin).find(':visible').appendTo(dest);
		}
		$('#left').click(function () {
			moveItems('#sbTarget', '#sbSource');
		});		 
		$('#right').on('click', function () {
			moveItems('#sbSource', '#sbTarget');
		});		 
		$('#leftall').on('click', function () {
			moveAllItems('#sbTarget', '#sbSource');
		});		 
		$('#rightall').on('click', function () {
			moveAllItems('#sbSource', '#sbTarget');
		});
		$('#sbSource').dblclick(function() {
		   moveItems('#sbSource', '#sbTarget');
		});
		$('#sbTarget').dblclick(function() {
		   moveItems('#sbTarget', '#sbSource');
		});
		});
		
		function Updatepermissions () {
			var url = '/modules/permissions';
				// ajax call
				$('#sbSource').find('option').remove().end();
				$('#sbTarget').find('option').remove().end();
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
							console.log(item);
							$('#sbSource').append($("<option></option>").attr("value", i).text(item));
						});
					}, // End of success function of ajax form
					error: function(output_string){				
						alert(jxhr.responseText);
					}
				}); //ajax call end
		}
		
	</script>
@endpush