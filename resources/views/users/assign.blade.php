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
		});
	</script>
	{{ Form::model($user, array('id' => 'frmManage')) }}
	{{ Form::hidden('id', $user->id, array('id' => 'id', 'class' => 'form-control')) }}
	<div class="row-fluid">	<!-- row 1 -->
		<div class="col-md-6">  <!-- Column 1 -->
			<div class="form-group"> <!-- User Name -->  
				{{ Form::label('name', 'Name') }}
				<p class='form-control-static'>{{ $user->name }}</p>
			</div> <!-- User name -->  
		</div>					<!-- end col 1 -->					
		<div class="col-md-6">  <!-- Column 2 -->
			<div class="form-group"> <!-- email -->  
				{{ Form::label('email', 'email') }}
				<p class='form-control-static'>{{ $user->email }}</p>				
			</div> <!-- email -->
		</div>					<!-- end col 2 -->
	</div>				<!-- end row 1 -->
	<div class="row-fluid">	<!-- row 2 -->
		<div class="col-md-4">  <!-- Column 1 -->
			<div class="form-group"> <!-- role -->  
				{{ Form::label('role_id', 'Role') }}
				{{ Form::select('role_id', $roles, Input::old('role_id'),array('id' => 'role_id', 'class' => 'form-control'))}}		
				@if ($errors->has('role_id')) <p class="bg-danger">{{ $errors->first('role_id') }}</p> @endif
			</div> <!-- role end --> 			
		</div>					<!-- end col 1 -->
		<div class="col-md-4">  <!-- Column 2 -->
			<div class="form-group"> <!-- company -->  
				{{ Form::label('company_id', 'Company') }}
				{{ Form::select('company_id', $companies, Input::old('company_id'),array('id' => 'company_id', 'class' => 'form-control'))}}		
				@if ($errors->has('company_id')) <p class="bg-danger">{{ $errors->first('company_id') }}</p> @endif
			</div> <!-- company end --> 			
		</div>					<!-- end col 2 -->
		<div class="col-md-4">  <!-- Column 3 -->
			<div class="form-group"> <!-- branch -->  
				{{ Form::label('branch_id', 'Branch') }}
				{{ Form::select('branch_id', $firstcompanybranches, Input::old('branch_id'),array('id' => 'branch_id', 'class' => 'form-control'))}}		
				@if ($errors->has('branch_id')) <p class="bg-danger">{{ $errors->first('branch_id') }}</p> @endif
			</div> <!-- branch end --> 			
		</div>					<!-- end col 3 -->		
	</div>				<!-- end row 2 -->	
	<div class="row-fluid">	<!-- row 3 -->
		<div class="col-md-12 col-lg-12"> <!-- Column 1 -->		
		<table id="mytable" class="table table-striped table-bordered table-hover table-condensed">
			<thead>
				<tr>
					<th class="no-sort" width="10%">
						<a href="#" class="btn btn-info" role="button" onclick="addroles();"><span class="glyphicon glyphicon-plus"></span></a>				
					</th>
					<th>Role</th>
					<th>Company</th>
					<th>Branch</th>					
				</tr>		
			</thead>
			<tbody>					
				<?php $i = 0 ; ?>
				@foreach ($user->roles as $role)					
					@foreach ($branches as $id => $name)
						@if ($id == $role->pivot->branch_id)
							<?php $i = $i + 1 ; ?>
							<tr>				
								<td>
									<input type="hidden" name="del[{{ $i }}]" id="del" value="0" class="del">
									<a href="#" class="btn btn-danger deleteRowButton" role="button"><span class="glyphicon glyphicon-trash"></span></a>
								</td>
								<td>
								<input type="hidden" name="roleid[{{ $i }}]" id="roleid" value="{{ $role->id}}">
								{{ $role->rolename }}
								</td>
								@foreach ($user->branches as $branch)
									@if ($branch->id == $role->pivot->branch_id)										
										<?php $branchname = $branch->branchname ; ?>
										<?php $companyname = $branch->company->companyname ; ?>
										<?php $branchid = $branch->id ; ?>
									@endif
								@endforeach
								<td>
									{{ $companyname }}
								</td>
								<td>
									<input type="hidden" name="branchid[{{ $i }}]" id="branchid" value="{{ $branchid }}">
									{{ $branchname }}
								</td>
							</tr>	
						@endif
					@endforeach
				@endforeach					
			</tbody>
		</table>		
		</div> <!-- Column 1 end -->
	</div> <!-- row 3 end -->	
	<div class="row-fluid">	<!-- row 4 --> 
	<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
		@if (isset($mode))
			<div class="col-md-4 col-lg-4"> <!-- Column 1 -->
				<a href="{{ url("/companys/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>						
			</div> <!-- Column 1 end -->
			<div class="col-md-4 col-lg-4"> <!-- Column 2 -->
				<a href="{{ url("/companys") }}" class="btn btn-primary" role="button" title="Search">Search</a>
			</div>
			<div class="col-md-4 col-lg-4"> <!-- Column 3 -->
				<a href="{{ url("/companys/" . $company->id) }}" class="btn btn-primary" role="button" title="Edit">Edit</a>
			</div>
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button')) }}
			<a href="" class="btn btn-primary" id="lnksubmit">
				SAve
			</a>
		@endif    
		
	</div> <!-- Column 1 end -->
	</div> <!--row 4 end -->
	<input type="hidden" name="assigncount" id="assigncount" value="<?php echo $i ; ?>">
	{{ Form::close() }}
	<script type="text/javascript">
		$(document).ready(function(){
			//Delete assignment
			$('.deleteRowButton').click(DeleteRow);
			// company change ajax
			$("#company_id").change(function(){
				//branch
				$('#branch_id').find('option').remove().end();
				$.ajax({
					url: '<?php echo '/company/branches/';?>' + $('#company_id').val(),
					type:'GET',
					dataType: 'json',
					cache: false,
					success: function(data){
						$.each(data, function(i, item) {
							$('#branch_id').append($("<option></option>").attr("value", item.id).text(item.branchname)); 
							
						});
					}, // End of success function of ajax form
					error: function(output_string){				
						alert(jxhr.responseText);
					}
				}); //End of ajax call
				
			}); // company change ajax end
		});
		function addroles() {
			var table = document.getElementById('mytable');
			var rowLength = table.rows.length;
			var row = table.rows[rowLength - 1];
			cell = row.cells[0].innerHTML;
			if (cell == 'No data available in table') {
				table.deleteRow(rowLength - 1)
			}
			if (rowLength > 2) {
				for (var i = 1; i <= rowLength - 1; i += 1){
					var row = table.rows[i];
					role = row.cells[1].innerHTML;
					company = row.cells[2].innerHTML;
					branch = row.cells[3].innerHTML;
					if (role.includes($('#role_id option:selected').text()) && company.includes($('#company_id option:selected').text()) && branch.includes($('#branch_id option:selected').text()))  {
						if (row.style.display != 'none') {
							alert("Already assigned");
							return false;
						}										
					}
				}
			}
			var assigncount = eval($('#assigncount').val()) + 1;
			//alert(assigncount);
			//alert($('#role_id option:selected').text());
			
			var row = '<tr><td>';
			row = row + '<input type="hidden" name="del[' + assigncount + ']" class="del" id="del" value="2">';
			row = row + '<a href="#" class="btn btn-danger" onclick="DelRow(this);return false;" id="btnDelProduct"><span class="glyphicon glyphicon-trash"></span></a>';
			row = row + '</td>';
			row = row + '<td><input name="roleid[' + assigncount + ']" type="hidden" class="form-control" value="' + $('#role_id').val() + '">' + $('#role_id option:selected').text() + '</td>';
			row = row + '<td>' + $('#company_id option:selected').text() + '</td>';
			row = row + '<td><input name="branchid[' + assigncount + ']" type="hidden" class="form-control" value="' + $('#branch_id').val() + '">' + $('#branch_id option:selected').text() + '</td>';
			row = row + '</tr>';
			$('#mytable').append(row);
			$('#assigncount').val(assigncount);
			//return false;			
		}
		function DeleteRow() {
			var tr = $(this).closest('tr');
			var buttonHandle = $(this).closest('td').find('.del');
			//alert("myObject is " + buttonHandle.toSource());
			buttonHandle.val(1);		
			tr.hide();      
		}
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
			tr.hide();      
		}
		
	</script>
@stop
 

