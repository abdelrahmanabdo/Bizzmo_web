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
	
	<div class="row">	<!-- row 1 -->
		<div class="col-sm-6">  <!-- Column 1 -->
			<h4>Basic Info</h4>{{ Form::hidden('screen', 1, array('id' => 'screen')) }}
		</div>
	</div>
	<div class="row">	<!-- row 1 -->		
		<div class="col-sm-6">  <!-- Column 1 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- Company name -->  
				{{ Form::label('companyname', 'Company Name', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->companyname }}</p>
				@else					
					{{ Form::text('companyname', Input::old('companyname'), array('id' => 'companyname', 'class' => 'form-control')) }}								
					@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
				@endif
			</div> <!-- Company name -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-6">  <!-- Column 2 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- address -->  
				{{ Form::label('address', 'Address', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->address }}</p>
				@else					
					{{ Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control')) }}			
					@if ($errors->has('address')) <p class="bg-danger">{{ $errors->first('address') }}</p> @endif
				@endif
			</div> <!-- address end -->  
		</div>					<!-- end col 2 -->					
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 2 -->
		<div class="col-sm-4">  <!-- Column 1 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
				{{ Form::label('district', 'District', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->district }}</p>
				@else					
					{{ Form::text('district', Input::old('district'), array('id' => 'district', 'class' => 'form-control')) }}			
					@if ($errors->has('district')) <p class="bg-danger">{{ $errors->first('district') }}</p> @endif
				@endif
			</div> <!-- district end -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-4">  <!-- Column 2 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- country -->  
				{{ Form::label('country_id', 'Country', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->country->countryname }}</p>
				@else					
					{{ Form::select('country_id', $countries, Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control'))}}		
					@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
				@endif
			</div> <!-- country --> 			
		</div>					<!-- end col 2 -->
		<div class="col-sm-4">  <!-- Column 1 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- city -->  
				{{ Form::label('city_id', 'City', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->city->cityname }}</p>
				@else					
					{{ Form::select('city_id', $cities, Input::old('city_id'),array('id' => 'city_id', 'class' => 'form-control'))}}		
					@if ($errors->has('city_id')) <p class="bg-danger">{{ $errors->first('city_id') }}</p> @endif
				@endif
			</div> <!-- city --> 			
		</div>					<!-- end col 1 -->		
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 3 -->
		<div class="col-sm-3">  <!-- Column 1 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- phone -->  
				{{ Form::label('phone', 'Phone', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->phone }}</p>
				@else					
					{{ Form::text('phone', Input::old('phone'), array('id' => 'phone', 'class' => 'form-control phone', 'placeholder' => '(000) 0 0000000')) }}			
					@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
				@endif
			</div> <!-- phone end -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-3">  <!-- Column 2 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- fax -->  
				{{ Form::label('fax', 'Fax', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->fax }}</p>
				@else					
					{{ Form::text('fax', Input::old('fax'), array('id' => 'fax', 'class' => 'form-control phone', 'placeholder' => '(000) 0 0000000')) }}			
					@if ($errors->has('fax')) <p class="bg-danger">{{ $errors->first('fax') }}</p> @endif
				@endif
			</div> <!-- fax end -->  
		</div>					<!-- end col 2 -->
		<div class="col-sm-2">  <!-- Column 3 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- pobox -->  
				{{ Form::label('pobox', 'PO Box', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->pobox }}</p>
				@else					
					{{ Form::text('pobox', Input::old('pobox'), array('id' => 'pobox', 'class' => 'form-control')) }}			
					@if ($errors->has('pobox')) <p class="bg-danger">{{ $errors->first('pobox') }}</p> @endif
				@endif
			</div> <!-- pobox end -->  
		</div>					<!-- end col 3 -->
		<div class="col-sm-4">  <!-- Column 4 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- email -->  
				{{ Form::label('email', 'Email', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->email }}</p>
				@else					
					{{ Form::email('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'Placeholder' => 'Email of the contact person of the company')) }}			
					@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
				@endif
			</div> <!-- email end -->  
		</div>					<!-- end col 4 -->
	</div>				<!-- end row 3 -->
	<div class="row">	<!-- row 4 -->
		<div class="col-sm-2">  <!-- Column 1 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
				{{ Form::label('license', 'Trade License No.', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->license }}&nbsp;&nbsp;&nbsp;</p>
				@else					
					{{ Form::text('license', Input::old('license'), array('id' => 'license', 'class' => 'form-control')) }}					
					@if ($errors->has('license')) <p class="bg-danger">{{ $errors->first('license') }}</p> @endif
				@endif				
			</div> <!-- license end -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-2">  <!-- column 2 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
			{{ Form::label('tradelic', 'Trade License', array('class' => 'control-label')) }}<br>
			@if (!isset($mode))
				<a href="#" class="btn btn-success" onclick="Uploadtradefile(this);return false;" id="lnkattach" alt="Upload PDF, JPG, JPEG or PNG file that has a copy of the Trade License. Maximum file size is 2M" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Trade License. Maximum file size is 2M"><span class="glyphicon glyphicon-link"></span></a>			
				<input type="file" name="tradeattach" id="tradeattach" class="tradeattach" style="display:none;">
			@endif
			@if (old('tradefile'))
				<input name="tradefile" id="tradefile" type="hidden" value="{{ old('tradefile') }}">
				<input name="tradeattachid" id="tradeattachid" type="hidden" value="{{ old('tradeattachid') }}">
				<span id="tradefilename" name="tradefilename">{{ old('tradefile') }}</span>
			@else
				@if (isset($tradeattachment))
					<input name="tradefile" id="tradefile" type="hidden" value="{{ $tradeattachment->filename }}">
					<input name="tradeattachid" id="tradeattachid" type="hidden" value="{{ $tradeattachment->id }}">
					@if (isset($mode))
						<a href="/{{ $company->attachments->first()->path }}" download="{{ $company->attachments->first()->path }}">{{ $company->attachments->first()->filename }}</a>
					@else
						<span id="tradefilename" name="tradefilename">{{ $tradeattachment->filename }}</span>
					@endif
				@else
					<input name="tradefile" id="tradefile" type="hidden">
					<input name="tradeattachid" id="tradeattachid" type="hidden">
					<span id="tradefilename" name="tradefilename"></span>
				@endif
			@endif
			@if ($errors->has('tradefile')) <p class="bg-danger">{{ $errors->first('tradefile') }}</p> @endif			
			</div>					<!-- end col 1 -->	
		</div>					<!-- end col 2 -->
		<div class="col-sm-2">  <!-- Column 3 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- tax -->  
				{{ Form::label('tax', 'TRN', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->tax }}</p>
				@else					
					{{ Form::text('tax', Input::old('tax'), array('id' => 'tax', 'class' => 'form-control')) }}			
					@if ($errors->has('tax')) <p class="bg-danger">{{ $errors->first('tax') }}</p> @endif
				@endif
			</div> <!-- tax end -->  
		</div>					<!-- end col 3 -->
		<div class="col-sm-2">  <!-- column 4 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- incorporated -->  
				{{ Form::label('incorporated', 'Incorporation Date', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static text-right'>{{ $company->incorporated }}</p>
				@else					
					{{ Form::text('incorporated', Input::old('incorporated'), array('id' => 'incorporated', 'class' => 'form-control')) }}			
					@if ($errors->has('incorporated')) <p class="bg-danger">{{ $errors->first('incorporated') }}</p> @endif
				@endif
			</div> <!-- incorporated end -->  
		</div>					<!-- end col 4 -->		
		<div class="col-sm-4">  <!-- column 5 -->
			<div class="form-group"> <!-- website -->  
				{{ Form::label('website', 'Company Website') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->website }}</p>
				@else					
					{{ Form::text('website', Input::old('website'), array('id' => 'website', 'class' => 'form-control')) }}			
					@if ($errors->has('website')) <p class="bg-danger">{{ $errors->first('website') }}</p> @endif
				@endif
			</div> <!-- website end -->  
		</div>					<!-- end col 5 -->
	</div>				<!-- end row 4 -->
	<div class="row">	<!-- row 5 -->
		<div class="col-sm-2">  <!-- column 1 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- articles of assoc -->  
			{{ Form::label('assoclic', 'Articles Of Assoc.', array('class' => 'control-label')) }}<br>
			
			@if (!isset($mode))
				<a href="#" class="btn btn-success" onclick="Uploadassocfile(this);return false;" id="lnkattach" alt="Upload PDF, JPG, JPEG or PNG file that has a copy of the Articles Of Association. Maximum file size is 2M" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Articles Of Association. Maximum file size is 2M"><span class="glyphicon glyphicon-link"></span></a>			
				<input type="file" name="assocattach" id="assocattach" class="assocattach" style="display:none;">
			@endif
			@if (old('assocfile'))
				<input name="assocfile" id="assocfile" type="hidden" value="{{ old('assocfile') }}">
				<input name="assocattachid" id="assocattachid" type="hidden" value="{{ old('assocattachid') }}">
				<span id="assocfilename" name="assocfilename">{{ old('assocfile') }}</span>
			@else
				@if (isset($assocattachment))
					<input name="assocfile" id="assocfile" type="hidden" value="{{ $assocattachment->filename }}">
					<input name="assocattachid" id="assocattachid" type="hidden" value="{{ $assocattachment->id }}">
					@if (isset($mode))
						<a href="/{{ $assocattachment->path }}" download="{{ $assocattachment->path }}">{{ $assocattachment->filename }}</a>
					@else
						<span id="assocfilename" name="assocfilename">{{ $assocattachment->filename }}</span>
					@endif					
				@else
					<input name="assocfile" id="assocfile" type="hidden">
					<input name="assocattachid" id="assocattachid" type="hidden">
					<span id="assocfilename" name="assocfilename"></span>
				@endif
			@endif
			@if ($errors->has('assocfile')) <p class="bg-danger">{{ $errors->first('assocfile') }}</p> @endif			
			</div>					<!-- articles of assoc end -->	
		</div>					<!-- end col 1 -->
		<div class="col-sm-8">  <!-- column 2 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- operating -->  
				{{ Form::label('operating', 'Industries Operating In', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->operating }}</p>
				@else					
					{{ Form::text('operating', Input::old('operating'), array('id' => 'operating', 'class' => 'form-control', 'Placeholder' => 'Enter a comma separated list')) }}			
					@if ($errors->has('operating')) <p class="bg-danger">{{ $errors->first('operating') }}</p> @endif
				@endif				
			</div> <!-- operating end -->  
		</div>					<!-- end col 2 -->
		<div class="col-sm-2">  <!-- column 3 -->
			<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- employees -->  
				{{ Form::label('employees', 'No. Of Employees', array('class' => 'control-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $company->employeenumber->name }}</p>
				@else					
					{{ Form::select('employees', $employees, Input::old('employees'),array('id' => 'employees', 'class' => 'form-control'))}}		
					@if ($errors->has('employees')) <p class="bg-danger">{{ $errors->first('employees') }}</p> @endif
				@endif
			</div> <!-- employees end -->  
		</div>					<!-- end col 3 -->
	</div>				<!-- end row 5 -->
	
	<div class="row">	<!-- row 10 --> 
		<div class=" col-sm-12"> <!-- Column 1 -->
		@if (isset($mode))
			@if ($company->confirmed)
				@if (Gate::allows('co_cr'))
					<div class="col-xs-4"> <!-- Column 1 -->			
						<a href="{{ url("/companies/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>						
					</div> <!-- Column 1 end -->
				@endif
				@if (Gate::allows('co_sc'))
					<div class="col-xs-4"> <!-- Column 2 -->
						<a href="{{ url("/companies") }}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				@endif
				@if (Gate::allows('co_ch', $company->id))
					<div class="col-xs-4"> <!-- Column 3 -->
						<a href="{{ url("/companies/" . $company->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
					</div>
				@endif
			@else
				@if (Gate::allows('co_cr'))
					<div class="row">
					<div class="col-xs-offset-2 col-xs-2"> <!-- Column 1 -->
						{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
						<a href="" class="btn btn-primary fixedw_button" id="lnkconfirm" type="button" title="Confirm">
							<span class="glyphicon glyphicon-ok"></span>
						</a>
					</div> <!-- Column 1 end -->
					<div class="col-xs-8"> <!-- Column 1 -->
						<div class="checkbox">
						<label>
						  <input type="checkbox" name="cbconfirm" id ="cbconfirm"> I hereby confirm that the above data and attachments are correct.
						</label>
					  </div>
					</div> <!-- Column 1 end -->
					</div>
				@endif
				<div class="row">
				@if (Gate::allows('co_sc'))
					<div class="col-xs-6"> <!-- Column 2 -->
						<a href="{{ url("/companies") }}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				@endif
				@if (Gate::allows('co_ch', $company->id))
					<div class="col-xs-6"> <!-- Column 3 -->
						<a href="{{ url("/companies/" . $company->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
					</div>
				@endif
				</div>
			@endif
		@else
			<span class="red">*</span> denotes a required field.<br>
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			 <ul class="list-inline pull-right">
				<li><button type="button" class="btn btn-default prev-step disabled" id="lnknext">Previous</button></li>
				<li><button type="button" class="btn btn-default next-step" id="lnkprevious">Next</button></li>
			</ul>  
		@endif 	
		</div> <!-- Column 1 end -->
	</div> <!--row 10 end -->
	{{ Form::close() }}
@stop	
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			Updatecity();
			$("#submit").hide();
			$(".next-step").bind('click', function(e) {
				alert('aa');
				e.preventDefault();
				$("#submit").click();
			});
			$("#lnkconfirm").bind('click', function(e) {
				e.preventDefault();
				if (!$("#cbconfirm").is(':checked')) {
					alert('You must check the confirmation text.');
				} else {
					$("#submit").click();
				}
			});
			//tabs
			$(".nav-tabs a").click(function(){
				$(this).tab('show');
			});
						
			$('.nav-tabs a').on('shown.bs.tab', function(event){
				var x = $(event.target).text();         
				var y = $(event.relatedTarget).text();  
				$(".act span").text(x);
				$(".prev span").text(y);
				console.log('y: ' + y);
				$("#activetab").val(y);
			});
			
			//next prev
			$(".next-step").click(function (e) {
				var $active = $('.nav-tabs li.active');
				$active.next().removeClass('disabled');
				nextTab($active);
			});
			$(".prev-step").click(function (e) {
				var $active = $('.nav-tabs li.active');
				prevTab($active);

			});
			
			$(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
				alert('xx');
			  if ($(this).hasClass("disabled")) {
				  alert('yy');
				e.preventDefault();
				return false;
			  }
			});
			//tabs end
			var phonemask = '(000) 0 0000000';
			var mobilemask = '(000) 00 0000000';
			$('.phone').mask(phonemask);
			$('.mobile').mask(mobilemask);
			$("#country_id").change(function(){
				Updatecity();
			}); // $("#country_id").change end
			
			$( "#incorporated" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				showOtherMonths: true,
				selectOtherMonths: true,
				autoclose: true,
			});			
			//validation
			$("#frmManage1").validate({
			rules: {
				companyname: {
				required: true,
				maxlength: 60
				},
				address: {
				required: true,
				maxlength: 60
				},
				district: {
				required: true,
				maxlength: 60
				},
				phone: {
				required: true,
				digits:true,
				maxlength: 60
				},
				fax: {
				required: true,
				digits:true,
				maxlength: 60
				},
				email: {
				email: true,
				required: true,
				maxlength: 60
				},
				license: {
				required: true,
				maxlength: 60
				},
				tax: {
				required: true,
				maxlength: 60
				},
				incorporated: {
				required: true,
				maxlength: 60
				},
				employees: {
				required: true,
				digits:true,
				maxlength: 60
				},	
			},	
			messages: {
				companyname: "Company name is required, with a max length of 60 characters",
				address: "Address is required, with a max length of 60 characters",
				district: "District is required, with a max length of 60 characters",
			}
			});
			//validation end
			$("#lnkowner").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('ownertable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';							 
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelOwner" type="button"><span class="glyphicon glyphicon-trash" title="Delete shareholder"></span></a>';
				row = row + '<input name="ownerid[]" type="hidden" class="form-control">';
				row = row + '<input name="ownerdel[]" id="ownerdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="ownername[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="owneremail[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="ownerphone[]" type="text" class="form-control mobile" placeholder="(000) 00 0000000"></td>';
				row = row + '<td><input name="ownershare[]" type="text" class="form-control"></td>';
				row = row + '<td>';
				row = row + '<input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,1);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the ID. Maximum file size is 2M"></span></a>&nbsp;<span></span>';
				row = row + '<input name="owneridfile[]" id="owneridfile" type="hidden" value="">';
				row = row + '<input name="owneridattachid[]" id="owneridattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,9);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Passport. Maximum file size is 2M"></span></a>&nbsp;<span></span>												';
				row = row + '<input name="ownerpptfile[]" id="ownerpptfile" type="hidden" value="">';
				row = row + '<input name="ownerpptattachid[]" id="ownerpptattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,2);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Visa. Maximum file size is 2M"></span></a>&nbsp;<span></span>';
				row = row + '<input name="ownervisafile[]" id="ownervisafile" type="hidden" value="">';
				row = row + '<input name="ownervisaattachid[]" id="ownervisaattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<input type="hidden" name="ownerattach[]" id="ownerattach" value="0">';
				row = row + '</td>';
				row = row + '</tr>';
				$('#ownertable').append(row);
				$("#ownercount").val(parseInt($("#ownercount").val()) + 1);
				$('.mobile').mask(mobilemask);
			});
			$("#lnkbeneficial").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('beneficialtable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';							 
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelOwner" type="button"><span class="glyphicon glyphicon-trash" title="Delete shareholder"></span></a>';
				row = row + '<input name="beneficialid[]" type="hidden" class="form-control">';
				row = row + '<input name="beneficialdel[]" id="beneficialdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="beneficialname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="beneficialemail[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="beneficialphone[]" type="text" class="form-control mobile" placeholder="(000) 00 0000000"></td>';
				row = row + '<td><input name="beneficialshare[]" type="text" class="form-control"></td>';
				row = row + '<td>';
				row = row + '<input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,11);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the ID. Maximum file size is 2M"></span></a>&nbsp;<span></span>';
				row = row + '<input name="beneficialidfile[]" id="beneficialidfile" type="hidden" value="">';
				row = row + '<input name="beneficialidattachid[]" id="beneficialidattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,13);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Passport. Maximum file size is 2M"></span></a>&nbsp;<span></span>												';
				row = row + '<input name="beneficialpptfile[]" id="beneficialpptfile" type="hidden" value="">';
				row = row + '<input name="beneficialpptattachid[]" id="beneficialpptattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,12);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Visa. Maximum file size is 2M"></span></a>&nbsp;<span></span>';
				row = row + '<input name="beneficialvisafile[]" id="beneficialvisafile" type="hidden" value="">';
				row = row + '<input name="beneficialvisaattachid[]" id="beneficialvisaattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<input type="hidden" name="beneficialattach[]" id="beneficialattach" value="0">';
				row = row + '</td>';
				row = row + '</tr>';
				$('#beneficialtable').append(row);
				$("#beneficialcount").val(parseInt($("#beneficialcount").val()) + 1);
				$('.mobile').mask(mobilemask);
			});
			$("#lnkdirector").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('directortable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelPDirector" type="button"><span class="glyphicon glyphicon-trash" title="Delete director"></span></a>&nbsp;';
				row = row + '<input name="directorid[]" type="hidden" class="form-control">';
				row = row + '<input name="directordel[]" id="directordel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="directorname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="directortitle[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="directoremail[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="directorphone[]" type="text" class="form-control mobile" placeholder="(000) 00 0000000"></td>';
				row = row + '<td>';
				row = row + '<input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,3);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the ID. Maximum file size is 2M"></span></a>&nbsp;<span></span>';
				row = row + '<input name="directoridfile[]" id="directoridfile" type="hidden" value="">';
				row = row + '<input name="directoridattachid[]" id="directoridattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,10);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Passport. Maximum file size is 2M"></span></a>&nbsp;<span></span>												';
				row = row + '<input name="directorpptfile[]" id="directorpptfile" type="hidden" value="">';
				row = row + '<input name="directorpptattachid[]" id="directorpptattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<div class=" col-sm-12" style="white-space: nowrap;">';
				row = row + '<a href="#" onclick="Attachment(this,4);return false;"><span class="glyphicon glyphicon-link green" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Visa. Maximum file size is 2M"></span></a>&nbsp;<span></span>';
				row = row + '<input name="directorvisafile[]" id="directorvisafile" type="hidden" value="">';
				row = row + '<input name="directorvisaattachid[]" id="directorvisaattachid" type="hidden" value="">';
				row = row + '</div>';
				row = row + '<input type="hidden" name="directorattach[]" id="directorattach" value="0">';
				row = row + '</td>';
				row = row + '</tr>';
				$('#directortable').append(row);							
				$("#directorcount").val(parseInt($("#directorcount").val()) + 1);
				$('.mobile').mask(mobilemask);
			});
			$("#lnktopproduct").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('topproducttable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelPTopproduct" type="button"><span class="glyphicon glyphicon-trash" title="Delete brand"></span></a>';
				row = row + '<input name="topproductid[]" type="hidden" class="form-control">';
				row = row + '<input name="topproductdel[]" id="topproductdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="topproductname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="topproductrevenue[]" type="text" class="form-control"></td>';
				row = row + '</tr>';
				$('#topproducttable').append(row);							
				$("#topproductcount").val(parseInt($("#topproductcount").val()) + 1);				
			});
			$("#lnktopcustomer").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('topcustomertable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelPTopcustomer" type="button"><span class="glyphicon glyphicon-trash" title="Delete supplier"></span></a>';
				row = row + '<input name="topcustomerid[]" type="hidden" class="form-control">';
				row = row + '<input name="topcustomerdel[]" id="topcustomerdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="topcustomername[]" type="text" class="form-control"></td>';
				row = row + '</tr>';
				$('#topcustomertable').append(row);							
				$("#topsuppliercount").val(parseInt($("#topsuppliercount").val()) + 1);
			});
			$('.table').on('change', '.topproductsum', (event) => {
				var brandsum = Brandsum();
			});
			$('.table').on('change', '.selectpicker', (event) => {
				var select = event.target;
				var table = select.parentNode.parentNode.parentNode.parentNode.parentNode;
				var tr = select.parentNode.parentNode.parentNode;
				var td = select.parentNode.parentNode;
				for(var i=1; i<table.rows[0].cells.length; i+=1){
				switch (table.rows[0].cells[i].innerHTML) {
					case 'Attachment':
						var attachmentcell = i;
						break;
					}
				}
				var inputs = tr.cells[attachmentcell].getElementsByTagName("input");
				var inputslength = inputs.length;
				inputs[0].click();
			});
			$('.table').on('change', '.attach', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('Maximum file size should be 2M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
					alert('Only PDF, JPEG, JPG, PNG files are allowed');
					return false;
				}				
				var table =fileInput.parentNode.parentNode.parentNode.parentNode;
				var tr = fileInput.parentNode.parentNode;
				var td = fileInput.parentNode;
				for(var i=1; i<table.rows[0].cells.length; i+=1){
					//alert(table.rows[0].cells[i].innerHTML.substr(0,10));
					switch (table.rows[0].cells[i].innerHTML.substr(0,10)) {
						case 'Attachment':
							var attachmentcell = i;
							break;
					}
				}
				
				ownerupload = document.getElementById("ownerupload");
				beneficialupload = document.getElementById("beneficialupload");
				directorupload = document.getElementById("directorupload");
				var formData = new FormData;
					formData.append('attach', file);
					formData.append('_token', $('input[name=_token]').val());
                $.ajax({					
                    url: '/attach',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response){
						console.log(filename);				
						if (ownerupload.value == 1) {
							tr.cells[attachmentcell].getElementsByTagName("input")[1].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[2].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[0].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[0].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[1].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[1].innerText = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[7].value = parseInt(tr.cells[attachmentcell].getElementsByTagName("input")[7].value) + 1;
						} else if (ownerupload.value == 9) {
							tr.cells[attachmentcell].getElementsByTagName("input")[3].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[4].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[2].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[2].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[3].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[3].innerText = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[7].value = parseInt(tr.cells[attachmentcell].getElementsByTagName("input")[7].value) + 1;
						} else if (ownerupload.value == 2) {
							tr.cells[attachmentcell].getElementsByTagName("input")[5].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[6].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[4].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[4].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[5].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[5].innerText = filename;
						} else if (beneficialupload.value == 11) {
							tr.cells[attachmentcell].getElementsByTagName("input")[1].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[2].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[0].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[0].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[1].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[1].innerText = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[7].value = parseInt(tr.cells[attachmentcell].getElementsByTagName("input")[7].value) + 1;
						} else if (beneficialupload.value == 13) {
							tr.cells[attachmentcell].getElementsByTagName("input")[3].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[4].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[2].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[2].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[3].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[3].innerText = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[7].value = parseInt(tr.cells[attachmentcell].getElementsByTagName("input")[7].value) + 1;
						} else if (beneficialupload.value == 12) {
							tr.cells[attachmentcell].getElementsByTagName("input")[5].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[6].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[4].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[4].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[5].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[5].innerText = filename;
						} else if (directorupload.value == 3) {
							tr.cells[attachmentcell].getElementsByTagName("input")[1].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[2].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[0].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[0].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[1].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[1].innerText = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[7].value = parseInt(tr.cells[attachmentcell].getElementsByTagName("input")[7].value) + 1;
						} else if (directorupload.value == 10) {
							tr.cells[attachmentcell].getElementsByTagName("input")[3].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[4].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[2].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[2].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[3].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[3].innerText = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[7].value = parseInt(tr.cells[attachmentcell].getElementsByTagName("input")[7].value) + 1;
						} else if (directorupload.value == 4) {
							tr.cells[attachmentcell].getElementsByTagName("input")[5].value = filename;
							tr.cells[attachmentcell].getElementsByTagName("input")[6].value = response;
							tr.cells[attachmentcell].getElementsByTagName("span")[4].className = 'glyphicon glyphicon-remove red';
							tr.cells[attachmentcell].getElementsByTagName("span")[4].title = 'Delete';
							tr.cells[attachmentcell].getElementsByTagName("span")[5].className = '';
							tr.cells[attachmentcell].getElementsByTagName("span")[5].innerText = filename;
						}
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			}); //$('.table').on('change', '.attach', () => {
			
			$('#tradeattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('Maximum file size should be 2M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
					alert('Only PDF, JPEG, JPG, PNG files are allowed');
					return false;
				}
				var formData = new FormData;
					formData.append('attach', file);
					formData.append('_token', $('input[name=_token]').val());
                $.ajax({					
                    url: '/attach',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response){
						console.log(filename);						
						$('#tradefile').val(filename);
						$('#tradefilename').text(filename);
						$('#tradeattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			}); //$('#tradeattach').on('change', (event) => {
			
			$('#assocattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('Maximum file size should be 2M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
					alert('Only PDF, JPEG, JPG, PNG files are allowed');
					return false;
				}
				var formData = new FormData;
					formData.append('attach', file);
					formData.append('_token', $('input[name=_token]').val());
                $.ajax({					
                    url: '/attach',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response){
						console.log(filename);						
						$('#assocfile').val(filename);
						$('#assocfilename').text(filename);
						$('#assocattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			}); //$('#assocattach').on('change', (event) => {
		});
		function DelRow(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
					var inputval = inputs[j].id;                
					if (inputval == 'ownerdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';
						tr.cells[2].getElementsByTagName("input")[0].value='A@a.a';
						tr.cells[3].getElementsByTagName("input")[0].value='A';
						tr.cells[4].getElementsByTagName("input")[0].value='0';
						tr.cells[5].getElementsByTagName("input")[1].value='A';
						$("#ownercount").val(parseInt($("#ownercount").val()) - 1);
						tr.cells[5].getElementsByTagName("input")[7].value=1 ;
					} else if (inputval == 'beneficialdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';
						tr.cells[2].getElementsByTagName("input")[0].value='A@a.a';
						tr.cells[3].getElementsByTagName("input")[0].value='A';
						tr.cells[4].getElementsByTagName("input")[0].value='0';
						tr.cells[5].getElementsByTagName("input")[1].value='A';
						$("#beneficialcount").val(parseInt($("#beneficialcount").val()) - 1);
						tr.cells[5].getElementsByTagName("input")[7].value=1 ;
					} else if (inputval == 'directordel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						tr.cells[2].getElementsByTagName("input")[0].value='A';
						tr.cells[3].getElementsByTagName("input")[0].value='A@a.a';
						tr.cells[4].getElementsByTagName("input")[0].value='A';
						tr.cells[5].getElementsByTagName("input")[1].value='A';
						$("#directorcount").val(parseInt($("#directorcount").val()) - 1);
						tr.cells[5].getElementsByTagName("input")[7].value=1 ;
					} else if (inputval == 'topproductdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						//tr.cells[2].getElementsByTagName("input")[0].value='0';
						$("#topproductcount").val(parseInt($("#topproductcount").val()) - 1);
					} else if (inputval == 'topcustomerdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						//tr.cells[2].getElementsByTagName("input")[0].value='0';
						$("#topsuppliercount").val(parseInt($("#topsuppliercount").val()) - 1);
					}
					
				}
			tr.style.display = 'none';				
		}
		function Attachment(lnk, atttype) {
			var table =lnk.parentNode.parentNode.parentNode.parentNode.parentNode;
			var tr = lnk.parentNode.parentNode.parentNode;
			var td = lnk.parentNode.parentNode;
			var spans = td.getElementsByTagName("span");
			var as = td.getElementsByTagName("a");
			var inputs = td.getElementsByTagName("input");
			if (atttype == 1) {
				if (spans[1].innerText == '') {
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = 1;
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = '';
					directorupload = document.getElementById("directorupload");
					directorupload.value = '';
					inputs[0].click();
				} else {					
					spans[0].className  = 'glyphicon glyphicon-link green';
					spans[0].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the ID. Maximum file size is 2M';
					spans[1].innerText  = '';
					inputs[1].value = '';
					inputs[2].value = '';
					inputs[7].value = parseInt(inputs[7].value) - 1;
				}
			} else if (atttype == 9) {
				if (spans[3].innerText == '') {
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = 9;
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = '';
					directorupload = document.getElementById("directorupload");
					directorupload.value = '';
					inputs[0].click();
				} else {					
					spans[2].className  = 'glyphicon glyphicon-link green';
					spans[2].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the Passport. Maximum file size is 2M';
					spans[3].innerText  = '';
					inputs[3].value = '';
					inputs[4].value = '';
					inputs[7].value = parseInt(inputs[7].value) - 1;
				}
			} else if (atttype == 2) {
				if (spans[5].innerText == '') {
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = 2;
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = '';
					directorupload = document.getElementById("directorupload");
					directorupload.value = '';
					inputs[0].click();
				} else {					
					spans[4].className  = 'glyphicon glyphicon-link green';
					spans[4].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the Visa. Maximum file size is 2M';
					spans[5].innerText  = '';
					inputs[5].value = '';
					inputs[6].value = '';
				}
			} else if (atttype == 11) {
				if (spans[1].innerText == '') {
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = 11;
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = '';					
					directorupload = document.getElementById("directorupload");
					directorupload.value = '';
					inputs[0].click();
				} else {					
					spans[0].className  = 'glyphicon glyphicon-link green';
					spans[0].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the ID. Maximum file size is 2M';
					spans[1].innerText  = '';
					inputs[1].value = '';
					inputs[2].value = '';
					inputs[7].value = parseInt(inputs[7].value) - 1;
				}
			} else if (atttype == 13) {
				if (spans[3].innerText == '') {
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = 13;
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = '';					
					directorupload = document.getElementById("directorupload");
					directorupload.value = '';
					inputs[0].click();
				} else {					
					spans[2].className  = 'glyphicon glyphicon-link green';
					spans[2].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the Passport. Maximum file size is 2M';
					spans[3].innerText  = '';
					inputs[3].value = '';
					inputs[4].value = '';
					inputs[7].value = parseInt(inputs[7].value) - 1;
				}
			} else if (atttype == 12) {
				if (spans[5].innerText == '') {
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = 12;
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = '';					
					directorupload = document.getElementById("directorupload");
					directorupload.value = '';
					inputs[0].click();
				} else {					
					spans[4].className  = 'glyphicon glyphicon-link green';
					spans[4].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the Visa. Maximum file size is 2M';
					spans[5].innerText  = '';
					inputs[5].value = '';
					inputs[6].value = '';
				}
			} else if (atttype == 3) {
				if (spans[1].innerText == '') {
					directorupload = document.getElementById("directorupload");
					directorupload.value = 3;
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = '';
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = '';
					inputs[0].click();
				} else {					
					spans[0].className  = 'glyphicon glyphicon-link green';
					spans[0].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the ID. Maximum file size is 2M';
					spans[1].innerText  = '';
					inputs[1].value = '';
					inputs[2].value = '';
					inputs[7].value = parseInt(inputs[7].value) - 1;
				}
			} else if (atttype == 10) {
				if (spans[3].innerText == '') {
					directorupload = document.getElementById("directorupload");
					directorupload.value = 10;
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = '';
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = '';
					inputs[0].click();
				} else {					
					spans[2].className  = 'glyphicon glyphicon-link green';
					spans[2].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the Passport. Maximum file size is 2M';
					spans[3].innerText  = '';
					inputs[3].value = '';
					inputs[4].value = '';
					inputs[7].value = parseInt(inputs[7].value) - 1;
				}
			} else if (atttype == 4) {
				if (spans[5].innerText == '') {
					directorupload = document.getElementById("directorupload");
					directorupload.value = 4;
					ownerupload = document.getElementById("ownerupload");
					ownerupload.value = '';
					beneficialupload = document.getElementById("beneficialupload");
					beneficialupload.value = '';
					inputs[0].click();
				} else {					
					spans[4].className  = 'glyphicon glyphicon-link green';
					spans[4].title = 'Upload PDF, JPG, JPEG or PNG file that has a copy of the Visa. Maximum file size is 2M';
					spans[5].innerText  = '';
					inputs[5].value = '';
					inputs[6].value = '';
				}
			}
			return false;
		}
		function Uploadtradefile(lnk) {			
			$("#tradeattach").click();
		}
		function Uploadassocfile(lnk) {			
			$("#assocattach").click();
		}
		function Updatecity () {
			var url = '/countries/cities';
				// ajax call
				$('#city_id').find('option').remove().end();
				$.ajax({
					url: url,
					type:'post',
					data: {
						'country_id':$('select[name=country_id]').val(),
						'_token': $('input[name=_token]').val()
					},
					cache: false,
					success: function(data){
						var j = 0;
						$.each(data, function(i, item) {
							if (j == 0) {
								$('#city_id').append($("<option></option>").attr("value", i).text(item).attr("selected", true));
							} else {
								$('#city_id').append($("<option></option>").attr("value", i).text(item)).attr("selected", false);
							}
							console.log(j);
							j = j + 1;							
						});
					}, // End of success function of ajax form
					error: function(output_string){				
						alert(jxhr.responseText);
					}
				}); //ajax call end
		}
		function Brandsum () {
			var table = document.getElementById('topproducttable');
			var rowLength = table.rows.length;
			var revenue = 0;		
			if ($("#topproducttable tr:visible").length > 1) {
				var row = table.rows[0];
				for(var i=1; i<row.cells.length; i+=1){
					//alert(row.cells[i].innerHTML.substr(0,7));
					switch (row.cells[i].innerHTML.substr(0,7)) {
						case 'Revenue':
							var revenuecell = i;
							break;
					}
				}
				for (var i = 1; i < rowLength; i += 1){
					var row = table.rows[i];
					//alert(row.style.display);
					if (row.style.display != 'none') {
						//alert(parseInt(row.cells[revenuecell].getElementsByTagName("select")[0].value));
						var revenue = parseInt(revenue) + parseInt(row.cells[revenuecell].getElementsByTagName("input")[0].value);
					}
				}		
			}
			$("#topproductsum").val(revenue);
			return true;
		}
		function nextTab(elem) {
			$(elem).next().find('a[data-toggle="tab"]').click();
		}
		function prevTab(elem) {
			$(elem).prev().find('a[data-toggle="tab"]').click();
		}
	</script>
@endpush