@extends('layouts.app') 
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if (isset($company)) 
		{{ Form::model($company, array('id' => 'frmManage', 'files' => true)) }}
		{{ Form::hidden('company_id', $company->id, array('id' => 'company_id')) }}
		{{ Form::hidden('requesttype_id', $requesttype_id, array('id' => 'requesttype_id')) }}
	@else
		{{ Form::model($creditrequest, array('id' => 'frmManage', 'files' => true)) }}
		{{ Form::hidden('requesttype_id', $creditrequest->requesttype_id, array('id' => 'requesttype_id')) }}
	@endif
	<div class="row">	<!-- row 1 -->		
		<div class="col-md-4">  <!-- Column 1 -->
			<div class="form-group"> <!-- Company name -->  
				{{ Form::label('companyname', 'Company name') }}
				@if (isset($company))
					<p class='form-control-static'>{{ $company->companyname }}</p>
				@endif
				@if (isset($creditrequest))
					<p class='form-control-static'>{{ $creditrequest->company->companyname }}</p>
				@endif
			</div> <!-- Company name -->  
		</div>					<!-- end col 1 -->
		<div class="col-md-4">  <!-- Column 2 -->
			<div class="form-group"> <!-- address -->  
				{{ Form::label('address', 'Address') }}
				@if (isset($company))
					<p class='form-control-static'>{{ $company->address }}</p>				
				@endif
				@if (isset($creditrequest))
					<p class='form-control-static'>{{ $creditrequest->company->address }}</p>
				@endif
			</div> <!-- address end -->  
		</div>					<!-- end col 2 -->
		<div class="col-md-4">  <!-- Column 3 -->
			<div class="form-group"> <!-- address -->  
				{{ Form::label('country', 'Country') }}
				@if (isset($company))
					<p class='form-control-static'>{{ $company->country->countryname }}</p>				
				@endif
				@if (isset($creditrequest))
					<p class='form-control-static'>{{ $creditrequest->company->country->countryname }}</p>
				@endif
			</div> <!-- address end -->  
		</div>					<!-- end col 3 -->			
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 2 -->
		<div class="col-md-6">  <!-- col 1 -->
			@if(isset($mode))
				{{ Form::label('attbank', 'View bank statement') }}<br>
				<a href="/creditrequests/attachment/bank/{{$creditrequest->id}}">Bank statement</span></a>
			@else
				{{ Form::label('attbank', 'Attach bank statement') }}<br>
				<input type="file" name="attbank" id="attbank" title="Browse" class="btn btn-success">
				@if ($errors->has('attbank')) <p class="bg-danger">{{ $errors->first('attbank') }}</p> @endif
			@endif
		</div>	<!-- col 1 end -->
		<div class="col-md-6">  <!-- col 2 -->
			@if(isset($mode))
				{{ Form::label('attbank', 'View financial statement') }}<br>
				<a href="/creditrequests/attachment/financial/{{$creditrequest->id}}">Financial statement</span></a>
			@else
				{{ Form::label('attfinstat', 'Attach financial statement') }}<br>
				<input type="file" name="attfinstat" id="attfinstat" title="Browse" class="btn btn-success">
				@if ($errors->has('attfinstat')) <p class="bg-danger">{{ $errors->first('attfinstat') }}</p> @endif
			@endif
		</div>	<!-- col 2 end -->	
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 2 -->
		<div class="col-md-2">  <!-- Column 1 -->
			<div class="form-group"> <!-- askedlimit -->  
				{{ Form::label('askedlimit', 'Requested limit') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ number_format($creditrequest->askedlimit, 2, '.', ',') }}</p>
				@else					
					{{ Form::text('askedlimit', Input::old('askedlimit'), array('id' => 'askedlimit', 'class' => 'form-control')) }}								
					@if ($errors->has('askedlimit')) <p class="bg-danger">{{ $errors->first('askedlimit') }}</p> @endif
				@endif
			</div> <!-- askedlimit end -->  
		</div>					<!-- end col 1 -->
		<div class="col-md-10">  <!-- Column 2 -->
			<div class="form-group"> <!-- Company name -->  
				{{ Form::label('justification', 'Justification') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $creditrequest->justification }}</p>
				@else					
					{{ Form::text('justification', Input::old('justification'), array('id' => 'justification', 'class' => 'form-control')) }}								
					@if ($errors->has('justification')) <p class="bg-danger">{{ $errors->first('justification') }}</p> @endif
				@endif
			</div> <!-- Company name -->  
		</div>					<!-- end col 2 -->
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 3 --> 
		<div class=" col-md-12"> <!-- Column 1 -->
		@if (isset($mode))
			@if (Gate::allows('cr_cr'))
				<div class="col-xs-3"> <!-- Column 1 -->			
					<a href="{{ url("/creditrequests/create") }}" role="button" title="Create"><span class="add-icon"></span></a>						
				</div> <!-- Column 1 end -->
			@endif
			@if (Gate::allows('cr_sc'))
				<div class="col-xs-3"> <!-- Column 2 -->
					<a href="{{ url("/creditrequests") }}" class="btn btn-info fixedw_button bm-btn" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
				</div>
			@endif
			@if (Gate::allows('cr_ch', $creditrequest->id) && $creditrequest->creditstatus_id == 2)
				<div class="col-xs-3"> <!-- Column 3 -->
					<a href="{{ url("/creditrequests/" . $creditrequest->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
				</div>
			@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn btn-primary fixedw_button bm-btn green" id="lnksubmit" type="button">
				<span class="glyphicon glyphicon-ok"></span>
			</a>
		@endif 	
		</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
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
			
			$('input[type=file]').bootstrapFileInput();
			$('.file-inputs').bootstrapFileInput();
			
			
			$("#lnkbusref").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('busreftable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelOwner" type="button"><span class="glyphicon glyphicon-trash" title="Add owner"></span></a>';
				row = row + '<input name="busrefid[]" type="hidden">';
				row = row + '<input name="busrefdel[]" id="busrefdel" type="hidden">';
				row = row + '</td>';
				row = row + '<td><input name="busrefname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="busreflimit[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="busreftype[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="busreflength[]" type="text" class="form-control"></td>';
				row = row + '</tr>';
				$('#busreftable').append(row);
				$("#busrefcount").val(parseInt($("#busrefcount").val()) + 1);
			});
			//validation
			$("#frmManage").validate({
			rules: {
				attbank: {
					required: true, 
					accept: "image/jpeg, image/jpg, , image/png, application/pdf"
				},
				attfinstat: {
					required: true, 
					accept: "image/jpeg, image/jpg, , image/png, application/pdf"
				},
			},	
			messages: {
				attbank: "You must select a file to upload",
				attfinstat: "You must select a file to upload",
			}
			});
			//validation end
		});
		function DelRow(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
					var inputval = inputs[j].id;                
					inputs[j].value  = 1;
					tr.cells[1].getElementsByTagName("input")[0].value='A';
					tr.cells[2].getElementsByTagName("input")[0].value='0';
					tr.cells[3].getElementsByTagName("input")[0].value='A';
					tr.cells[4].getElementsByTagName("input")[0].value='0';
					$("#busrefcount").val(parseInt($("#busrefcount").val()) - 1);
				}
			tr.style.display = 'none';
		}
	</script>
	<script src="{{ asset('js/bootstrap.file-input.js') }}"></script>
@endpush