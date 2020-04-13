@extends('layouts.app') 
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if (isset($company)) 
		{{ Form::model($company, array('id' => 'frmManage', 'files' => true, 'class' => 'cr-decision-form')) }}
		{{ Form::hidden('company_id', $company->id, array('id' => 'company_id')) }}
		{{ Form::hidden('requesttype_id', $requesttype_id, array('id' => 'requesttype_id')) }}
	@else
		@if (isset($creditrequest)) 
			{{ Form::model($creditrequest, array('id' => 'frmManage', 'files' => true, 'class' => 'cr-decision-form')) }}
		@else
			{{ Form::open(array('id' => 'frmManage', 'files' => true, 'class' => 'cr-decision-form')) }}
		@endif		
		{{ Form::hidden('requesttype_id', $creditrequest->requesttype_id, array('id' => 'requesttype_id')) }}
	@endif
	
	@php $showbusref = 1; @endphp
	@if (isset($creditrequest))
		@if ($creditrequest->requesttype_id == 2)
			@php $showbusref = 0; @endphp
		@endif
	@else
		@if (isset($requesttype_id))
			@if ($requesttype_id == 2)
				@php $showbusref = 0; @endphp
			@endif
		@endif
	@endif
	@if (isset($mode))
		@if ($mode == 'a')
			<div class="row">	<!-- row 14 -->
				<div class="col-md-3">  <!-- column 1 -->
					<div class="form-group"> <!-- limit -->  
						{{ Form::label('limit', 'Approved Limit', ['class' => 'label-view']) }}
						{{ Form::text('limit', Input::old('limit'), array('id' => 'limit', 'class' => 'form-control')) }}								
						@if ($errors->has('limit')) <p class="bg-danger">{{ $errors->first('limit') }}</p> @endif
					</div> <!-- limit end -->				
				</div>					<!-- end col 1 -->
				<div class="col-md-3">  <!-- column 2 -->
					{{ Form::label('margindeposittype_id', 'Security deposit type', ['class' => 'label-view']) }}
					{{ Form::select('margindeposittype_id', $margindeposittypes, Input::old('margindeposittype_id'),array('id' => 'margindeposittype_id', 'class' => 'form-control bm-select'))}}		
					@if ($errors->has('margindeposittype_id')) <p class="bg-danger">{{ $errors->first('margindeposittype_id') }}</p> @endif
				</div>					<!-- end col 2 -->
				<div class="col-md-3">  <!-- column 3 -->
					<div class="form-group"> <!-- Security % -->  
						{{ Form::label('margindepositvalue', 'Security deposit %', ['class' => 'label-view']) }}
						{{ Form::text('margindepositvalue', Input::old('margindepositvalue'), array('id' => 'margindepositvalue', 'class' => 'form-control')) }}								
						@if ($errors->has('margindepositvalue')) <p class="bg-danger">{{ $errors->first('margindepositvalue') }}</p> @endif
					</div> <!-- Security % end -->				
				</div>					<!-- end col 3 -->
				<div class="col-md-3">  <!-- column 4 -->
					<div class="form-group"> <!-- Tenor -->  
						{{ Form::label('tenor_id', 'Max tenor', ['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $creditrequest->tenor->name }}</p>
					</div> <!-- Tenor end -->				
				</div>					<!-- end col 4 -->				
			</div>				<!-- end row 13 -->		
			
			
			<div class="row">	<!-- row 17 -->	
				<div class="col-md-6 col-sm-12">  <!-- column 1 -->
					<div class="form-group"> <!-- securitytype_id -->  
						{{ Form::label('securitytype_id', 'Security type', ['class' => 'label-view d-block']) }}
						{{ Form::select('securitytype_id', $securitytypes, Input::get('securitytype_id'),array('id' => 'securitytype_id', 'class' => 'form-control bm-select select-with-icon'))}}		
						<a href="" id="lnksecuritytype" role="button" class="add-icon" style="margin-top: 7px" title="Add security type"></a>	
						@if ($errors->has('securitytype_id')) <p class="bg-danger">{{ $errors->first('securitytype_id') }}</p> @endif
					</div>
				</div>
			</div>				<!-- end row 17 -->
			<div class="row">	<!-- row 18 -->
				<table id="listtable" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th class="col-sm-1 no-sort">
								&nbsp;
							</th>
							<th class="col-sm-1 no-sort">Security type</th>
							<th class="col-sm-2 no-sort">Signer name</th>
							<th class="col-sm-2 no-sort">Signer email</th>
							<th class="col-sm-2 no-sort">Passport No.</th>
							<th class="col-sm-2 no-sort">Country</th>
							<th class="col-sm-2 no-sort">Value</th>
						</tr>		
					</thead>
					<tbody>
						@if (old('id'))
							@php
								$i = 0;
							@endphp
							@foreach (old('id') as $item)
								<tr>
									<td>
										<a href="#" class="btn fixedw_button" onclick="delst(this);return false;" id="btnDelpt" type="button" title="Delete payment term"><span class="delete-icon"></span></a>
										<input name="id[]" type="hidden" value="{{ old('id')[$i] }}">
										<input name="securitytype_id[]" type="hidden" value="{{ old('securitytype_id')[$i] }}">
										<input name="securitytypedel[]" id="securitytypedel" type="hidden" value="{{ old('securitytypedel')[$i] }}">										
									</td>
									<td>
										{{ Form::hidden('st_name[]', old('st_name')[$i], array('id' => 'st_name')) }}
										{{ old('st_name')[$i] }}
									</td>
									<td>
										{{ Form::text('signername[]', old('signername')[$i], array('id' => 'signername', 'class' => 'form-control')) }}
										@if ($errors->has('signername.' . $i)) <p class="bg-danger">{{ $errors->first('signername.' . $i) }}</p> @endif
									</td>
									<td>
										{{ Form::text('signeremail[]', old('signeremail')[$i], array('id' => 'signeremail', 'class' => 'form-control')) }}
										@if ($errors->has('signeremail.' . $i)) <p class="bg-danger">{{ $errors->first('signeremail.' . $i) }}</p> @endif
									</td>
									@if (old('securitytype_id')[$i] == 4 || old('securitytype_id')[$i] == 6)
										<td>{{ Form::hidden('passportno[]', '1', array('id' => 'passportno')) }}</td>
										<td>{{ Form::hidden('country_id[]', '1', array('id' => 'country_id')) }}
										{{ Form::hidden('company_name[]', '1', array('id' => 'company_name')) }}
										{{ Form::hidden('commercial_register[]', '1', array('id' => 'commercial_register')) }}
										{{ Form::hidden('address[]', '1', array('id' => 'address')) }}
										{{ Form::hidden('company_owner[]', '1', array('id' => 'company_owner')) }}
										{{ Form::hidden('designation[]', '1', array('id' => 'designation')) }}</td>
										<td>
											{{ Form::text('amount[]', old('amount')[$i], array('id' => 'amount', 'class' => 'form-control')) }}
											@if ($errors->has('amount.' . $i)) <p class="bg-danger">{{ $errors->first('amount.' . $i) }}</p> @endif
											{{ Form::text('pickupdate[]', old('pickupdate')[$i], array('id' => 'pickupdate', 'class' => 'form-control datepicker', 'Placeholder' => 'Pickup date')) }}											
											@if ($errors->has('pickupdate.' . $i)) <p class="bg-danger">{{ $errors->first('pickupdate.' . $i) }}</p> @endif
											{{ Form::select('pickuptime_id[]', $timelist->pluck('name', 'id'), Input::old('pickuptime_id'),array('id' => 'pickuptime_id', 'class' => 'form-control bm-select'))}}		
											@if ($errors->has('pickuptime_id')) <p class="bg-danger">{{ $errors->first('pickuptime_id') }}</p> @endif
										</td>
									@elseif (old('securitytype_id')[$i] == 5)
										<td colspan="3">{{ Form::hidden('passportno[]', '1', array('id' => 'passportno')) }}
										{{ Form::select('country_id[]', $countries->pluck('countryname', 'id'), Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		
										@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
									
										{{ Form::text('company_name[]', old('company_name')[$i], array('id' => 'company_name', 'class' => 'form-control')) }}
										@if ($errors->has('company_name.' . $i)) <p class="bg-danger">{{ $errors->first('company_name.' . $i) }}</p> @endif

										{{ Form::text('commercial_register[]', old('commercial_register')[$i], array('id' => 'commercial_register', 'class' => 'form-control')) }}
										@if ($errors->has('commercial_register.' . $i)) <p class="bg-danger">{{ $errors->first('commercial_register.' . $i) }}</p> @endif

										{{ Form::text('address[]', old('address')[$i], array('id' => 'address', 'class' => 'form-control')) }}
										@if ($errors->has('address.' . $i)) <p class="bg-danger">{{ $errors->first('address.' . $i) }}</p> @endif

										{{ Form::hidden('company_owner[]', '1', array('id' => 'company_owner')) }}

										{{ Form::text('designation[]', old('designation')[$i], array('id' => 'designation', 'class' => 'form-control')) }}
										@if ($errors->has('designation.' . $i)) <p class="bg-danger">{{ $errors->first('designation.' . $i) }}</p> @endif
										{{ Form::hidden('amount[]', '1', array('id' => 'amount')) }}
										{{ Form::hidden('pickupdate[]', '1/1/2000', array('id' => 'pickupdate')) }}
										{{ Form::hidden('pickuptime_id[]', '1', array('id' => 'pickuptime_id')) }}
										</td>
									@else
										@if (old('securitytype_id')[$i] == 3)
											<td>{{ Form::hidden('passportno[]', '1', array('id' => 'passportno')) }}</td>
											<td>{{ Form::hidden('country_id[]', '1', array('id' => 'country_id')) }}</td>
										@else
											<td>
												{{ Form::text('passportno[]', old('passportno')[$i], array('id' => 'passportno', 'class' => 'form-control')) }}
												@if ($errors->has('passportno.' . $i)) <p class="bg-danger">{{ $errors->first('passportno.' . $i) }}</p> @endif
											</td>
											<td>
												{{ Form::select('country_id[]', $countries->pluck('countryname', 'id'), Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		
												@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
											</td>
										@endif
										<td>{{ Form::hidden('amount[]', '1', array('id' => 'amount')) }}
										{{ Form::hidden('company_name[]', '1', array('id' => 'company_name')) }}
										{{ Form::hidden('commercial_register[]', '1', array('id' => 'commercial_register')) }}
										{{ Form::hidden('address[]', '1', array('id' => 'address')) }}
										{{ Form::hidden('company_owner[]', '1', array('id' => 'company_owner')) }}
										{{ Form::hidden('designation[]', '1', array('id' => 'designation')) }}
										{{ Form::hidden('pickupdate[]', '1/1/2000', array('id' => 'pickupdate')) }}
										{{ Form::hidden('pickuptime_id[]', '1', array('id' => 'pickuptime_id')) }}
										</td>
									@endif
								</tr>
								@php
									$i++;
								@endphp	
							@endforeach
						@else	
							@foreach ($creditrequest->securities as $security)
								<tr>
									<td>
										<a href="#" class="btn fixedw_button" onclick="delst(this);return false;" id="btnDelpt" type="button" title="Delete security check"><span class="delete-icon"></span></a>
										<input name="id[]" type="hidden" value="{{ $security->id }}">
										<input name="securitytype_id[]" type="hidden" value="{{ $security->securitytype_id }}">
										<input name="securitytypedel[]" id="securitytypedel" type="hidden" value="">
									</td>
									<td>
										<input name="st_name[]" type="hidden" value="{{ $security->securitytype->name }}">
										{{ $security->name }}
									</td>
									<td>
										{{ Form::text('signername[]', '', array('id' => 'signername', 'class' => 'form-control')) }}
									</td>
									<td>
										{{ Form::text('signeremail[]', '', array('id' => 'signeremail', 'class' => 'form-control')) }}
									</td>
									@if ($security->securitytype_id == 4)
										<td>
											{{ Form::text('amount[]', '', array('id' => 'amount', 'class' => 'form-control')) }}
										</td>
									@else										
										<td>{{ Form::hidden('amount[]', '1', array('id' => 'amount')) }}</td>
									@endif
								</tr>	
							@endforeach			
						@endif
					</tbody>
				</table>
			</div>				<!-- end row 18 -->
		@else
			@if ($creditrequest->creditstatus_id == 1 || $creditrequest->creditstatus_id == 3)
				<div class="row">	<!-- row 18 -->
					<div class="col-md-4">  <!-- column 1 -->
						<div class="form-group"> <!-- limit -->  
							{{ Form::label('limit', 'Approved Limit') }}
							<p class='form-control-static'>{{ number_format($creditrequest->limit, 2, '.', ',') }}</p>
						</div> <!-- limit end -->				
					</div> 	  <!-- column 1 end -->
					<div class="col-md-4">  <!-- column 2 -->
						<div class="form-group"> <!-- limit -->  
							{{ Form::label('pricingmargin', 'Pricing margin') }}
							<p class='form-control-static'>{{ number_format($creditrequest->pricingmargin, 2, '.', ',') }}</p>
						</div> <!-- limit end -->				
					</div> 	  <!-- column 2 end -->
					<div class="col-md-4">  <!-- column 3 -->
						<div class="form-group"> <!-- limit -->  
							{{ Form::label('paymentterm', 'Payment term (days)') }}
							<p class='form-control-static'>{{ $creditrequest->paymentterm }}</p>
						</div> <!-- limit end -->				
					</div> 	  <!-- column 3 end -->
				</div>				<!-- end row 18 -->
			@endif
		@endif
	@endif
	<div class="row">	<!-- row 19 --> 
		<div class=" col-md-12"> <!-- column 1 -->
		@if (isset($mode))
			@if ($mode == 'a')
			<div class="col-xs-12 btn-container">
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn bm-btn blue fixedw_button hidden')) }}
				<a href="" class="btn bm-btn green fixedw_button" id="lnksubmit" type="button" title="Approve">Approve</span>
				</a>
				<a href="{{ url("/creditrequests/reject/" . $creditrequest->id) }}" class="btn bm-btn red fixedw_button" role="button" title="Reject">Reject</span>
				</a>
			</div>
			@elseif ($mode == 's')
				<a href="{{ url("/creditrequests/view/" . $creditrequest->id) }}" class="btn bm-btn blue fixedw_button" role="button" title="Save">Save</a>
			@else			
				@if (Gate::allows('cr_cr'))
					<div class="col-xs-3"> <!-- column 1 -->			
						<a href="{{ url("/creditrequests/create") }}" class="btn bm-btn blue fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus">Create</span></a>										
					</div> <!-- column 1 end -->
				@endif
				@if (Gate::allows('cr_sc'))
					<div class="col-xs-3"> <!-- column 2 -->
						<a href="{{ url("/creditrequests") }}" class="btn btn-info bm-btn fixedw_button" role="button" title="Search">Search</span></a>
					</div>
				@endif
				@if (Gate::allows('cr_ch', $creditrequest->id) && ($creditrequest->creditstatus_id == 2 || $creditrequest->creditstatus_id == 4 || $creditrequest->creditstatus_id == 6))
					<div class="col-xs-3"> <!-- column 3 -->
						@if ($creditrequest->personalguarantee || $creditrequest->corporateguarantee || $creditrequest->promissarynote || $creditrequest->securitycheck)
						@else
							<a href="{{ url("/creditrequests/" . $creditrequest->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit">Edit</span></a>
						@endif
					</div>
				@endif
				@if (Gate::allows('cr_ap') && $creditrequest->creditstatus_id == 2)
					<div class="col-xs-3"> <!-- column 4 -->
						<a href="{{ url("/creditrequests/approve/" . $creditrequest->id) }}" class="btn bm-btn blue fixedw_button" role="button" title="Approval">Approve</span></a>
					</div>
				@endif				
			@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn bm-btn blue fixedw_button hidden')) }}
			<a href="" class="btn bm-btn blue fixedw_button" id="lnksubmit" type="button" title="Save">Save</a>
		@endif 	
		</div> <!-- column 1 end -->
	</div> <!--row 19 end -->
	@if (isset($mode))
		@if ($mode == 'v' && Gate::allows('cr_cr') && $creditrequest->appointment_id == null)
			<div class="row">	<!-- row 20 -->
				<div class="col-sm-12"> <!-- Column 1 -->
					<div class="alert alert-danger">
						<p class="bg-danger"><strong>@lang('messages.needvisit')</strong></p>
						<p class="bg-danger">Click <a href="/calendar/create/{{ $creditrequest->id }}">here</a>@lang('messages.needvisitmsg')</p>
					</div>
				</div> <!-- Column 1 end -->
			</div> <!--row 20 end -->
		@endif
	@endif
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
			
			$('.datepicker').datepicker({ 
				format: "d/m/yyyy",
				startDate: "0d",
				showOtherMonths: true,
				selectOtherMonths: true,
				autoclose: true,
			})
			
			//lnksecuritytype
			$("#lnksecuritytype").bind('click', function(e) {
				e.preventDefault();				
				var table = document.getElementById('listtable');
				var rowLength = table.rows.length;

				if(rowLength > 1)
					$(".dataTables_empty").hide();

				var row = '<tr>';							
				row = row + '<td>';							 
				row = row + '<a href="#" class="btn fixedw_button" onclick="delst(this);return false;" id="btnDelst" type="button"><span class="delete-icon"></span></span></a>';
				row = row + '<input name="id[]" type="hidden" class="form-control">';
				row = row + '<input name="securitytype_id[]" type="hidden" value="' + $('#securitytype_id option:selected').val() + '">';
				row = row + '<input name="securitytypedel[]" id="securitytypedel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td>';
				row = row + '<input name="st_name[]" type="hidden" value="' + $('#securitytype_id option:selected').text() + '">';
				row = row + $('#securitytype_id option:selected').text();
				row = row + '</td>';
				if ($('#securitytype_id option:selected').val() == 4 || $('#securitytype_id option:selected').val() == 6) {
					row = row + '<td><input name="signername[]" type="text" value="" class="form-control"></td>';
					row = row + '<td><input name="signeremail[]" type="text" value="" class="form-control"></td>';
					row = row + '<td><input name="passportno[]" type="hidden" value="1"></td>';
					row = row + '<td><input name="country_id[]" type="hidden" value="1"></td>';
					row = row + '<td><input name="amount[]" type="text" value="" class="form-control">';
					row  = row + '<input name="company_name[]" type="hidden" value="">';
					row  = row + '<input name="commercial_register[]" type="hidden" value="">';
					row  = row + '<input name="address[]" type="hidden" value="">';
					row  = row + '<input name="company_owner[]" type="hidden" value="">';
					row  = row + '<input name="designation[]" type="hidden" value="">';
					row = row + '<input name="pickupdate[]" type="text" value="" class="form-control datepicker" placeholder="Pickup date">';
					row = row + '<select name="pickuptime_id[]" class="form-control">';
					<?php
						if (isset($timelist)) {
							foreach ($timelist as $times) {
								echo "row = row + '<option value=" . $times->id .">" .str_replace("'", " ", $times->name) . "</option>';";
							}
						}
					?>
					row = row + '</select>';
					row = row + '</td>';
				}else if ($('#securitytype_id option:selected').val() == 5) {
					row = row + '<td><input name="signername[]" type="text" value="" class="form-control"></td>';
					row = row + '<td><input name="signeremail[]" type="text" value="" class="form-control"></td>';
					row = row + '<td colspan="3"><input name="passportno[]" type="hidden" value="1">';
					row = row + '<input name="amount[]" type="hidden" value="1">';
					row = row + '<select name="country_id[]" class="form-control">';
					<?php
						if (isset($countries)) {
							foreach ($countries as $country) {
								echo "row = row + '<option value=" . $country->id .">" .str_replace("'", " ", $country->countryname) . "</option>';";
							}
						}
					?>
					row = row + '</select>';
					row = row + '<input name="company_name[]" type="text" value="" class="form-control" placeholder="Company Name">';
					row = row + '<input name="commercial_register[]" type="text" value="" class="form-control" placeholder="Commercial Register">';
					row = row + '<input name="address[]" type="text" value="" class="form-control" placeholder="Address">';
					row = row + '<input name="company_owner[]" type="hidden" value="" class="form-control" placeholder="Representative">';
					row = row + '<input name="designation[]" type="text" value="" class="form-control" placeholder="Designation">';
					row  = row + '<input name="pickupdate[]" type="hidden" value="1/1/2000">';
					row  = row + '<input name="pickuptime_id[]" type="hidden" value="1"></td>';
				} else {
					row = row + '<td><input name="signername[]" type="text" value="" class="form-control"></td>';
					row = row + '<td><input name="signeremail[]" type="text" value="" class="form-control"></td>';
					if ($('#securitytype_id option:selected').val() == 3) {
						row = row + '<td><input name="passportno[]" type="hidden" value="1"></td>';
						row = row + '<td><input name="country_id[]" type="hidden" value="1"></td>';
					} else {
						row = row + '<td><input name="passportno[]" type="text" value="" class="form-control"></td>';
						
						
						row = row + '<td><select name="country_id[]" class="form-control">';
						<?php
							if (isset($countries)) {
								foreach ($countries as $country) {
									echo "row = row + '<option value=" . $country->id .">" .str_replace("'", " ", $country->countryname) . "</option>';";
								}
							}
						?>
						row = row + '</select></td>';
						
					}					
					row = row + '<td><input name="amount[]" type="hidden" value="1">';
					row  = row + '<input name="company_name[]" type="hidden" value="">';
					row  = row + '<input name="commercial_register[]" type="hidden" value="">';
					row  = row + '<input name="address[]" type="hidden" value="">';
					row  = row + '<input name="company_owner[]" type="hidden" value="">';
					row  = row + '<input name="designation[]" type="hidden" value="">';
					row  = row + '<input name="pickupdate[]" type="hidden" value="1/1/2000">';
					row  = row + '<input name="pickuptime_id[]" type="hidden" value="1"></td>';
				}				
				row = row + '</tr>';
				$('#listtable').append(row);
				$('.datepicker').datepicker({ 
					format: "d/m/yyyy",
					startDate: "0d",
					showOtherMonths: true,
					selectOtherMonths: true,
					autoclose: true,
				})
			});
			//lnksecuritytype end						
			$('#securitycheckattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('Maximum file size is 2M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
					alert('Only PDF, JPEG, JPG, PNG files are allowed');
					return false;
				}
				var cr_id ='';				
				var formData = new FormData;
					formData.append('attach', file);
					formData.append('filename', filename);
					formData.append('_token', $('input[name=_token]').val());
					@if (isset($creditrequest))
						@php
							echo "cr_id = " . $creditrequest->id . ";";
							echo "formData.append('cr_id', " . $creditrequest->id . ");";
						@endphp
					@endif
                $.ajax({					
                    url: '/attach',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response){
						//console.log(filename);						
						$('#securitycheckfile').val(filename);
						$('#securitycheckfilename').text(filename);
						$('#securitycheckattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			}); //$('.securitycheckattach').on('change', '.attach', () => {
		});

		function Uploadsecuritycheckfile(lnk) {			
			$("#securitycheckattach").click();
		}
		//delete security type row
		function delst(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
					var inputval = inputs[j].id;                
					if (inputval == 'securitytypedel') {
						inputs[j].value  = 1;						
					}						
				}
			tr.cells[2].getElementsByTagName("input")[0].value='A';	
			tr.cells[3].getElementsByTagName("input")[0].value='A@a.com';	
			tr.cells[4].getElementsByTagName("input")[0].value='0';	
			tr.style.display = 'none';
			tr.remove();

			var table = document.getElementById('listtable');
			var rowLength = table.rows.length;
			if(rowLength <= 2)
				$(".dataTables_empty").show();
		}
		//delete security type row end
	</script>
@endpush