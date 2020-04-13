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
		@if (isset($creditrequest)) 
			{{ Form::model($creditrequest, array('id' => 'frmManage', 'files' => true)) }}
		@else
			{{ Form::open(array('id' => 'frmManage', 'files' => true)) }}
		@endif		
		{{ Form::hidden('requesttype_id', $creditrequest->requesttype_id, array('id' => 'requesttype_id')) }}
		@php
			$requesttype_id = $creditrequest->requesttype_id;
		@endphp
	@endif
	
	@php 
		$showbusref = 1; 
		$showincomestatement = 1; 
		$showbalanceseet = 1; 
	@endphp
	@if (isset($creditrequest))
		@if ($creditrequest->requesttype_id == 2)
			@php 
				$showbusref = 0;
				$showincomestatement = 0; 
				$showbalanceseet = 0; 
			@endphp
		@endif
	@else
		@if (isset($requesttype_id))
			@if ($requesttype_id == 2)
				@php 
					$showbusref = 0; 
					$showincomestatement = 0; 
					$showbalanceseet = 0; 
				@endphp
			@endif
		@endif
	@endif
	@php
	$showincomestatement = 1; 
	$showbalanceseet = 1; 
	@endphp

		@if (!isset($mode) && !isset($creditrequest))
		<div class="row flex-container bm-pg-header">
			<h2 class="bm-pg-title">Create Credit Request</h2>
		</div>
		@endif
		
		<ul class="nav nav-tabs pointer">
		@if ($errors->has('bankfile') || $errors->has('financialfile') ||$errors->has('askedlimit') || $errors->has('margindeposittype_id'))
				<li class="active pointer-shape--without-left"><a style="left: 5px" href="#home">Basic&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span></a></li>
		@else
			<li class="active pointer-shape--without-left"><a style="left: 5px" href="#home">Basic</a></li>
		@endif
		@if (isset($creditrequest))
			@if ($creditrequest->creditstatus_id != 6 && $creditrequest->securities->count() > 0)
				<li class="pointer-shape"><a style="left: 12px" href="#menu1">Securities</a></li>
			@endif
		@endif
		@if ($errors->has('justification') || $errors->has('busrefname.*') || $errors->has('busreflimit.*') 
			|| $errors->has('busreftype.*') || $errors->has('busreflength.*') 
			|| $errors->has('busrefcount') || $errors->has('busref_contact_name.*')
			|| $errors->has('busref_contact_mobile.*') || $errors->has('busref_contact_email.*'))
			@if ($requesttype_id == 1)
				<li class="pointer-shape"><a style="left: 12px" href="#menu2">Business Ref&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span></a></li>
			@else
				<li class="pointer-shape"><a style="left: 14px" href="#menu2">Justification&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span></a></li>
			@endif
		@else
			@if ($requesttype_id == 1)
				<li class="pointer-shape"><a style="left: 12px" href="#menu2">Business Ref</a></li>
			@else
				<li class="pointer-shape"><a style="left: 14px" href="#menu2">Justification</a></li>
			@endif
		@endif
		@if ($showincomestatement == 1 || $showbalanceseet == 1)
			<?php $incstaterr = 0 ?>
			@if($errors->count() > 0)
			   @foreach ($errors->all() as $error)
					@if ($error == 'Income statement fields are required' || $error == 'Income statement fields must be numeric' || $error == 'Balance sheet fields are required' || $error == 'Balance sheet fields must be numeric')
						<?php $incstaterr = 1 ?>
						@break
					@endif
			  @endforeach
			@endif 
			@if ($errors->has('incomestatementfrom') || $errors->has('incomestatementtonotused') || $errors->has('balancesheeton') || $incstaterr == 1 || $errors->has('balancesheetitemy1value') || $errors->has('balancesheetitemy2value') || $errors->has('balancesheetitemy3value'))
				<li class="pointer-shape--without-right"><a style="left: 10px" href="#menu3">Financials&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span></a></li>
			@else
				<li class="pointer-shape--without-right"><a style="left: 10px" href="#menu3">Financials</a></li>
			@endif
		@endif
		@if (isset($mode))
			@if ($mode == 'a')
				<li class="pointer-shape--without-left"><a style="left: 5px" href="#menu4">Credit Assess.</a></li>
				<li class="pointer-shape"><a style="left: 12px" href="#menu5">Exec. Summary</a></li>
				<li class="pointer-shape--without-right"><a style="left: 10px" href="#menu6">Trading History</a></li>
			@endif
		@endif
	</ul>
	<div class="tab-content pointer">
		<div id="home" class="tab-pane fade in active">
			<div class="row">	<!-- row 1 -->
				<div class="col-md-2">  <!-- column 2 -->
					<div class="form-group"> <!-- company name -->  
						{{ Form::label('companyname', 'Credit request no.' ,['class' => 'label-view']) }}
						@if (isset($creditrequest))
							<p class='form-control-static'>{{ $creditrequest->id }}</p>
						@else
							<p class='form-control-static'>New</p>
						@endif
					</div> <!-- company name -->  
				</div>					<!-- end col 2 -->
				<div class="col-md-4">  <!-- column 2 -->
					<div class="form-group"> <!-- company name -->  
						{{ Form::label('companyname', 'Company name' ,['class' => 'label-view']) }}
						@if (isset($company))
							<p class='form-control-static'>{{ $company->companyname }}</p>
						@endif
						@if (isset($creditrequest))
							<p class='form-control-static'>{{ $creditrequest->company->companyname }}</p>
						@endif
					</div> <!-- company name -->  
				</div>					<!-- end col 2 -->
				<div class="col-md-4">  <!-- column 3 -->
					<div class="form-group"> <!-- address -->  
						{{ Form::label('address', 'Address' ,['class' => 'label-view']) }}
						@if (isset($company))
							<p class='form-control-static'>{{ $company->address }}</p>				
						@endif
						@if (isset($creditrequest))
							<p class='form-control-static'>{{ $creditrequest->company->address }}</p>
						@endif
					</div> <!-- address end -->			
				</div>					<!-- end col 3 -->		
				<div class="col-md-2">  <!-- column 4 -->
					<div class="form-group"> <!-- address -->  
						{{ Form::label('country', 'Country' ,['class' => 'label-view']) }}
						@if (isset($company))
							<p class='form-control-static'>{{ $company->country->countryname }}</p>				
						@endif
						@if (isset($creditrequest))
							<p class='form-control-static'>{{ $creditrequest->company->country->countryname }}</p>
						@endif
					</div> <!-- address end -->  
				</div>					<!-- end col 4 -->	
			</div>				<!-- end row 1 -->
			<div class="row">	<!-- row 2 -->
				<div class="col-md-5">  <!-- column 1 -->
					<div class="form-group"> <!-- company name -->  
						{{ Form::label('currentcreditlimit', 'Current credit limit' ,['class' => 'label-view']) }}
						@if (isset($company))
							<p class='form-control-static'>{{ number_format($company->creditlimit, 2, '.', ',') }}</p>
						@endif
						@if (isset($creditrequest))
							<p class='form-control-static'>{{ number_format($creditrequest->company->creditlimit, 2, '.', ',') }}</p>
						@endif
					</div> <!-- company name -->  
				</div>					<!-- end col 1 -->
			</div>				<!-- end row 2 -->	
			<div class="row">	<!-- row 3 -->
				<div class="col-md-5">  <!-- col 1 -->
					<div class="form-group"> <!-- bank attachment -->  
						{{ Form::label('banklic', 'Bank statement') }}<br>						
						@if (isset($mode))
							@foreach($creditrequest->bankStatements as $bankStatement)
								<a href="/{{ $bankStatement->path }}" download="{{ $bankStatement->path }}" style="display: block">{{ $bankStatement->filename }}</a>				
							@endforeach
						@else
							<progress id="progressBar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<br>
							<div class="flex-container">
								<div><a href="#" width="36px" class="attach-icon" onclick="Uploadbankfile(this);return false;" id="lnkattach" alt="Upload PDF, JPG, JPEG or PNG file that has a copy of the bank statement" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the bank statement"></a></div>
								<div>&nbsp;</div>
								<div>
									<small class='form-control-static'>Upload last six months statements.
										Use one or more PDF, JPEG, JPG, PNG files. Maximum file size is 6M.
									</small>
								</div>
							</div>
							<input type="file" name="bankattach" id="bankattach" class="bankattach" style="display:none;">
							<input type="text" id="tmpbankfilename" style="display:none;">
							<div id="bankStatements" class="files-container"></div>
						@endif
						@if (old('bankattachid'))
							<input name="bankfile" id="bankfile" type="hidden" value="{{ old('bankfile') }}">
							<input name="bankattachid" id="bankattachid" type="hidden" value="{{ old('bankattachid') }}">
							@php
							$bankAttachIds = explode(',', old('bankattachid'));
							$bankFileNames = explode(',', old('bankfile'));
							@endphp
							<div id="bankStatements" class="files-container">	
								@for($i = 0; $i < count($bankAttachIds); $i++)
								<div class="flex-container" style="margin-bottom: 5px">
									<a href="#!" onclick="deleteAttachment(this, 1);return false;"><span class="cancel-icon" title="Delete"></span></a>
									<span name="bankfilename" data-value="{{ $bankAttachIds[$i] }}" data-name="{{ $bankFileNames[$i] }}" style="margin-left: 5px;">{{ $bankFileNames[$i] }}</span>
								</div>
								@endfor
							</div>
						@else
							@if (isset($bankattachment))
								<input name="bankfile" id="bankfile" type="hidden" value="{{ $bankattachment->filename }}">
								<input name="bankattachid" id="bankattachid" type="hidden" value="">
								<span id="bankfilename" name="bankfilename">{{ $bankattachment->filename }}</span>
							@else
								<input name="bankfile" id="bankfile" type="hidden">
								<input name="bankattachid" id="bankattachid" type="hidden">
								<span id="bankfilename" name="bankfilename"></span>
							@endif
						@endif
						@if ($errors->has('bankfile')) <p class="bg-danger">{{ $errors->first('bankfile') }}</p> @endif
					</div>
				</div>					<!-- end col 1 -->
				<div class="col-md-5">  <!-- col 2 -->
					<div class="form-group"> <!-- financial attachment -->  
						{{ Form::label('financiallic', 'Financials') }}<br>
						@if (isset($mode))
							@foreach($creditrequest->financials as $financial)
								<a href="/{{ $financial->path }}" download="{{ $financial->path }}" style="display: block">{{ $financial->filename }}</a>				
							@endforeach
						@else
							<progress id="progressBarFinancial" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<br>
							<div class="flex-container">
								<div><a href="#" class="attach-icon" onclick="Uploadfinancialfile(this);return false;" id="lnkattach" alt="Upload PDF, JPG, JPEG or PNG file that has a copy of the financials" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the financials"></a></div>
								<div>&nbsp;</div>
								<div>
									<small class='form-control-static'>Upload last three years financials.
										Use one or more PDF, JPEG, JPG, PNG files. Maximum file size is 6M.
									</small>
								</div>
							</div>
							<input type="file" name="financialattach" id="financialattach" class="financialattach" style="display:none;">
							<input type="text" id="tmpfinancialfilename" style="display:none;">
							<div id="financialStatements" class="files-container"></div>
						@endif
						@if (old('financialfile'))
							<input name="financialfile" id="financialfile" type="hidden" value="{{ old('financialfile') }}">
							<input name="financialattachid" id="financialattachid" type="hidden" value="{{ old('financialattachid') }}">
							@php
							$financialAttachIds = explode(',', old('financialattachid'));
							$financialFileNames = explode(',', old('financialfile'));
							@endphp
							<div id="financialStatements" class="files-container">
								@for($i = 0; $i < count($financialAttachIds); $i++)
								<div class="flex-container" style="margin-bottom: 5px">
									<a href="#!" onclick="deleteAttachment(this, 2);return false;"><span class="cancel-icon" title="Delete"></span></a>
									<span name="financialfilename" data-value="{{ $financialAttachIds[$i] }}" data-name="{{ $financialFileNames[$i] }}" style="margin-left: 5px;">{{ $financialFileNames[$i] }}</span>
								</div>
								@endfor
							</div>
						@else
							@if (isset($financialattachment))
								<input name="financialfile" id="financialfile" type="hidden" value="{{ $financialattachment->filename }}">
								<input name="financialattachid" id="financialattachid" type="hidden" value="">
								<span id="financialfilename" name="financialfilename">{{ $financialattachment->filename }}</span>
							@else
								<input name="financialfile" id="financialfile" type="hidden">
								<input name="financialattachid" id="financialattachid" type="hidden">
								<span id="financialfilename" name="financialfilename"></span>
							@endif
						@endif
						@if ($errors->has('financialfile')) <p class="bg-danger">{{ $errors->first('financialfile') }}</p> @endif			
					</div>	
				</div>	<!-- col 2 end -->
				@if (isset($creditrequest))
					<div class="col-md-2">  <!-- column 3 -->
						<div class="form-group"> <!-- Status -->  
							{{ Form::label('status', 'Status') }}
							@switch ($creditrequest->creditstatus_id)
							@case (1)
								@php $class = 'bg-success'; @endphp
								@break
							@case (2)
								@php $class = 'bg-warning'; @endphp
								@break
							@case (3)
								@php $class = 'bg-danger'; @endphp
								@break
							@case (4)
								@php $class = 'bg-warning'; @endphp
								@break
							@case (5)
								@php $class = 'bg-warning'; @endphp
								@break
							@case (6)
								@php $class = 'bg-warning'; @endphp
								@break
							@endswitch
							<p class='form-control-static {{$class}}'>{{ $creditrequest->creditstatus->name }}</p>
						</div> <!-- address end -->  
					</div>					<!-- end col 3 -->
				@endif
			</div>				<!-- end row 3 -->
			<div class="row">	<!-- row 4 -->
				<div class="col-md-4">  <!-- column 1 -->
					<div class="form-group"> <!-- askedlimit -->  
						{{ Form::label('askedlimit', 'Requested limit') }}				
						@if (isset($mode))
							<p class='form-control-static'>{{ number_format($creditrequest->askedlimit, 2, '.', ',') }}</p>
						@else
							{{ Form::text('askedlimit', Input::old('askedlimit'), array('id' => 'askedlimit', 'class' => 'form-control')) }}								
							@if ($errors->has('askedlimit')) <p class="bg-danger">{{ $errors->first('askedlimit') }}</p> @endif
						@endif
					</div> <!-- askedlimit end -->  
				</div>					<!-- column 1 end -->
				@if (isset($creditrequest) && $creditrequest->approved_by != 0)
					<div class="col-md-2">  <!-- column 2 -->
						<div class="form-group"> <!-- margindeposittype -->  
							{{ Form::label('margindeposittype_id', 'Margin deposit type') }}
							@if (isset($mode))								
								<p class='form-control-static'>{{ $creditrequest->margindeposittype->name }}</p>
							@else
								{{ Form::select('margindeposittype_id', $margindeposittypes, Input::old('margindeposittype_id'),array('id' => 'margindeposittype_id', 'class' => 'form-control bm-select'))}}		
								@if ($errors->has('margindeposittype_id')) <p class="bg-danger">{{ $errors->first('margindeposittype_id') }}</p> @endif
							@endif
						</div> <!-- margindeposittype end -->				
					</div>					<!-- end col 2 -->
					<div class="col-md-2">  <!-- column 3 -->
						<div class="form-group"> <!-- margindeposittype -->  
							{{ Form::label('margindepositpercent', 'Margin deposit %') }}
							<p class='form-control-static text-center'>{{ number_format($creditrequest->margindepositvalue, 2, '.', ',') }}</p>
						</div> <!-- margindeposittype end -->				
					</div>	
				@endif
				<div class="col-md-2">  <!-- column 4 -->
					<div class="form-group"> <!-- tenor -->  
						{{ Form::label('tenor_id', 'Max tenor') }}
						@if (isset($mode))
							<p class='form-control-static'>{{ $creditrequest->tenor->name }}</p>
						@else
							{{ Form::select('tenor_id', $tenors, Input::old('tenor_id'),array('id' => 'tenor_id', 'class' => 'form-control bm-select'))}}		
							@if ($errors->has('tenor_id')) <p class="bg-danger">{{ $errors->first('tenor_id') }}</p> @endif
						@endif
					</div> <!-- tenor end -->				
				</div>					<!-- end col 4 -->
				<div class="col-md-3">  <!-- column 2 -->
				<div class="form-group"> <!-- currency -->  
					{{ Form::label('currency_id', 'Currency') }}
					@if (isset($mode))
						<p class='form-control-static'>{{ $creditrequest->currency->name }}</p>
					@else					
						{{ Form::select('currency_id', $currencies, old('currency_id'),array('id' => 'currency_id', 'class' => 'form-control bm-select'))}}		
						@if ($errors->has('currency_id')) <p class="bg-danger">{{ $errors->first('currency_id') }}</p> @endif
					@endif
				</div> <!-- currency --> 			
			</div>					<!-- column 2 end -->
			</div>				<!-- end row 4 -->	
		</div> <!-- end tab -->
		@if (isset($creditrequest))	
			<div id="menu1" class="tab-pane fade">
				<div class="row">	<!-- row 6 -->
					<div class="col-md-12"> <!-- column 1 -->
						<h4 class="bm-heading">Securities</h4>
						<table id="itemstable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Security type</th>
									<th>Signer name</th>
									<th>Signer email</th>
									<th>Value</th>
									@if (isset($mode))
										@if ($mode == 's' || $mode == 'v')
											<th>Status</th>
											<th>Document</th>
										@endif
									@endif
									<th>Actions</th>
								</tr>		
							</thead>
							<tbody>
								@foreach ($creditrequest->securities as $security)
									<tr>
										<td>
											<input name="security_id" id="security_id" type="hidden" value="{{ $security->id }}">
											{{ $security->securitytype->name }}
										</td>
										<td>{{ $security->signername }}</td>
										<td>{{ $security->signeremail }}</td>
										@if ($security->securitytype_id == 4 || $security->securitytype_id == 6 || $security->securitytype_id == 7)
											<td align="right">{{ number_format($security->amount, 2, '.', ',') }}</td>
											@elseif ($security->securitytype_id == 5)
															<td>Company name: {{ $security->company_name }}<br>
															Commercial register: {{ $security->commercial_register }}<br>
															Address: {{ $security->address }}<br>
															Designation: {{ $security->designation }}
															</td>
										@else
											<td>&nbsp;</td>
										@endif
										@if (isset($mode))
											@if ($mode == 's' || $mode == 'v')
												<td>
													@if (($security->securitytype_id == 4 || $security->securitytype_id == 6 || $security->securitytype_id == 7) && $security->status == 'signing_complete' )
														Delivered
													@else
														@if ($security->status == '')
															@if ($security->securitytype_id == 4 || $security->securitytype_id == 6 || $security->securitytype_id == 7)
																Pending delivery
															@else
																Pending buyer signature
															@endif
														@elseif ($security->status == 'signing_complete')
															Signing completed
														@else
															{{ $security->status }}
														@endif												
													@endif											
												</td>
												<td>
													@if (Gate::allows('cr_ap'))
														<?php
															$attachment = $security->attachment(true);
														?>
														@if ($attachment !== null)
															<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a><br>
														@endif
														@if ($security->securitytype_id == 7 && $security->attachments->count() >0)
															<a href="/{{ $security->attachments->first()->path }}" download="{{ $security->attachments->first()->path }}">{{ $security->attachments->first()->filename }}</a><br>
														@endif
													@endif
												</td>
											@endif
										@endif
										<td>
											@if ($security->securitytype_id == 4 && Gate::allows('cr_ap') && $security->status !== 'signing_complete' || $security->securitytype_id == 6 && Gate::allows('cr_ap') && $security->status !== 'signing_complete' || $security->securitytype_id == 7 && Gate::allows('cr_ap') && $security->status !== 'signing_complete')
											<a 
												href="#" 
												class="btn bm-btn blue" 
												role="button"
												title="I've recieved the credit check"
												style="padding-top: 5px;padding-bottom: 5px;padding-left: 10px;padding-right: 10px;"
												onclick="markAsRecieved(this, <?= $security->id ?>)"
												>
												<strong>Receive</strong>
												<span id="loader" style="display: inline-block;display: none">
													<i class="fa fa-spinner fa-spin"></i>
												</span>
											</a>

											<p id="info-message" class="green" style="display: none;margin-bottom: 0px"><strong>Received</strong></p>
										@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>				<!-- end row 6 -->
			</div> <!-- end tab -->
		@endif
		<div id="menu2" class="tab-pane fade">
			@if ($showbusref == 0)
				<div class="row">	<!-- row 7 -->
					<div class="col-md-12">  <!-- column 1 -->
						<div class="form-group"> <!-- company name -->  
							{{ Form::label('justification', 'Justification') }}
							@if (isset($mode))
							<p class='form-control-static'>{{ $creditrequest->justification }}</p>
							@else
								{{ Form::text('justification', Input::old('justification'), array('id' => 'justification', 'class' => 'form-control')) }}								
								@if ($errors->has('justification')) <p class="bg-danger">{{ $errors->first('justification') }}</p> @endif
							@endif
						</div> <!-- company name -->  
					</div>					<!-- end col 1 -->
				</div>				<!-- end row 7 -->	
			@else
			@php $busRefTypesArr = ['secured' => 'Secured', 'unsecured' => 'Unsecured'] @endphp
				<div class="row">	<!-- row 8 -->
					<div class="col-md-12"> <!-- column 1 -->
						<h4 class="bm-heading">Business References</h4>
						<?php $busrefcount = 3; ?>
						<div class="row">
							<div class="col-sm-12 table-container">
								<table id="busreftable" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											@if (isset($mode))
												<th class="col-md-2 tb-item-title">Company name</th>
												<th class="col-md-1 tb-item-title">Credit limit</th>
												<th class="col-md-2 tb-item-title">Contact name</th>
												<th class="col-md-2 tb-item-title">Contact email</th>
												<th class="col-md-2 tb-item-title">Contact mobile</th>
												<th class="col-md-1 tb-item-title">Type of credit</th>
												<th class="col-md-2 tb-item-title">Length of business (Years)</th>
											@else
												<th class="col-md-1 no-sort" width="10%">
													<a href="" id="lnkbusref" role="button" title="Add business reference" class="add-icon"></a>	
												</th>
											<th class="col-md-11 tb-item-title"></th>
											@endif
										</tr>		
									</thead>
									<tbody>
										@if (old('busrefid'))
											@php
												$i = 0;
											@endphp
											@foreach (old('busrefid') as $item)
												<tr style="{{ (old('busrefdel')[$i]) ? 'display:none' : '' }}">
													<td style="vertical-align:top">
														<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDel"></a>
														{{ Form::hidden('busrefid[]', old('busrefid')[$i], array('id' => 'busref_id')) }}
														{{ Form::hidden('busrefdel[]', old('busrefdel')[$i], array('id' => 'busrefdel', 'class' => 'form-control')) }}
													</td>
													<td>
														<div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busrefname', 'Company name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('busrefname[]', old('busrefname')[$i], array('id' => 'busrefname', 'class' => 'form-control')) }}
																	@if ($errors->has('busrefname.' . $i)) <p class="bg-danger">{{ $errors->first('busrefname.' . $i) }}</p> @endif
																</div>
															</div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busreflimit', 'Credit limit', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('busreflimit[]', old('busreflimit')[$i], array('id' => 'busreflimit', 'class' => 'form-control')) }}
																	@if ($errors->has('busreflimit.' . $i)) <p class="bg-danger">{{ $errors->first('busreflimit.' . $i) }}</p> @endif
																</div>
															</div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busref_contact_name', 'Contact name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('busref_contact_name[]', old('busref_contact_name')[$i], array('id' => 'busref_contact_name', 'class' => 'form-control')) }}
																	@if ($errors->has('busref_contact_name.' . $i)) <p class="bg-danger">{{ $errors->first('busref_contact_name.' . $i) }}</p> @endif
																</div>
															</div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busref_contact_email', 'Contact email', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('busref_contact_email[]', old('busref_contact_email')[$i], array('id' => 'busref_contact_email', 'class' => 'form-control')) }}
																	@if ($errors->has('busref_contact_email.' . $i)) <p class="bg-danger">{{ $errors->first('busref_contact_email.' . $i) }}</p> @endif
																</div>
															</div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busref_contact_mobile', 'Contact mobile', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('busref_contact_mobile[]', old('busref_contact_mobile')[$i], array('id' => 'busref_contact_mobile', 'class' => 'form-control busref_contact_mobile', 'placeholder' => '+00000000000000')) }}
																	@if ($errors->has('busref_contact_mobile.' . $i)) <p class="bg-danger">{{ $errors->first('busref_contact_mobile.' . $i) }}</p> @endif
																</div>
															</div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busreftype', 'Type of credit', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<select name="busreftype[]" class="form-control bm-select" id="busreftype">
																		@foreach ($busRefTypesArr as $key => $value)
																			<option value="{{ $key }}" <?= (isset(old('busreftype')[$i]) && old('busreftype')[$i] == $key) ? 'selected' : '' ?> >{{ $value }}</option>
																		@endforeach
																	</select>																	
																	@if ($errors->has('busreftype.' . $i)) <p class="bg-danger">{{ $errors->first('busreftype.' . $i) }}</p> @endif
																</div>
															</div>
															<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																{{ Form::label('busreflength', 'Length of business (Years)', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<select name="busreflength[]" class="form-control bm-select" id="busreflength">
																		@foreach ($arrbusreflengths as $id => $len)
																			<option value="{{ $id }}" <?= (isset(old('busreflength')[$i]) && old('busreflength')[$i] == $id) ? 'selected' : '' ?> >{{ $len }}</option>
																		@endforeach
																	</select>
																	@if ($errors->has('busreflength.' . $i)) <p class="bg-danger">{{ $errors->first('busreflength.' . $i) }}</p> @endif																</div>
																</div>
															</div>
													  </div>
													</td>
													
												</tr>
											@php
												$i++;
												$busrefcount = $i;
											@endphp	
											@endforeach
										@else
											@if (isset($creditrequest))
												<?php $i = 0 ; ?>
												@foreach ($creditrequest->busrefs as $busref)
													<tr>							
														@if (isset($mode))								
															<td>{{ $busref->busrefname }}</td>
															<td class="text-right">{{ number_format($busref->busreflimit, 2, '.', ',') }}</td>
															<td class="text-right">{{ $busref->contact_name }}</td>					
															<td class="text-right">{{ $busref->contact_email }}</td>					
															<td class="text-right">{{ $busref->contact_mobile }}</td>					
															<td>{{ $busref->busreftype }}</td>
															<td class="text-center">{{ $busref->yearsnum->name }}</td>
														@else
															<td style="vertical-align:top">
																<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDel"></a>
																{{ Form::hidden('busrefid[]', $busref->id, array('id' => 'busref_id')) }}
																{{ Form::hidden('busrefdel[]', '', array('id' => 'busrefdel', 'class' => 'form-control')) }}
															</td>
															<td>
																<div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busrefname', 'Company name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			{{ Form::text('busrefname[]', old('busrefname')[$i], array('id' => 'busrefname', 'class' => 'form-control')) }}
																			@if ($errors->has('busrefname.' . $i)) <p class="bg-danger">{{ $errors->first('busrefname.' . $i) }}</p> @endif
																		</div>
																	</div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busreflimit', 'Credit limit', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			{{ Form::text('busreflimit[]', old('busreflimit')[$i], array('id' => 'busreflimit', 'class' => 'form-control')) }}
																			@if ($errors->has('busreflimit.' . $i)) <p class="bg-danger">{{ $errors->first('busreflimit.' . $i) }}</p> @endif
																		</div>
																	</div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busref_contact_name', 'Contact name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			{{ Form::text('busref_contact_name[]', old('busref_contact_name')[$i], array('id' => 'busref_contact_name', 'class' => 'form-control')) }}
																			@if ($errors->has('busref_contact_name.' . $i)) <p class="bg-danger">{{ $errors->first('busref_contact_name.' . $i) }}</p> @endif
																		</div>
																	</div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busref_contact_email', 'Contact email', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			{{ Form::text('busref_contact_email[]', old('busref_contact_email')[$i], array('id' => 'busref_contact_email', 'class' => 'form-control')) }}
																			@if ($errors->has('busref_contact_email.' . $i)) <p class="bg-danger">{{ $errors->first('busref_contact_email.' . $i) }}</p> @endif
																		</div>
																	</div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busref_contact_mobile', 'Contact mobile', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			{{ Form::text('busref_contact_mobile[]', old('busref_contact_mobile')[$i], array('id' => 'busref_contact_mobile', 'class' => 'form-control busref_contact_mobile', 'placeholder' => '+00000000000000')) }}
																			@if ($errors->has('busref_contact_mobile.' . $i)) <p class="bg-danger">{{ $errors->first('busref_contact_mobile.' . $i) }}</p> @endif
																		</div>
																	</div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busreftype', 'Type of credit', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			<select name="busreftype[]" class="form-control bm-select" id="busreftype">
																				@foreach ($busRefTypesArr as $key => $value)
																					<option value="{{ $key }}" <?= (isset(old('busreftype')[$i]) && old('busreftype')[$i] == $key) ? 'selected' : '' ?> >{{ $value }}</option>
																				@endforeach
																			</select>
																			@if ($errors->has('busreftype.' . $i)) <p class="bg-danger">{{ $errors->first('busreftype.' . $i) }}</p> @endif
																		</div>
																	</div>
																	<div class="form-group row {{ isset($mode) ? ''  : 'required'}}" style="margin-left: 0">
																		{{ Form::label('busreflength', 'Length of business (Years)', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																		<div class=" col-lg-6 col-sm-9 col-xs-12">
																			<select name="busreflength[]" class="form-control bm-select" id="busreflength">
																				@foreach ($arrbusreflengths as $id => $len)
																					<option value="{{ $id }}" <?= (isset(old('busreflength')[$i]) && old('busreflength')[$i] == $id) ? 'selected' : '' ?> >{{ $len }}</option>
																				@endforeach
																			</select>
																			@if ($errors->has('busreflength.' . $i)) <p class="bg-danger">{{ $errors->first('busreflength.' . $i) }}</p> @endif																</div>
																		</div>
																	</div>
																</div>
															</td>
														@endif
													</tr>
													<?php $i = $i + 1 ; 
														$busrefcount = $i;
													?>
												@endforeach
											@else
												@for ($i = 0; $i < 3; $i++)
												<tr>
													<td style="vertical-align:top">
														<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelOwner" type="button" title="Delete business refrence"></a>
														<input name="busrefid[]" type="hidden">
														<input name="busrefdel[]" id="busrefdel" type="hidden">
													</td>
													<td>
														<div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busrefname', 'Company name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<input name="busrefname[]" type="text" class="form-control">
																</div>
															</div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busreflimit', 'Credit limit', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<input name="busreflimit[]" type="text" class="form-control">																
																</div>
															</div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busref_contact_name', 'Contact name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<input name="busref_contact_name[]" type="text" class="form-control">																
																</div>
															</div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busref_contact_email', 'Contact email', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<input name="busref_contact_email[]" type="text" class="form-control">																
																</div>
															</div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busref_contact_mobile', 'Contact mobile', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<input name="busref_contact_mobile[]" type="text" class="form-control busref_contact_mobile" id="busref_contact_mobile" placeholder = "+00000000000000">																
																</div>
															</div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busreftype', 'Type of credit', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	<select name="busreftype[]" type="text" class="form-control bm-select">
																		<option value="secured">Secured</option>
																		<option value="unsecured">Unsecured</option>
																	</select>
																</div>
															</div>
															<div class="form-group required row" style="margin-left: 0">
																{{ Form::label('busreflength', 'Length of business (Years)', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																@if (isset($busreflengths))
																	<select name="busreflength[]" class="form-control bm-select">
																	@foreach ($busreflengths as $busreflength)
																		<option value="{{ $busreflength->id }}"> {{ $busreflength->name }}</option>
																	@endforeach
																@endif															
																</div>
															</div>
													  </div>
													</td>
												</tr>
												@endfor
										@endif
									@endif
									</tbody>
								</table>
							</div>
						</div>
						<input type="hidden" name="busrefcount" id="busrefcount" value="{{$busrefcount}}">
						@if ($errors->has('busrefcount')) <p class="bg-danger">{{ $errors->first('busrefcount') }}</p> @endif
					</div>
				</div>				<!-- end row 8 -->
			@endif
		</div> <!-- end tab -->
		@if ($showincomestatement == 1 || $showbalanceseet == 1)
			<div id="menu3" class="tab-pane fade">
				<div class="row">	<!-- row 11 -->
					<div class=" col-md-2"> <!-- column 1 -->
						<h4>Financials as of</h4>
					</div>		
					<div class="col-md-2">  <!-- column 2 -->
						<div class="form-group"> <!-- incomestatementfrom -->  
							@if (isset($mode))	
								<p class='form-control-static'>{{ date("j/n/Y",strtotime($creditrequest->incomestatementfrom)) }}</p>
							@else										
								@if (isset($creditrequest))
									{{ Form::text('incomestatementfrom', date("j/n/Y",strtotime($creditrequest->incomestatementfrom)), array('id' => 'incomestatementfrom', 'class' => 'form-control')) }}									
								@else
									{{ Form::text('incomestatementfrom', old('incomestatementfrom', date("j/n/Y",strtotime($incomestatementstart))), array('id' => 'incomestatementfrom', 'class' => 'form-control')) }}
								@endif					
								@if ($errors->has('incomestatementfrom')) <p class="bg-danger">{{ $errors->first('incomestatementfrom') }}</p> @endif
							@endif
						</div> <!-- incomestatementfrom end -->  
					</div>					<!-- column 2 end -->
					<div class=" col-md-1 hidden "> <!-- column 3 -->
						<p class="form-control-static">&nbsp;</p>
					</div>		
					<div class="col-md-2 hidden">  <!-- column 4 -->
						<div class="form-group"> <!-- incomestatementtonotused -->  
							@if (isset($mode))	
								&nbsp;
							@else										
								@if (isset($creditrequest))
									{{ Form::hidden('incomestatementtonotused', date("j/n/Y",strtotime($creditrequest->incomestatementtonotused)), array('id' => 'incomestatementtonotused', 'class' => 'form-control')) }}									
								@else
									{{ Form::hidden('incomestatementtonotused', old('incomestatementtonotused'), array('id' => 'incomestatementtonotused', 'class' => 'form-control')) }}									
								@endif
								@if ($errors->has('incomestatementtonotused')) <p class="bg-danger">{{ $errors->first('incomestatementtonotused') }}</p> @endif
							@endif
						</div> <!-- incomestatementtonotused end -->  
					</div>					<!-- column 4 end -->
					<div class=" col-md-2"> <!-- column 5 -->
						<h4>Currency</h4>
					</div>		
					<div class="col-md-2">  <!-- column 6 -->
						<div class="form-group"> <!-- financialscurrency -->  
							@if (isset($mode))	
								<p class='form-control-static'>{{ $creditrequest->financialscurrency->name }}</p>
							@else										
								{{ Form::select('financialscurrency_id', $financialscurrencies, old('financialscurrency_id'),array('id' => 'financialscurrency_id', 'class' => 'form-control bm-select'))}}		
								@if ($errors->has('financialscurrency_id')) <p class="bg-danger">{{ $errors->first('financialscurrency_id') }}</p> @endif
							@endif
						</div> <!-- financialscurrency end -->  
					</div>					<!-- column 2 end -->
				</div>				<!-- end row 11 -->
				<div class="row">	<!-- row 11 -->
				<div class=" col-md-3"> <!-- column 1 -->
						<h4 class="bm-heading">Income statement</h4>
					</div>
				</div>				<!-- end row 11 -->
				<div class="row">	<!-- row 12 -->
					<div class=" col-md-12"> <!-- column 1 -->
						@if (isset($creditrequest))
							@php
								$incomestatementstart = $creditrequest->incomestatementfrom
							@endphp
						@endif
						<table id="incomestatementtable" class="table table-striped table-bordered table-hover">
							<thead>					
								<tr>
									<th class="tb-item-title">From</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-3 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-2 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-1 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
								</tr>
								<tr>
									<th class="tb-item-title">To</th>						
									<th class="text-right tb-item-title">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-2 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-1 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>						
									<th class="text-right tb-item-title">{{ date("j/n/Y",strtotime($incomestatementstart)) }}</th>
								</tr>
							</thead>
							<tbody>
								@if (isset($creditrequest))						
									@foreach ($creditrequest->incomestatements as $incomestatement)							
										<tr class="{{$incomestatement->incomestatementitem->calc == 1 ? 'success' : ''}}">
											@if (isset($mode))
												<td class="tb-item-title">{{ $incomestatement->incomestatementitem->name }}</td>
												@if ($incomestatement->incomestatementitem->name == '%')
													<td class="text-right">{{ number_format($incomestatement->incomestatementitemy1 * 100, 2, '.', ',') }}</td>
													<td class="text-right">{{ number_format($incomestatement->incomestatementitemy2 * 100, 2, '.', ',') }}</td>
													<td class="text-right">{{ number_format($incomestatement->incomestatementitemy3 * 100, 2, '.', ',') }}</td>
												@else
													<td class="text-right">{{ number_format($incomestatement->incomestatementitemy1, 2, '.', ',') }}</td>
													<td class="text-right">{{ number_format($incomestatement->incomestatementitemy2, 2, '.', ',') }}</td>
													<td class="text-right">{{ number_format($incomestatement->incomestatementitemy3, 2, '.', ',') }}</td>
												@endif												
											@else
												@php
													if ($incomestatement->incomestatementitem->sign) {
														$thesign = '+';
														$sign = 'positive';
													} else {
														$thesign = '-';
														$sign = 'negative';
													}
												@endphp
												<td class="tb-item-title">
													{{ Form::hidden('incomestatementid[]', $incomestatement->id, array('id' => 'incomestatementid', 'class' => 'form-control')) }}
													{{ Form::hidden('incomestatementitem_id[]', $incomestatement->incomestatementitem_id, array('id' => 'incomestatementitem_id', 'class' => 'form-control')) }}
													{{ Form::hidden('order[]', $incomestatement->order, array('id' => 'order', 'class' => 'form-control')) }}
													{{ $incomestatement->incomestatementitem->name }} ({{ $thesign }})
												</td>
												<td>
													{{ Form::text('incomestatementitemy1[]', $incomestatement->incomestatementitemy1, array('id' => 'incomestatementitemy1[]', 'class' => 'form-control ' . $sign, 'placeholder' => $thesign)) }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy2[]', $incomestatement->incomestatementitemy2, array('id' => 'incomestatementitemy2[]', 'class' => 'form-control ' . $sign, 'placeholder' => $thesign)) }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy3[]', $incomestatement->incomestatementitemy3, array('id' => 'incomestatementitemy3[]', 'class' => 'form-control ' . $sign, 'placeholder' => $thesign)) }}
												</td>
											@endif
										</tr>	
									@endforeach						
								@else
									@foreach ($incomestatementitems as $incomestatementitem)
										@php
											if ($incomestatementitem->sign) {
												$thesign = '+';
												$sign = 'positive';
											} else {
												$thesign = '-';
												$sign = 'negative';
											}
										@endphp
										@if ($incomestatementitem->calc == 0)
											<tr>
												<td class="tb-item-title">
													{{ Form::hidden('incomestatementid[]', old('incomestatementid[]'), array('id' => 'incomestatementid', 'class' => 'form-control')) }}
													{{ Form::hidden('incomestatementitem_id[]', $incomestatementitem->id, array('id' => 'incomestatementitem_id', 'class' => 'form-control')) }}
													{{ Form::hidden('order[]', $incomestatementitem->order, array('id' => 'order', 'class' => 'form-control')) }}
													{{ $incomestatementitem->name }}  ({{ $thesign }})
												</td>
												<td>
													{{ Form::text('incomestatementitemy1[]', old('incomestatementitemy1[]'), array('id' => 'incomestatementitemy1[]', 'class' => 'incomestatementitemy1 form-control ' . $sign, 'placeholder' => $thesign)) }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy2[]', old('incomestatementitemy2[]'), array('id' => 'incomestatementitemy2[]', 'class' => 'incomestatementitemy2 form-control ' . $sign, 'placeholder' => $thesign)) }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy3[]', old('incomestatementitemy3[]'), array('id' => 'incomestatementitemy3[]', 'class' => 'incomestatementitemy3 form-control ' . $sign, 'placeholder' => $thesign)) }}
												</td>
											</tr>											
										@else
											<tr>
												<td class="tb-item-title">
													{{ Form::hidden('incomestatementid[]', old('incomestatementid[]'), array('id' => 'incomestatementid', 'class' => 'form-control')) }}
													{{ Form::hidden('incomestatementitem_id[]', $incomestatementitem->id, array('id' => 'incomestatementitem_id', 'class' => 'form-control')) }}
													{{ Form::hidden('order[]', $incomestatementitem->order, array('id' => 'order', 'class' => 'form-control')) }}
													{{ $incomestatementitem->name }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy1[]', old('incomestatementitemy1[]'), array('id' => 'incomestatementitemy1[]', 'class' => 'incomestatementitemy1_calc form-control hidden')) }}
													<span>{{ old('incomestatementitemy1[]') }}</span>
												</td>
												<td>
													{{ Form::text('incomestatementitemy2[]', old('incomestatementitemy2[]'), array('id' => 'incomestatementitemy2[]', 'class' => 'incomestatementitemy2_calc form-control hidden')) }}
													<span>{{ old('incomestatementitemy1[]') }}</span>
												</td>
												<td>
													{{ Form::text('incomestatementitemy3[]', old('incomestatementitemy3[]'), array('id' => 'incomestatementitemy3[]', 'class' => 'incomestatementitemy3_calc form-control hidden')) }}
													<span>{{ old('incomestatementitemy1[]') }}</span>
												</td>
											</tr>
										@endif
									@endforeach
								@endif
							</tbody>
						</table>
						@if($errors->count() > 0)
						   @foreach ($errors->all() as $error)
								@if ($error == 'Income statement fields are required')
									<p class="bg-danger">Income statement fields are required</p>
									@break
								@endif
								@if ($error == 'Income statement fields must be numeric')
									<p class="bg-danger">Income statement fields must be numeric</p>
									@break
								@endif								
						  @endforeach
						@endif 
					</div>
				</div>				<!-- end row 12 -->
				
				
				<div class="row">	<!-- row 13 -->
					<div class=" col-md-3"> <!-- column 1 -->
						<h4 class="bm-heading">Balance sheet</h4>
					</div>
					<div class="col-md-2">  <!-- column 2 -->
						<div class="form-group"> <!-- balancesheeton -->  
							@if (isset($mode))	
								<p class='form-control-static hidden'>{{ date("j/n/Y",strtotime($creditrequest->balancesheeton)) }}</p>
							@else										
								@if (isset($creditrequest))
									{{ Form::text('balancesheeton', date("j/n/Y",strtotime($creditrequest->balancesheeton)), array('id' => 'balancesheeton', 'class' => 'form-control hidden')) }}									
								@else
									{{ Form::text('balancesheeton', old('balancesheeton', date("j/n/Y",strtotime($balancesheeton))), array('id' => 'balancesheeton', 'class' => 'form-control hidden')) }}
								@endif
							@endif
						</div> <!-- balancesheeton end -->  
					</div>					<!-- column 2 end -->
				</div>				<!-- end row 13 -->
				<div class="row">	<!-- row 14 -->
					<div class=" col-md-12"> <!-- column 1 -->
						@if (isset($creditrequest))
							@php
								$balancesheeton = $creditrequest->balancesheeton
							@endphp
						@endif
						<table id="balancesheettable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th class="tb-item-title">From</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-3 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-2 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-1 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
								</tr>
								<tr>
									<th class="tb-item-title">To</th>						
									<th class="text-right tb-item-title">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-2 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right tb-item-title">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-1 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>						
									<th class="text-right tb-item-title">{{ date("j/n/Y",strtotime($balancesheeton)) }}</th>
								</tr>
							</thead>
							<tbody>
								@php
									$balancesheet1 = 0;
									$balancesheet2 = 0;
									$balancesheet3 = 0;
								@endphp
								@if (isset($creditrequest))
									@foreach ($creditrequest->balancesheets as $balancesheet)
										@if (isset($mode))
											<tr class="{{$balancesheet->balancesheetitem->calc == 1 ? 'success' : ''}}">
										@else
											<tr>
										@endif
											@if (isset($mode))
												<td class="tb-item-title">{{ $balancesheet->balancesheetitem->name }}</td>
												<td class="text-right">{{ number_format($balancesheet->balancesheetitemy1, 2, '.', ',') }}</td>
												<td class="text-right">{{ number_format($balancesheet->balancesheetitemy2, 2, '.', ',') }}</td>
												<td class="text-right">{{ number_format($balancesheet->balancesheetitemy3, 2, '.', ',') }}</td>
											@else
												@php
													if ($balancesheet->balancesheetitem->sign) {
														$thesign = '+';
														$sign = 'positive';
													} else {
														$thesign = '-';
														$sign = 'negative';
													}
												@endphp
												<td class="tb-item-title">
													{{ Form::hidden('balancesheetid[]', $balancesheet->id, array('id' => 'balancesheetid', 'class' => 'form-control')) }}
													{{ Form::hidden('balancesheetitem_id[]', $balancesheet->balancesheetitem_id, array('id' => 'balancesheetitem_id', 'class' => 'form-control')) }}
													{{ Form::hidden('order[]', $balancesheet->order, array('id' => 'order', 'class' => 'form-control')) }}
													{{ $balancesheet->balancesheetitem->name }}
													</td>
												<td>
													{{ Form::text('balancesheetitemy1[]', $balancesheet->balancesheetitemy1, array('id' => 'balancesheetitemy1[]', 'class' => 'form-control balancesheetitemy1 ' . $sign, 'placeholder' => $thesign)) }}
												</td>
												<td>
													{{ Form::text('balancesheetitemy2[]', $balancesheet->balancesheetitemy2, array('id' => 'balancesheetitemy2[]', 'class' => 'form-control balancesheetitemy2 ' . $sign, 'placeholder' => $thesign)) }}
												</td>
												<td>
													{{ Form::text('balancesheetitemy3[]', $balancesheet->balancesheetitemy3, array('id' => 'balancesheetitemy3[]', 'class' => 'form-control balancesheetitemy3 ' . $sign, 'placeholder' => $thesign)) }}
												</td>
											@endif
										</tr>	
									@endforeach						
								@else
									@foreach ($balancesheetitems as $balancesheetitem)
										@php
											if ($balancesheetitem->sign) {
												$thesign = '+';
												$sign = 'positive';
											} else {
												$thesign = '-';
												$sign = 'negative';
											}
										@endphp
										<tr>
											<td class="tb-item-title">
												{{ Form::hidden('balancesheetid[]', old('balancesheetid[]'), array('id' => 'balancesheetid', 'class' => 'form-control')) }}
												{{ Form::hidden('balancesheetitem_id[]', $balancesheetitem->id, array('id' => 'balancesheetitem_id', 'class' => 'form-control')) }}
												{{ Form::hidden('bsorder[]', $balancesheetitem->order, array('id' => 'bsorder', 'class' => 'form-control')) }}
												{{ $balancesheetitem->name }} ({{ $thesign }})
												</td>
											<td>
												{{ Form::text('balancesheetitemy1[]', old('balancesheetitemy1[]'), array('id' => 'balancesheetitemy1[]', 'class' => 'form-control balancesheetitemy1 ' . $sign, 'placeholder' => $thesign)) }}
											</td>
											<td>
												{{ Form::text('balancesheetitemy2[]', old('balancesheetitemy2[]'), array('id' => 'balancesheetitemy2[]', 'class' => 'form-control balancesheetitemy2 ' . $sign, 'placeholder' => $thesign)) }}
											</td>
											<td>
												{{ Form::text('balancesheetitemy3[]', old('balancesheetitemy3[]'), array('id' => 'balancesheetitemy3[]', 'class' => 'form-control balancesheetitemy3 ' . $sign, 'placeholder' => $thesign)) }}
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
							@if (!isset($creditrequest))
								<tfoot>
									<td class="tb-item-title">Balance</td>
									<td class="tb-item-title text-right">
										<span id="balancesheetitemy1balance">{{ number_format(old('balancesheetitemy1value',$balancesheet1), 2, '.', ',') }}</span>
										{{ Form::hidden('balancesheetitemy1value', old('balancesheetitemy1value',$balancesheet1), array('id' => 'balancesheetitemy1value')) }}
									</th>
									<td class="tb-item-title text-right">
										<span id="balancesheetitemy2balance">{{ number_format(old('balancesheetitemy2value', $balancesheet2), 2, '.', ',') }}</span>
										{{ Form::hidden('balancesheetitemy2value', old('balancesheetitemy2value', $balancesheet2), array('id' => 'balancesheetitemy2value')) }}
									</th>
									<td class="tb-item-title text-right">
										<span id="balancesheetitemy3balance">{{ number_format(old('balancesheetitemy3value', $balancesheet3), 2, '.', ',') }}</span>
										{{ Form::hidden('balancesheetitemy3value', old('balancesheetitemy3value', $balancesheet3), array('id' => 'balancesheetitemy3value')) }}
									</th>
								</tfoot>
							@endif
						</table>
						@if($errors->count() > 0)
						   @foreach ($errors->all() as $error)
								@if ($error == 'Balance sheet fields are required')
									<p class="bg-danger">Balance sheet fields are required</p>
									@break
								@endif
								@if ($error == 'Balance sheet fields must be numeric')
									<p class="bg-danger">Balance sheet fields must be numeric</p>
									@break
								@endif
								@if ($error == 'Income statement balance must be 0.')
									<p class="bg-danger">Income statement balance must be 0.</p>
									@break
								@endif
						  @endforeach
						@endif 
					</div>
				</div>				<!-- end row 14 -->
				
			</div> <!-- end tab -->
		@endif
		@if ($showbalanceseet == 1)
		@endif
		@if (isset($mode))
			@if ($mode == 'a')
				<div id="menu4" class="tab-pane fade">
					<div class="row">	<!-- row 11 -->
						<div class=" col-md-3"> <!-- column 1 -->
							<h4 class="bm-heading">Credit Assessment</h4>
						</div>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('prepared_by', 'Prepared By') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->prepared_by }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('approved_by', 'Approved By') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->approved_by }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('date_of_assessment', 'Date Of Assessment') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ date("j/n/Y",strtotime($creditrequest->creditassessments->first()->date_of_assessment)) }}</p>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- end tab -->
				<div id="menu5" class="tab-pane fade">
					<div class="row">	<!-- row 11 -->
						<div class=" col-md-3"> <!-- column 1 -->
							<h4 class="bm-heading">Executive Summary</h4>
						</div>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('company_background', 'Company Background') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->company_background }}</p>
								</div>
							</div>
						</div>
					</div>					
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('key_financials_developments', 'Key Financials Developments') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->key_financials_developments }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('key_risks', 'Key Risks') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->key_risks }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('mitigating_factors', 'Mitigating Factors') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->mitigating_factors }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">	<!-- row 11 -->
						<div class=" col-md-3"> <!-- column 1 -->
							<h4 class="bm-heading">Legal Information</h4>
						</div>
					</div>
					<div class="row">	<!-- row 11 -->
						<table id="legalinfo" class="form-table table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Company Name</th>
								<th>Type</th>
							</tr>		
						</thead>
						<tbody>
							@foreach ($creditrequest->creditassessmentcompanies as $creditassessmentcompany)
								<tr>
									<td>{{ $creditassessmentcompany->company_name }}</td>
									<td>{{ $creditassessmentcompany->companyrelationtype->name }}</td>
								</tr>
							@endforeach
						</tbody>
						</table>
					</div>
				</div> <!-- end tab -->
				<div id="menu6" class="tab-pane fade">
					<div class="row">	<!-- row 11 -->
						<div class=" col-md-3"> <!-- column 1 -->
							<h4 class="bm-heading">Trading History</h4>
						</div>
					</div>
					<div class="row">	<!-- row 11 -->
						<table id="lastquarter" class="form-table table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Last 8 Quarters</th>
									<th>Sales</th>
									<th>Payments</th>
								</tr>		
							</thead>
							<tbody>
								@foreach ($creditrequest->tradehistory as $tradehistory)
									<tr>
										<td>{{ $tradehistory->quarter }}</td>
										<td>{{ $tradehistory->sales }}</td>
										<td>{{ $tradehistory->payments }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row">					
						<div class="col-sm-12">  <!-- Cclumn 1 -->
							<div class="control-label col-offset-md-1 col-sm-3">
								{{ Form::label('outstanding', 'Highest Outstanding Balance in Last 12 Months') }}
							</div>
							<div class="col-md-6">
								<div class="form-group"> <!-- date -->  
									<p class='form-control-static'>{{ $creditrequest->creditassessments->first()->heighest_balance }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">	<!-- row 11 -->
						<div class=" col-md-3"> <!-- column 1 -->
							<h4 class="bm-heading">Score Card</h4>
						</div>
					</div>
					<div class="row">	<!-- row 11 -->
						<table id="lastquarter" class="form-table table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Information</th>
									<th>Weigh</th>
									<th>Score</th>
									<th>Results</th>
								</tr>		
							</thead>
							<tbody>
								@php
									$score = 0;
								@endphp
								@foreach ($creditrequest->scorecard as $scorecard)
									<tr>
										<td>{{ $scorecard->factor->name }}</td>
										<td>{{ $scorecard->weight }}</td>
										<td>{{ $scorecard->score->value }}</td>
										<td>{{ $scorecard->weight * $scorecard->score->value }}</td>
									</tr>
									@php
										$score = $score + $scorecard->weight * $scorecard->score->value;
									@endphp
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3">Score</th>
									<th>{{ $score }}</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div> <!-- end tab -->
			@endif
		@endif
	</div>	
	<div class="row">	<!-- row 19 --> 
		<div class=" col-md-12"> <!-- column 1 -->			
			@if (isset($mode))
				@if ($mode == 's')
					<a href="{{ url("/creditrequests/view/" . $creditrequest->id) }}" class="btn bm-btn blue fixedw_button bm-btn green" role="button" title="Save"><span class="glyphicon glyphicon-ok"></span></a>
				@else
					@if (Gate::allows('cr_ap') || (Gate::allows('cr_cr') && $creditrequest->appointment_id != null))
						<div class="col-xs-3"> <!-- column 4 -->
							<a href="#" class="btn btn-info bm-btn hidden" id="lnkprev" type="button" title="Prev">Previous</a>
							<a href="#" class="btn btn-info bm-btn hidden" id="lnknext" type="button" title="Next">Next</a>
						</div>
					@endif
					@if (Gate::allows('cr_ap') && $creditrequest->creditstatus_id == 2)
						<!-- Increase or initial and appointment completed -->						
						@if($creditrequest->requesttype_id == 2 || ($creditrequest->appointment_id != null && $creditrequest->appointment->status_id == 3)) 							
							<div class="col-xs-3"> <!-- column 4 -->
								<a href="{{ url("/creditrequests/proceed/" . $creditrequest->id) }}" class="btn btn-info bm-btn green hidden" role="button" id="lnkproceed" title="Proceed to credit decision">Proceed to credit decision</a>
							</div>
						@elseif($creditrequest->appointment_id != null && $creditrequest->appointment->status_id == 1)
							<div class="row">	<!-- row 20 -->								
								<div class="col-sm-12"> <!-- Column 1 -->
									<br>
									<div class="alert alert-danger">
										<p class="bg-danger"><strong>Accept site visit</strong></p>
										<p class="bg-danger">Click <a href="/calendar/accept/{{$creditrequest->appointment->id}}">here</a> to accept the site visit</p>
									</div>
								</div> <!-- Column 1 end -->
							</div> <!--row 20 end -->
						@elseif($creditrequest->appointment_id != null && $creditrequest->appointment->status_id == 8)
							<div class="row">	<!-- row 20 -->
								<div class="col-sm-12"> <!-- Column 1 -->
									<br>
									<div class="alert alert-danger">
										<p class="bg-danger"><strong>Complete site visit</strong></p>
										<p class="bg-danger">Click <a href="/calendar/complete/{{$creditrequest->appointment->id}}">here</a> to complete the site visit</p>
									</div>
								</div> <!-- Column 1 end -->
							</div> <!--row 20 end -->
						@endif
					@endif
				@endif
			@else
				<a href="#" class="btn btn-info bm-btn hidden" id="lnkprev" type="button" title="Prev">Previous</a>
				<a href="#" class="btn btn-info bm-btn hidden" id="lnknext" type="button" title="Next">Next</a>
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn bm-btn blue fixedw_button hidden')) }}						
				<a href="" class="btn btn-info fixedw_button bm-btn green hidden" id="lnksubmit" type="button" title="Save">
					Save
				</a>
			@endif 	
		</div> <!-- column 1 end -->
	</div> <!--row 19 end -->
	@if (isset($mode))
		@if ($mode == 'v' && Gate::allows('cr_cr') && $creditrequest->appointment_id == null && $creditrequest->requesttype_id == 1)
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
	@if (isset($creditrequest))
		@if (Gate::allows('cr_cr') && $creditrequest->appointment_id == null )
			{{ Form::open(['url' => '/creditrequests/delete/' . $creditrequest->id]) }}
			<a href="#" class="btn btn-info bm-btn hidden" id="lnkprev" type="button" title="Prev">Previous</a>
			<a href="#" class="btn btn-info bm-btn hidden" id="lnknext" type="button" title="Next">Next</a>
			<button id="lnkdelete" class="btn bm-btn red fixedw_button" title="Delete credit request" type="submit">Delete</button>
			{{ Form::close() }}
		@endif	
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
			//tabs
			$(".nav-tabs a").click(function(){
				$(this).tab('show');
				nextprev();
			});
			$('.nav-tabs a').on('shown.bs.tab', function(event){
				var x = $(event.target).text();         
				var y = $(event.relatedTarget).text();  
				$(".act span").text(x);
				$(".prev span").text(y);
			});
			//tabs end
			
			nextprev();
			
			$('#lnknext').click(function(){
			  $('.nav-tabs > .active').next('li').find('a').trigger('click');
			});

			  $('#lnkprev').click(function(){
			  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
			});

			var contact_mobile = $(".busref_contact_mobile");
			if (contact_mobile) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(contact_mobile);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(contact_mobile);
			}
			var incomestatementitem = $(".positive");
			if (incomestatementitem) {
				Inputmask({"regex": "\\+\\d+(\\.\\d+)?", "placeholder": ""}).mask(incomestatementitem);
			}
			var incomestatementitem = $(".negative");
			if (incomestatementitem) {
				Inputmask({"regex": "\\-\\d+(\\.\\d+)", "placeholder": ""}).mask(incomestatementitem);
			}
			var incomestatementfrom = $( "#incomestatementfrom" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
			var incomestatementtonotused = $( "#incomestatementtonotused" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
			incomestatementfrom.on( "change", function() {
				$('#balancesheeton').val($('#incomestatementfrom').val());
				$('#balancesheeton').trigger('change');
				var table = document.getElementById('incomestatementtable');
				var date = getDate(this);
				var momentObj = moment(date);
				
				var momentString = momentObj.add(+1, 'd').add(-3, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[0].cells[1].innerHTML =  momentString;
				var momentString = momentObj.add(-1, 'd').add(1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[1].cells[1].innerHTML =  momentString;
				
				var momentObj = moment(date);
				var momentString = momentObj.add(+1, 'd').add(-2, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[0].cells[2].innerHTML =  momentString;
				var momentString = momentObj.add(-1, 'd').add(1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[1].cells[2].innerHTML =  momentString;				
				
				var momentObj = moment(date);
				var momentString = momentObj.add(+1, 'd').add(-1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[0].cells[3].innerHTML =  momentString;
				var momentString = momentObj.add(-1, 'd').add(1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[1].cells[3].innerHTML =  momentString;				
			})
			incomestatementtonotused.on( "change", function() {
				date1 = getDate(this);
				date2 = Date.now() ;
				var diff = date1.valueOf() - date2.valueOf();				
				ddiff = Math.ceil(diff / (1000 * 3600 * 24));
				var new_options = {
					format: "d/m/yyyy",
					endDate: ddiff + "d",
					autoclose: true,					
				}
				$('#incomestatementfrom').datepicker('destroy');
				$('#incomestatementfrom').datepicker(new_options);
			})
			var balancesheeton = $( "#balancesheeton" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
			balancesheeton.on( "change", function() {
				var table = document.getElementById('balancesheettable');
				var date = getDate(this);
				var momentObj = moment(date);
				
				var momentString = momentObj.add(+1, 'd').add(-3, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[0].cells[1].innerHTML =  momentString;
				var momentString = momentObj.add(-1, 'd').add(1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[1].cells[1].innerHTML =  momentString;
				
				var momentObj = moment(date);
				var momentString = momentObj.add(+1, 'd').add(-2, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[0].cells[2].innerHTML =  momentString;
				var momentString = momentObj.add(-1, 'd').add(1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[1].cells[2].innerHTML =  momentString;				
				
				var momentObj = moment(date);
				var momentString = momentObj.add(+1, 'd').add(-1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[0].cells[3].innerHTML =  momentString;
				var momentString = momentObj.add(-1, 'd').add(1, 'y').format('D/M/YYYY'); // 2016-07-15
				table.rows[1].cells[3].innerHTML =  momentString;				
			})
			$("#lnkbusref").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('busreftable');
				var rowLength = table.rows.length;
				
				var row = '<tr><td style="vertical-align:top">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelOwner" type="button" title="Delete business refrence"></a>';
				row = row + '<input name="busrefid[]" type="hidden">';
				row = row + '<input name="busrefdel[]" id="busrefdel" type="hidden"></td>';
				row = row + '<td>';
				row = row + '<div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busrefname" class="control-label bm-label tb-label col-sm-3 col-xs-12">Company name</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<input name="busrefname[]" type="text" class="form-control">';
				row = row + '</div></div>';
				row = row + '<div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busreflimit" class="control-label bm-label tb-label col-sm-3 col-xs-12">Credit limit</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<input name="busreflimit[]" type="text" class="form-control">';
				row = row + '</div></div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busref_contact_name" class="control-label bm-label tb-label col-sm-3 col-xs-12">Contact name</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<input name="busref_contact_name[]" type="text" class="form-control">';
				row = row + '</div></div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busref_contact_email" class="control-label bm-label tb-label col-sm-3 col-xs-12">Contact email</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<input name="busref_contact_email[]" type="text" class="form-control">';
				row = row + '</div></div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busref_contact_mobile" class="control-label bm-label tb-label col-sm-3 col-xs-12">Contact mobile</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<input name="busref_contact_mobile[]" type="text" class="form-control busref_contact_mobile" id="busref_contact_mobile" placeholder = "+00000000000000">';
				row = row + '</div></div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busreftype" class="control-label bm-label tb-label col-sm-3 col-xs-12">Type of credit</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<select name="busreftype[]" type="text" class="form-control bm-select"><option value="secured">Secured</option><option value="unsecured">Unsecured</option></select>';
				row = row + '</div></div>';
				row = row + '<div class="form-group required row" style="margin-left: 0">';
				row = row + '<label for="busreflength" class="control-label bm-label tb-label col-sm-3 col-xs-12">Length of business (Years)</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12">'
				row = row + '<select name="busreflength[]" type="text" class="form-control bm-select">';
				@php
					if (isset($busreflengths)) {
						foreach ($busreflengths as $busreflength) {
							echo "row = row + '<option value=" . $busreflength->id . ">" . $busreflength->name . "</option>';";
						}
					}
				@endphp
				row = row + '</select></div></div></div></td></tr>';
				
				$('#busreftable').append(row);
				$("#busrefcount").val(parseInt($("#busrefcount").val()) + 1);
				var contact_mobile = $(".busref_contact_mobile");
				if (contact_mobile) {
					//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(contact_mobile);
					Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(contact_mobile);
				}
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
			$('#financialattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;				
				if (filesize > 6291456) {
					alert('Maximum file size is 6M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
					alert('Only PDF, JPEG, JPG, PNG files are allowed');
					return false;
				}

				// Check if there's a file with the same name
				if($.inArray(filename, $('#financialfile').val().split(',')) != -1) {
					if(!confirm('A file with the same name already uploaded, Are you sure you want to upload this file?')) return;
				}

				var formData = new FormData;					
				formData.append('attach', file);									
				formData.append('_token', $('input[name=_token]').val());
					
				$('#tmpfinancialfilename').val(filename);
				var file = event.target.files[0];
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandlerFinancial, false);
				ajax.addEventListener("load", completeHandlerFinancial, false);
				$("#progressBarFinancial").removeClass('hidden');
				ajax.open("POST", "/attach");
				pendingFileUpload();
				ajax.send(formData);

				// Clear input value
				$('#financialattach').val("");
			});

			$('#bankattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;

				if (filesize > 6291456) {
					alert('Maximum file size is 6M');
					return false;
				}

				if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
					alert('Only PDF, JPEG, JPG, PNG files are allowed');
					return false;
				}

				// Check if there's a file with the same name
				if($.inArray(filename, $('#bankfile').val().split(',')) != -1) {
					if(!confirm('A file with the same name already uploaded, Are you sure you want to upload this file?')) return;
				}

				var formData = new FormData;
				formData.append('attach', file);
				formData.append('_token', $('input[name=_token]').val());

				$('#tmpbankfilename').val(filename);
				
				var file = event.target.files[0];
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				$("#progressBar").removeClass('hidden');
				ajax.open("POST", "/attach");
				pendingFileUpload();
				ajax.send(formData);

				// Clear input value
				$('#bankattach').val("");
			});

			$('#personalguaranteeattach').on('change', (event) => {
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
						//console.log(filename);						
						$('#personalguaranteefile').val(filename);
						$('#personalguaranteefilename').text(filename);
						$('#personalguaranteeattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			});

			$('#corporateguaranteeattach').on('change', (event) => {
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
						//console.log(filename);						
						$('#corporateguaranteefile').val(filename);
						$('#corporateguaranteefilename').text(filename);
						$('#corporateguaranteeattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			});

			$('#promissarynoteattach').on('change', (event) => {
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
						//console.log(filename);						
						$('#promissarynotefile').val(filename);
						$('#promissarynotefilename').text(filename);
						$('#promissarynoteattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			});

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
			});

		});
		function DelRow(lnk) {
			var tr = lnk.parentNode.parentNode;
			$("#busrefcount").val(parseInt($("#busrefcount").val()) - 1);
			$(tr).remove();
		}
		function Uploadbankfile(lnk) {			
			$("#bankattach").click();
		}		
		function Uploadfinancialfile(lnk) {			
			$("#financialattach").click();
		}
		function Uploadpersonalguaranteefile(lnk) {			
			$("#personalguaranteeattach").click();
		}
		function Uploadcorporateguaranteefile(lnk) {			
			$("#corporateguaranteeattach").click();
		}
		function Uploadpromissarynotefile(lnk) {			
			$("#promissarynoteattach").click();
		}function Uploadsecuritycheckfile(lnk) {			
			$("#securitycheckattach").click();
		}
		function getDate( element ) {
				var date;				
				//date = $.datepicker.parseDate( "d/m/yyyy", element.value );
				var split = element.value.split("/");
				date = new Date(split[2], split[1] -1 , split[0]);
				//console.log(date);
				return date;
			}		

		function progressHandler(event) {
			var percent = (event.loaded / event.total) * 100;
			$("#progressBar").val(Math.round(percent));
			console.log(percent);
		}

		function completeHandler(event) {
			var fileName = $('#tmpbankfilename').val();
			var bankStatement = '<div class="flex-container" style="margin-bottom: 5px">';
			bankStatement += '<a href="#!" onclick="deleteAttachment(this,' + '1' + ');return false;">'
			bankStatement += '<span class="cancel-icon" title="Delete"></span></a>';
			bankStatement += '<span name="bankfilename" data-value="' + event.target.responseText + '"';
			bankStatement += ' data-name="' + fileName + '" style="margin-left: 5px;">';
			bankStatement += fileName + '</span></div>';
			$('#bankStatements').append(bankStatement);
			$("#progressBar").addClass('hidden');
			_("progressBar").value = 0;
			var bankFile = $('#bankfile');
			var bankFileVal = bankFile.val() ? bankFile.val() + ',' : '';
			bankFile.val(bankFileVal + fileName);
			var bankAttach = $('#bankattachid');
			var prevValue = bankAttach.val() ? bankAttach.val() + ',' : '';
			bankAttach.val(prevValue + event.target.responseText);
			completeFileUpload();
		}

		function progressHandlerFinancial(event) {
			var percent = (event.loaded / event.total) * 100;
			$("#progressBarFinancial").val(Math.round(percent));
			console.log(percent);
		}

		function completeHandlerFinancial(event) {
			var fileName = $('#tmpfinancialfilename').val();

			var financialStatement = '<div class="flex-container" style="margin-bottom: 5px">';
			financialStatement += '<a href="#!" onclick="deleteAttachment(this,' + '2' + ');return false;">';
			financialStatement += '<span class="cancel-icon" title="Delete"></span></a>';
			financialStatement += '<span name="financialfilename" data-value="' + event.target.responseText + '"';
			financialStatement += ' data-name="' + fileName + '" style="margin-left: 5px;">';
			financialStatement += fileName + '</span></div>';
			$('#financialStatements').append(financialStatement);
			$("#progressBarFinancial").addClass('hidden');
			_("progressBarFinancial").value = 0;
			var financialFile = $('#financialfile');
			var financialFileVal = financialFile.val() ? financialFile.val() + ',' : '';
			financialFile.val(financialFileVal + fileName);
			var financialAttach = $('#financialattachid');
			var prevValue = financialAttach.val() ? financialAttach.val() + ',' : '';
			financialAttach.val(prevValue + event.target.responseText);

			completeFileUpload();
		}

		function deleteAttachment(element, context) {
			var sibling = $(element).next();
			if (context == 1)
				var target = 'bank';

			if (context == 2)
				var target = 'financial';

			var attachmentId = sibling.attr('data-value');
			var attachmentName = sibling.attr('data-name');
		
			var attachmentsIds = $('#' + target + 'attachid'); // All bank statements ids
			var attachmentsNames = $('#' + target + 'file'); // All bank statements names
			
			// String to Arr
			var attachmentsIdsArr = attachmentsIds.val().split(',');
			var attachmentsNamesArr = attachmentsNames.val().split(',');
		
			var idIndex = attachmentsIdsArr.indexOf(attachmentId);
			var nameIndex = attachmentsNamesArr.indexOf(attachmentName);

			if (idIndex > -1)
				attachmentsIdsArr.splice(idIndex, 1);
			
			if (nameIndex > -1)
				attachmentsNamesArr.splice(nameIndex, 1);
			
			attachmentsIds.val(attachmentsIdsArr.join(", "));
			attachmentsNames.val(attachmentsNamesArr.join(", "));

			$(element).parent().remove();
		}

		function markAsRecieved(btn, id) {
			$.ajax ({
				url: '/creditrequests/credit-cheque/' + id + '/recieved',
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				beforeSend: function(){
					$("#loader").show()
				},
				success: function(response){				
					$("#info-message").show()
					$("#loader").hide()
					$(btn).hide()
					
					var table = document.getElementById('itemstable');
					var rowLength = table.rows.length;
					for (var i = 1; i < rowLength; i += 1){
						var row = table.rows[i];
						var inputs = row.cells[0].getElementsByTagName("input");
						if (inputs.length > 0) {
							if (inputs[0].value == id) {
								var inputs = row.cells[4].innerText = 'Delivered';
							}
						}
					}
				},
				error: function(err) {
					$("#loader").hide()
				}
			})
		}

	// Handle Upload Side Effects for Next btn
	function pendingFileUpload() {
		$("#lnksubmit").attr( "title", "Please wait till file is uploaded" );
		$("#lnksubmit").attr("disabled", true);
	}

	function completeFileUpload() {
		if (!$("progress:visible").length) {
			$("#lnksubmit").attr( "title", "Save" );
			$("#lnksubmit").attr("disabled", false);
		}
	}
	function nextprev () {
		mytabs = $(".pointer-shape, .pointer-shape--without-left, .pointer-shape--without-right");
		$("#lnkproceed").addClass('hidden');
		mytabs.each(function (index,item) {
			if ($(item).is('.active')) {
				//console.log(item);
				console.log(index);
				if (index == 0) {
					$("#lnkprev").addClass('hidden');
					$("#lnknext").removeClass('hidden');
					$("#lnksubmit").addClass('hidden');
					$("#lnkproceed").addClass('hidden');
				} else if (index == mytabs.length - 1) {
					$("#lnkprev").removeClass('hidden');
					$("#lnknext").addClass('hidden');
					$("#lnksubmit").removeClass('hidden');					
					$("#lnkproceed").removeClass('hidden');
				} else {
					$("#lnkprev").removeClass('hidden');
					$("#lnknext").removeClass('hidden');
					$("#lnksubmit").addClass('hidden');
					$("#lnkproceed").addClass('hidden');
				}
			}
		});
		//console.log(mytabs.length);
	}
	</script>
@endpush