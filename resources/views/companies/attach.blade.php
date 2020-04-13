@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if (isset($company)) 
		{{ Form::model($company, array('id' => 'frmManage', 'files' => true)) }}
		{{ Form::hidden('id', $company->id, array('id' => 'id', 'class' => 'form-control')) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'files' => true)) }}
	@endif

	<div class="row-fluid">	<!-- row 1 -->		
		<div class="col-md-6">  <!-- Column 1 -->
			<div class="form-group"> <!-- Company name -->  
				{{ Form::label('companyname', 'Company name') }}
				<p class='form-control-static'>{{ $company->companyname }}</p>				
			</div> <!-- Company name -->  
		</div>					<!-- end col 1 -->
		<div class="col-md-6">  <!-- Column 2 -->
			<div class="form-group"> <!-- address -->  
				{{ Form::label('address', 'address') }}
				<p class='form-control-static'>{{ $company->address }}</p>				
			</div> <!-- address end -->  
		</div>					<!-- end col 2 -->					
	</div>				<!-- end row 1 -->
	<div class="row-fluid">	<!-- row 2 -->
		<div class="col-md-6">  <!-- Column 1 -->
			<div class="form-group"> <!-- attchmenttypes -->  
				{{ Form::label('attachmenttype_id', 'Attachment types') }}
				{{ Form::select('attachmenttype_id', $attachmenttypes, Input::old('attachmenttype_id'),array('id' => 'attachmenttype_id', 'class' => 'form-control'))}}		
				@if ($errors->has('attachmenttype_id')) <p class="bg-danger">{{ $errors->first('attachmenttype_id') }}</p> @endif
			</div> <!-- attchmenttypes end --> 			
		</div>					<!-- end col 1 -->
		<div class="col-md-6">  <!-- Column 2 -->
			<div class="form-group"> <!-- attchmenttypes -->  
				{{ Form::label('attachmentdescription', 'Description') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->country->countryname }}</p>
				@else					
					{{ Form::text('attachmentdescription', Input::old('attachmentdescription'), array('id' => 'attachmentdescription', 'class' => 'form-control')) }}
					@if ($errors->has('attachmentdescription')) <p class="bg-danger">{{ $errors->first('attachmentdescription') }}</p> @endif
				@endif
			</div> <!-- attchmenttypes end --> 			
		</div>					<!-- end col 2 -->
	</div>				<!-- end row 2 -->
	<div class="row-fluid">	<!-- row 3 -->
		<div class="col-md-6">  <!-- Column 1 -->
			{{ Form::label('attachment', 'Attachment') }}
			<label class="btn btn-success">
				Browse
				<input type="file" name="attachment" class="form-input hidden">
				<span class="custom-file-control"></span>
			</label>
			@if ($errors->has('attachment')) <p class="bg-danger">{{ $errors->first('attachment') }}</p> @endif
		</div>					<!-- end col 1 -->
		<div class="col-md-3">  <!-- Column 2 -->
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn btn-primary fixedw_button" id="lnksubmit" type="button" title="Add attachment">
				<span class="glyphicon glyphicon-plus"></span>
			</a>
		</div>					<!-- end col 2 -->
		<div class="col-md-3">  <!-- Column 3 -->
			<a href="{{ url("/companies/view/" . $company->id) }}" class="btn btn-info fixedw_button" role="button" title="View company">
				<span class="glyphicon glyphicon-eye-open"></span>
			</a>
		</div>					<!-- end col 3 -->
	</div>				<!-- end row 3 -->
	<div class="row-fluid">	<!-- row 4 --> 
		<table id="mytable" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Attachment type</th>
					<th>Description</th>
				</tr>		
			</thead>
			<tbody>
				@foreach ($attachments as $attachment)
					<tr>
						<td>
							<a href="/companies/attachment/{{$attachment->id}}"><span class="glyphicon glyphicon-eye-open" title="View attachment"></span></a>
						</td>
						<td>{{ $attachment->attachmenttype->name}}</td>
						<td>{{ $attachment->attachmentname}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>				<!-- end row 4 -->
	<div class="row-fluid">	<!-- row 9 --> 
		@if (isset($mode))
			 <label class="custom-file">
				<input type="file" name="attachment" class="custom-file-input">
				<span class="custom-file-control"></span>
			</label>
		@endif
	</div> <!--row 9 end -->
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
			$("#frmManage").validate({
			rules: {
				attachmenttype_id: {
				required: true,
				maxlength: 60
				},
				attachmentdescription: {
				required: true,
				maxlength: 100
				},
				attachment: {
					required: true, 
					accept: "image/jpeg, image/jpg, , image/png, application/pdf"
				},
			},	
			messages: {
				attachmenttype_id: "Please select attachment type",
				attachmentdescription: "Please enter attachment description",
				attachment: "Select file",
			}
			});
			//validation end
		});
	</script>
@endpush

