@extends('layouts.app')
@section('content')	
	@if (isset($materialgroup)) 
		{!! Form::model($materialgroup, array('id' => 'frmManage')) !!}
	@else
		{!! Form::open(array('id' => 'frmManage')) !!}
	@endif

		<div class="row-fluid">	<!-- row 1 -->
			<div class="col-sm-4">  <!-- Column 1 -->
				<div class="form-group"> <!-- Material group name -->  
					{!! Form::label('name', 'Material group name') !!}
					{!! Form::text('name', Input::old('name'), array('id' => 'name', 'class' => 'form-control')) !!}			
					{!! Form::hidden('id', Input::old('id'), array('id' => 'id')) !!}
					@if ($errors->has('name')) <p class="bg-danger">{!! $errors->first('name') !!}</p> @endif
				</div> <!-- Material group name -->  
			</div>					<!-- end col 1 -->
			<div class="col-sm-6">  <!-- Column 2 -->
				<div class="form-group"> <!-- Description -->  
					{!! Form::label('description', 'Description') !!}
					{!! Form::text('description', Input::old('description'), array('id' => 'description', 'class' => 'form-control')) !!}			
					@if ($errors->has('description')) <p class="bg-danger">{!! $errors->first('description') !!}</p> @endif
				</div> <!-- Material group name -->  
			</div>					<!-- end col 2 -->			
			<div class="col-sm-2">  <!-- Column 3 -->
				<div class="form-group"> <!-- Active -->  
					{!! Form::label('active', 'Active') !!}
					<div class="icheckbox">
						{!! Form::checkbox('active', Input::old('active'), true, array('id' => 'active')) !!}			
						@if ($errors->has('active')) <p class="bg-danger">{!! $errors->first('active') !!}</p> @endif
					</div>
				</div> <!-- Active -->  
			</div>					<!-- end col 3 -->
		</div>				<!-- end row 1 -->

	<div class="row-fluid">	<!-- row 3 --> 
	<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
		@if (isset($mode))
		@else
				{!! Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button')) !!}
				<a href="" class="btn btn-primary fixedw_button" id="lnksubmit">
					<span class="glyphicon glyphicon-ok"></span>
				</a>
		@endif    
		
	</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
	{!! Form::close() !!}
	<div class="row-fluid">	<!-- row 4 -->
		<div class="col-md-12 col-lg-12"> 	<!-- Column 1 -->
			&nbsp;
		</div> 				<!-- Column 1 end -->

	</div> 			<!-- row 4 end -->
	<div class="row-fluid">	<!-- row 5 -->
	<table id="mytable" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th class="no-sort" width="10%">Action</th>
			<th>Name</th>
			<th>Description</th>
			<th>Active</th>
		</tr>		
	</thead>
	<tbody>			
		  @foreach ($materialgroups as $materialgroup)
			<tr>
				<td>
						<a href="{!! url("/materialgroups/" . $materialgroup->id) !!}" role="button"><span class="glyphicon glyphicon-pencil orange" title="edit"></span></a>	
				</td>
		       	<td> {!! $materialgroup->name !!} </td>
		       	<td> {!! $materialgroup->description !!} </td>
				<td> @if ($materialgroup->active == 1) Yes @else No @endif </td>
			</tr>	
		  @endforeach			
	</tbody>
	</table>
	</div>				<!-- end row 5 -->
	<script type="text/javascript">
		$(document).ready(function(){		
		//data table
		$('#mytable').dataTable({	
			"order": [ 1, 'asc' ],
			"aoColumnDefs": [ { 'bSortable': false, 'aTargets': [ "no-sort" ] } ],
			"iDisplayLength": 10,			
			"pagingType": "full_numbers"		
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
				description: {
				maxlength: 250
				}
			},	
			messages: {
				name: "Length between 1 and 60",
				description: "Maximum length is 250 only"
			}
			});			
		});
	</script>
@endpush