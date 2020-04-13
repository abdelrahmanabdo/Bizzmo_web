@extends('layouts.app')
@section('content')	
	@if (isset($paymentterm)) 
		{!! Form::model($paymentterm, array('id' => 'frmManage')) !!}
	@else
		{!! Form::open(array('id' => 'frmManage')) !!}
	@endif

		<div class="row-fluid">	<!-- row 1 -->
			<div class="col-sm-4">  <!-- Column 1 -->
				<div class="form-group"> <!-- Material group name -->  
					{!! Form::label('name', 'Payment term name') !!}
					{!! Form::text('name', Input::old('name'), array('id' => 'name', 'class' => 'form-control')) !!}			
					{!! Form::hidden('id', Input::old('id'), array('id' => 'id')) !!}
					@if ($errors->has('name')) <p class="bg-danger">{!! $errors->first('name') !!}</p> @endif
				</div> <!-- Material group name -->  
			</div>					<!-- end col 1 -->
			<div class="col-sm-6">  <!-- Column 2 -->
				<div class="form-group"> <!-- Description -->  
					{!! Form::label('buyup', 'Fees %') !!}
					{!! Form::text('buyup', Input::old('buyup'), array('id' => 'buyup', 'class' => 'form-control')) !!}			
					@if ($errors->has('buyup')) <p class="bg-danger">{!! $errors->first('buyup') !!}</p> @endif
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
			<th>Fees %</th>
			<th>Active</th>
		</tr>		
	</thead>
	<tbody>			
		  @foreach ($paymentterms as $paymentterm)
			<tr>
				<td>
						<a href="{!! url("/paymentterms/" . $paymentterm->id) !!}" role="button"><span class="glyphicon glyphicon-pencil orange" title="edit"></span></a>	
				</td>
		       	<td> {!! $paymentterm->name !!} </td>
		       	<td> {!! $paymentterm->buyup !!} </td>
				<td> @if ($paymentterm->active == 1) Yes @else No @endif </td>
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