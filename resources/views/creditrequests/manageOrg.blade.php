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
		<ul class="nav nav-tabs">
		@if ($errors->has('bankfile') || $errors->has('financialfile') ||$errors->has('askedlimit') || $errors->has('margindeposittype_id'))
				<li class="active"><a href="#home">Basic&nbsp;<span class="red glyphicon glyphicon-warning-sign"></span></a></li>
		@else
			<li class="active"><a href="#home">Basic</a></li>
		@endif
		@if (isset($creditrequest))	
			<li><a href="#menu1">Securities</a></li>
		@endif
		@if ($errors->has('justification') || $errors->has('busrefname.*') || $errors->has('busreflimit.*') || $errors->has('busreftype.*') || $errors->has('busreflength.*') || $errors->has('busrefcount'))
			@if ($requesttype_id == 1)
				<li><a href="#menu2">Business references&nbsp;<span class="red glyphicon glyphicon-warning-sign"></span></a></li>
			@else
				<li><a href="#menu2">Justification&nbsp;<span class="red glyphicon glyphicon-warning-sign"></span></a></li>
			@endif
		@else
			@if ($requesttype_id == 1)
				<li><a href="#menu2">Business references</a></li>
			@else
				<li><a href="#menu2">Justification</a></li>
			@endif
		@endif
		@if ($showincomestatement == 1)
			<?php $incstaterr = 0 ?>
			@if($errors->count() > 0)
			   @foreach ($errors->all() as $error)
					@if ($error == 'Income statement fields are required' || $error == 'Income statement fields must be numeric')
						<?php $incstaterr = 1 ?>
						@break
					@endif
			  @endforeach
			@endif 
			@if ($errors->has('incomestatementfrom') || $errors->has('incomestatementtonotused') || $incstaterr == 1)
				<li><a href="#menu3">Income statement&nbsp;<span class="red glyphicon glyphicon-warning-sign"></span></a></li>
			@else
				<li><a href="#menu3">Income statement</a></li>
			@endif
		@endif
		@if ($showbalanceseet == 1)
			<?php $balsheeterr = 0 ?>
			@if($errors->count() > 0)
			   @foreach ($errors->all() as $error)
					@if ($error == 'Balance sheet fields are required' || $error == 'Balance sheet fields must be numeric')
						<?php $balsheeterr = 1 ?>
						@break
					@endif
			  @endforeach
			@endif
			@if ($errors->has('balancesheeton') || $balsheeterr == 1)
				<li><a href="#menu4">Balance sheet&nbsp;<span class="red glyphicon glyphicon-warning-sign"></span></a></li>
			@else
				<li><a href="#menu4">Balance sheet</a></li>
			@endif		
		@endif		
	</ul>
	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">
			<div class="row">	<!-- row 1 -->
				<div class="col-md-2">  <!-- column 2 -->
					<div class="form-group"> <!-- company name -->  
						{{ Form::label('companyname', 'Credit request no.') }}
						@if (isset($creditrequest))
							<p class='form-control-static'>{{ $creditrequest->id }}</p>
						@else
							<p class='form-control-static'>New</p>
						@endif
					</div> <!-- company name -->  
				</div>					<!-- end col 2 -->
				<div class="col-md-4">  <!-- column 2 -->
					<div class="form-group"> <!-- company name -->  
						{{ Form::label('companyname', 'Company name') }}
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
						{{ Form::label('address', 'Address') }}
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
						{{ Form::label('country', 'Country') }}
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
						{{ Form::label('currentcreditlimit', 'Current credit limit') }}
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
				<div class="col-md-4">  <!-- col 1 -->
					<div class="form-group"> <!-- bank attachment -->  
					{{ Form::label('banklic', 'Bank statement') }}<br>
					@if (isset($mode))				
						<a href="/{{ $creditrequest->attachments->where('attachmenttype_id', 6)->first()->path }}" download="{{ $creditrequest->attachments->where('attachmenttype_id', 6)->first()->path }}">{{ $creditrequest->attachments->where('attachmenttype_id', 6)->first()->filename }}</a>				
					@else
						<a href="#" class="btn btn-success" onclick="Uploadbankfile(this);return false;" id="lnkattach" alt="Upload PDF file that has a copy of the bank statement" title="Upload PDF file that has a copy of the bank statement"><span class="glyphicon glyphicon-link"></span></a>			
						<input type="file" name="bankattach" id="bankattach" class="bankattach" style="display:none;">
					@endif
					@if (old('bankfile'))
						<input name="bankfile" id="bankfile" type="hidden" value="{{ old('bankfile') }}">
						<input name="bankattachid" id="bankattachid" type="hidden" value="{{ old('bankattachid') }}">
						<span id="bankfilename" name="bankfilename">{{ old('bankfile') }}</span>
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
					</div>					<!-- end col 1 -->
				</div>	<!-- col 1 end -->
				<div class="col-md-4">  <!-- col 2 -->
					<div class="form-group"> <!-- financial attachment -->  
					{{ Form::label('financiallic', 'Financial statement') }}<br>
					@if (isset($mode))
						<a href="/{{ $creditrequest->attachments->where('attachmenttype_id', 8)->first()->path }}" download="{{ $creditrequest->attachments->where('attachmenttype_id', 8)->first()->path }}">{{ $creditrequest->attachments->where('attachmenttype_id', 8)->first()->filename }}</a>
					@else
						<a href="#" class="btn btn-success" onclick="Uploadfinancialfile(this);return false;" id="lnkattach" alt="Upload PDF file that has a copy of the financials" title="Upload PDF file that has a copy of the financials"><span class="glyphicon glyphicon-link"></span></a>			
						<input type="file" name="financialattach" id="financialattach" class="financialattach" style="display:none;">
					@endif
					@if (old('financialfile'))
						<input name="financialfile" id="financialfile" type="hidden" value="{{ old('financialfile') }}">
						<input name="financialattachid" id="financialattachid" type="hidden" value="{{ old('financialattachid') }}">
						<span id="financialfilename" name="financialfilename">{{ old('financialfile') }}</span>
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
					<div class="col-md-4">  <!-- column 3 -->
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
								{{ Form::select('margindeposittype_id', $margindeposittypes, Input::old('margindeposittype_id'),array('id' => 'margindeposittype_id', 'class' => 'form-control'))}}		
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
							{{ Form::select('tenor_id', $tenors, Input::old('tenor_id'),array('id' => 'tenor_id', 'class' => 'form-control'))}}		
							@if ($errors->has('tenor_id')) <p class="bg-danger">{{ $errors->first('tenor_id') }}</p> @endif
						@endif
					</div> <!-- tenor end -->				
				</div>					<!-- end col 4 -->
			</div>				<!-- end row 4 -->	
		</div> <!-- end tab -->
		@if (isset($creditrequest))	
			<div id="menu1" class="tab-pane fade">
				<div class="row">	<!-- row 6 -->
					<div class="col-md-12"> <!-- column 1 -->
						<h4>Securities</h4>
						<table id="mytable" class="table table-striped table-bordered table-hover">
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
								</tr>		
							</thead>
							<tbody>
								@foreach ($creditrequest->securities as $security)
									<tr>
										<td> {{ $security->securitytype->name}}</td>
										<td>{{ $security->signername }}</td>
										<td>{{ $security->signeremail }}</td>
										@if ($security->securitytype_id == 4)								
											<td align="right">{{ number_format($security->amount, 2, '.', ',') }}</td>
										@else									
											<td>&nbsp;</td>
										@endif
										@if (isset($mode))
											@if ($mode == 's' || $mode == 'v')
												<td>
													@if ($security->securitytype_id == 4 && $security->status == 'signing_complete')
														Uploaded
													@else
														@if ($security->status == '')
															@if ($security->securitytype_id == 4)
																Pending delivery
															@else
																Pending buyer signature
															@endif
														@else
															{{ $security->status }}
														@endif												
													@endif											
												</td>
												<td>
													@if ($security->securitytype_id == 4 && $mode == 's')
														@if ($security->document == null)
															<a href="#" class="btn btn-success" onclick="Uploadsecuritycheckfile(this);return false;" id="lnkattach" alt="Upload PDF file that has a copy of the securitychecks" title="Upload PDF file that has a copy of the personal guarantee"><span class="glyphicon glyphicon-link"></span></a>			
															<input type="file" name="securitycheckattach" id="securitycheckattach" class="securitycheckattach" style="display:none;">	
														@endif
													@else
														@if ($security->securitytype_id == 4 && $security->status == 'signing_complete')
															<a href="/{{ $creditrequest->attachments->where('attachmenttype_id', 7)->first()->path }}" download="{{ $creditrequest->attachments->where('attachmenttype_id', 7)->first()->path }}">{{ $creditrequest->attachments->where('attachmenttype_id', 7)->first()->filename }}</a>
														@else
															@if ($security->document == null)
																&nbsp;
															@else
																<a href="/{{ 'envelopes/' . $security->document }}" download="{{ 'envelopes/' . $security->document }}">{{ substr($security->document,strlen($security->envelope) + strlen($security->document_id) + 2,100) }}</a>
															@endif														
														@endif
														
													@endif
												</td>
											@endif
										@endif
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
				<div class="row">	<!-- row 8 -->
					<div class="col-md-12"> <!-- column 1 -->
						<h4>Credit references</h4>
						<?php $busrefcount = 0; ?>
						<table id="busreftable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									@if (!isset($mode))
										<th class="col-md-1 no-sort" width="10%">
											<a href="" id="lnkbusref" role="button"><span class="glyphicon glyphicon-plus" title="Add business reference"></span></a>	
										</th>
									@endif
									<th class="col-md-5">Company name</th>
									<th class="col-md-2">Credit limit</th>
									<th class="col-md-2">Type of credit</th>
									<th class="col-md-3">Length of business (Years)</th>
								</tr>		
							</thead>
							<tbody>
								@if (old('busrefid'))
									@php
										$i = 0;
									@endphp
									@foreach (old('busrefid') as $item)
										<tr style="{{ (old('busrefdel')[$i]) ? 'display:none' : '' }}">
											<td>
												<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDel"><span class="glyphicon glyphicon-trash"></span></a>
												{{ Form::hidden('busrefid[]', old('busrefid')[$i], array('id' => 'busref_id')) }}
												{{ Form::hidden('busrefdel[]', old('busrefdel')[$i], array('id' => 'busrefdel', 'class' => 'form-control')) }}
											</td>
											<td>
												{{ Form::text('busrefname[]', old('busrefname')[$i], array('id' => 'busrefname', 'class' => 'form-control')) }}
												@if ($errors->has('busrefname.' . $i)) <p class="bg-danger">{{ $errors->first('busrefname.' . $i) }}</p> @endif
											</td>
											<td>
												{{ Form::text('busreflimit[]', old('busreflimit')[$i], array('id' => 'busreflimit', 'class' => 'form-control')) }}
												@if ($errors->has('busreflimit.' . $i)) <p class="bg-danger">{{ $errors->first('busreflimit.' . $i) }}</p> @endif
											</td>
											<td>
												{{ Form::select('busreftype[]', array('secured' => 'Secured','unsecured' => 'Unsecured'), old('busreftype')[$i], array('id' => 'busreftype', 'class' => 'form-control')) }}
												@if ($errors->has('busreftype.' . $i)) <p class="bg-danger">{{ $errors->first('busreftype.' . $i) }}</p> @endif
											</td>
											<td>
												{{ Form::select('busreflength[]', $arrbusreflengths, old('busreflength')[$i], array('id' => 'busreflength', 'class' => 'form-control')) }}
												@if ($errors->has('busreflength.' . $i)) <p class="bg-danger">{{ $errors->first('busreflength.' . $i) }}</p> @endif
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
													<td>{{ $busref->busreftype }}</td>
													<td class="text-center">{{ $busref->yearsnum->name }}</td>
												@else
													<td>
														<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDel"><span class="glyphicon glyphicon-trash" type="button"></span></a>
														{{ Form::hidden('busrefid[]', $busref->id, array('id' => 'busref_id')) }}
														{{ Form::hidden('busrefdel[]', '', array('id' => 'busrefdel', 'class' => 'form-control')) }}
													</td>
													<td>{{ Form::text('busrefname[]', $busref->busrefname, array('id' => 'busrefname', 'class' => 'form-control')) }}</td>
													<td>{{ Form::text('busreflimit[]', $busref->busreflimit, array('id' => 'busreflimit', 'class' => 'form-control')) }}</td>
													<td>{{ Form::select('busreftype[]', array('secured' => 'Secured','unsecured' => 'Unsecured'), $busref->busreftype, array('id' => 'busreftype', 'class' => 'form-control')) }}</td>
													<td>{{ Form::select('busreflength[]', $arrbusreflengths, $busref->busreflength, array('id' => 'busreflength', 'class' => 'form-control')) }}</td>
													</td>
												@endif
											</tr>
											<?php $i = $i + 1 ; 
												$busrefcount = $i;
											?>
										@endforeach
									@endif
								@endif
							</tbody>
						</table>
						<input type="hidden" name="busrefcount" id="busrefcount" value="{{$busrefcount}}">
						@if ($errors->has('busrefcount')) <p class="bg-danger">{{ $errors->first('busrefcount') }}</p> @endif
					</div>					<!-- end col 1 -->
				</div>				<!-- end row 8 -->
			@endif
		</div> <!-- end tab -->
		@if ($showincomestatement == 1)
			<div id="menu3" class="tab-pane fade">
				<div class="row">	<!-- row 11 -->
					<div class=" col-md-3"> <!-- column 1 -->
						<h4>Income statement</h4>
					</div>
					<div class=" col-md-1"> <!-- column 1 -->
						<p class="form-control-static">To</p>
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
					<div class=" col-md-1"> <!-- column 3 -->
						<p class="form-control-static">&nbsp;</p>
					</div>		
					<div class="col-md-2">  <!-- column 4 -->
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
									<th>From</th>
									<th class="text-right">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-3 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-2 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-1 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
								</tr>
								<tr>
									<th>To</th>						
									<th class="text-right">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-2 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right">
										@php
											$date = date_create($incomestatementstart);
											date_modify($date, '-1 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>						
									<th class="text-right">{{ date("j/n/Y",strtotime($incomestatementstart)) }}</th>
								</tr>
							</thead>
							<tbody>
								@if (isset($creditrequest))						
									@foreach ($creditrequest->incomestatements as $incomestatement)							
										<tr class="{{$incomestatement->incomestatementitem->calc == 1 ? 'success' : ''}}">
											@if (isset($mode))
												<td>{{ $incomestatement->incomestatementitem->name }}</td>
												<td class="text-right">{{ number_format($incomestatement->incomestatementitemy1, 2, '.', ',') }}</td>
												<td class="text-right">{{ number_format($incomestatement->incomestatementitemy2, 2, '.', ',') }}</td>
												<td class="text-right">{{ number_format($incomestatement->incomestatementitemy3, 2, '.', ',') }}</td>
											@else
												<td>
													{{ Form::hidden('incomestatementid[]', $incomestatement->id, array('id' => 'incomestatementid', 'class' => 'form-control')) }}
													{{ Form::hidden('incomestatementitem_id[]', $incomestatement->incomestatementitem_id, array('id' => 'incomestatementitem_id', 'class' => 'form-control')) }}
													{{ Form::hidden('order[]', $incomestatement->order, array('id' => 'order', 'class' => 'form-control')) }}
													{{ $incomestatement->incomestatementitem->name }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy1[]', $incomestatement->incomestatementitemy1, array('id' => 'incomestatementitemy1[]', 'class' => 'form-control')) }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy2[]', $incomestatement->incomestatementitemy2, array('id' => 'incomestatementitemy2[]', 'class' => 'form-control')) }}
												</td>
												<td>
													{{ Form::text('incomestatementitemy3[]', $incomestatement->incomestatementitemy3, array('id' => 'incomestatementitemy3[]', 'class' => 'form-control')) }}
												</td>
											@endif
										</tr>	
									@endforeach						
								@else
									@foreach ($incomestatementitems as $incomestatementitem)
										<tr>
											<td>
												{{ Form::hidden('incomestatementid[]', old('incomestatementid[]'), array('id' => 'incomestatementid', 'class' => 'form-control')) }}
												{{ Form::hidden('incomestatementitem_id[]', $incomestatementitem->id, array('id' => 'incomestatementitem_id', 'class' => 'form-control')) }}
												{{ Form::hidden('order[]', $incomestatementitem->order, array('id' => 'order', 'class' => 'form-control')) }}
												{{ $incomestatementitem->name }}
											</td>
											<td>
												{{ Form::text('incomestatementitemy1[]', old('incomestatementitemy1[]'), array('id' => 'incomestatementitemy1[]', 'class' => 'form-control')) }}
											</td>
											<td>
												{{ Form::text('incomestatementitemy2[]', old('incomestatementitemy2[]'), array('id' => 'incomestatementitemy2[]', 'class' => 'form-control')) }}
											</td>
											<td>
												{{ Form::text('incomestatementitemy3[]', old('incomestatementitemy3[]'), array('id' => 'incomestatementitemy3[]', 'class' => 'form-control')) }}
											</td>
										</tr>
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
				
				
			</div> <!-- end tab -->
		@endif
		@if ($showbalanceseet == 1)
			<div id="menu4" class="tab-pane fade">
				<div class="row">	<!-- row 13 -->
					<div class=" col-md-3"> <!-- column 1 -->
						<h4>Balance sheet</h4>
					</div>
					<div class=" col-md-1"> <!-- column 1 -->
						<p class="form-control-static">To</p>
					</div>		
					<div class="col-md-2">  <!-- column 2 -->
						<div class="form-group"> <!-- balancesheeton -->  
							@if (isset($mode))	
								<p class='form-control-static'>{{ date("j/n/Y",strtotime($creditrequest->balancesheeton)) }}</p>
							@else										
								@if (isset($creditrequest))
									{{ Form::text('balancesheeton', date("j/n/Y",strtotime($creditrequest->balancesheeton)), array('id' => 'balancesheeton', 'class' => 'form-control')) }}									
								@else
									{{ Form::text('balancesheeton', old('balancesheeton', date("j/n/Y",strtotime($balancesheeton))), array('id' => 'balancesheeton', 'class' => 'form-control')) }}									
								@endif
								@if ($errors->has('balancesheeton')) <p class="bg-danger">{{ $errors->first('balancesheeton') }}</p> @endif
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
									<th>From</th>
									<th class="text-right">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-3 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-2 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-1 year');
											date_modify($date, '+1 day');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
								</tr>
								<tr>
									<th>To</th>						
									<th class="text-right">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-2 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>
									<th class="text-right">
										@php
											$date = date_create($balancesheeton);
											date_modify($date, '-1 year');
											echo date_format($date, 'j/n/Y');
										@endphp
									</th>						
									<th class="text-right">{{ date("j/n/Y",strtotime($balancesheeton)) }}</th>
								</tr>
							</thead>
							<tbody>
								@if (isset($creditrequest))						
									@foreach ($creditrequest->balancesheets as $balancesheet)
										@if (isset($mode))
											<tr class="{{$balancesheet->balancesheetitem->calc == 1 ? 'success' : ''}}">
										@else
											<tr>
										@endif
											@if (isset($mode))
												<td>{{ $balancesheet->balancesheetitem->name }}</td>
												<td class="text-right">{{ number_format($balancesheet->balancesheetitemy1, 2, '.', ',') }}</td>
												<td class="text-right">{{ number_format($balancesheet->balancesheetitemy2, 2, '.', ',') }}</td>
												<td class="text-right">{{ number_format($balancesheet->balancesheetitemy3, 2, '.', ',') }}</td>
											@else
												<td>
													{{ Form::hidden('balancesheetid[]', $balancesheet->id, array('id' => 'balancesheetid', 'class' => 'form-control')) }}
													{{ Form::hidden('balancesheetitem_id[]', $balancesheet->balancesheetitem_id, array('id' => 'balancesheetitem_id', 'class' => 'form-control')) }}
													{{ Form::hidden('order[]', $balancesheet->order, array('id' => 'order', 'class' => 'form-control')) }}
													{{ $balancesheet->balancesheetitem->name }}
													</td>
												<td>
													{{ Form::text('balancesheetitemy1[]', $balancesheet->balancesheetitemy1, array('id' => 'balancesheetitemy1[]', 'class' => 'form-control')) }}
												</td>
												<td>
													{{ Form::text('balancesheetitemy2[]', $balancesheet->balancesheetitemy2, array('id' => 'balancesheetitemy2[]', 'class' => 'form-control')) }}
												</td>
												<td>
													{{ Form::text('balancesheetitemy3[]', $balancesheet->balancesheetitemy3, array('id' => 'balancesheetitemy3[]', 'class' => 'form-control')) }}
												</td>
											@endif
										</tr>	
									@endforeach						
								@else
									@foreach ($balancesheetitems as $balancesheetitem)
										<tr>
											<td>
												{{ Form::hidden('balancesheetid[]', old('balancesheetid[]'), array('id' => 'balancesheetid', 'class' => 'form-control')) }}
												{{ Form::hidden('balancesheetitem_id[]', $balancesheetitem->id, array('id' => 'balancesheetitem_id', 'class' => 'form-control')) }}
												{{ Form::hidden('bsorder[]', $balancesheetitem->order, array('id' => 'bsorder', 'class' => 'form-control')) }}
												{{ $balancesheetitem->name }}
												</td>
											<td>
												{{ Form::text('balancesheetitemy1[]', old('balancesheetitemy1[]'), array('id' => 'balancesheetitemy1[]', 'class' => 'form-control')) }}
											</td>
											<td>
												{{ Form::text('balancesheetitemy2[]', old('balancesheetitemy2[]'), array('id' => 'balancesheetitemy2[]', 'class' => 'form-control')) }}
											</td>
											<td>
												{{ Form::text('balancesheetitemy3[]', old('balancesheetitemy3[]'), array('id' => 'balancesheetitemy3[]', 'class' => 'form-control')) }}
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
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
						  @endforeach
						@endif 
					</div>
				</div>				<!-- end row 14 -->
			</div> <!-- end tab -->
		@endif
	</div>	
	<div class="row">	<!-- row 19 --> 
		<div class=" col-md-12"> <!-- column 1 -->
		@if (isset($mode))
			@if ($mode == 's')
				<a href="{{ url("/creditrequests/view/" . $creditrequest->id) }}" class="btn btn-primary fixedw_button" role="button" title="Save"><span class="glyphicon glyphicon-ok"></span></a>
			@else			
				@if (Gate::allows('cr_cr'))
					<div class="col-xs-3"> <!-- column 1 -->			
						<a href="{{ url("/creditrequests/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>										
					</div> <!-- column 1 end -->
				@endif
				@if (Gate::allows('cr_sc'))
					<div class="col-xs-3"> <!-- column 2 -->
						<a href="{{ url("/creditrequests") }}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				@endif
				@if (Gate::allows('cr_ch', $creditrequest->id) && ($creditrequest->creditstatus_id == 2 || $creditrequest->creditstatus_id == 4))
					<div class="col-xs-3"> <!-- column 3 -->
						@if ($creditrequest->personalguarantee || $creditrequest->corporateguarantee || $creditrequest->promissarynote || $creditrequest->securitycheck)
						@else
							<a href="{{ url("/creditrequests/" . $creditrequest->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
						@endif
					</div>
				@endif
				@if (Gate::allows('cr_ap') && $creditrequest->creditstatus_id == 2 && $mode == 'a')
					<div class="col-xs-3"> <!-- column 4 -->
						<a href="{{ url("/creditrequests/approve/" . $creditrequest->id) }}" class="btn btn-primary fixedw_button" role="button" title="Approval"><span class="glyphicon glyphicon-check"></span></a>
					</div>
				@endif				
			@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn btn-primary fixedw_button" id="lnksubmit" type="button" title="Save">
				<span class="glyphicon glyphicon-ok"></span>
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
			});
			$('.nav-tabs a').on('shown.bs.tab', function(event){
				var x = $(event.target).text();         
				var y = $(event.relatedTarget).text();  
				$(".act span").text(x);
				$(".prev span").text(y);
			});
			//tabs end
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
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelOwner" type="button"><span class="glyphicon glyphicon-trash" title="Add business refrence"></span></a>';
				row = row + '<input name="busrefid[]" type="hidden">';
				row = row + '<input name="busrefdel[]" id="busrefdel" type="hidden">';
				row = row + '</td>';
				row = row + '<td><input name="busrefname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="busreflimit[]" type="text" class="form-control"></td>';
				row = row + '<td><select name="busreftype[]" type="text" class="form-control"><option value="secured">Secured</option><option value="unsecured">Unsecured</option></select></td>';
				row = row + '<td><select name="busreflength[]" class="form-control">';
				@php
					if (isset($busreflengths)) {
						foreach ($busreflengths as $busreflength) {
							echo "row = row + '<option value=" . $busreflength->id . ">" . $busreflength->name . "</option>';";
						}
					}
				@endphp
				row = row + '</td>';
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
			$('#financialattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;				
				if (filesize > 2097152) {
					alert('Maximum file size is 2M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf']) == -1) {
					alert('File must be PDF');
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
						$('#financialfile').val(filename);
						$('#financialfilename').text(filename);
						$('#financialattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			}); //$('.financialattach').on('change', '.attach', () => {
			$('#bankattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('Maximum file size is 2M');
					return false;
				}
				if($.inArray(fileType.toLowerCase(), ['pdf']) == -1) {
					alert('File must be PDF');
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
						$('#bankfile').val(filename);
						$('#bankfilename').text(filename);
						$('#bankattachid').val(response);
                    },
                    error: function(e,a,b){
                        console.log(e,a,b);
                    }
                });
			}); //$('.bankattach').on('change', '.attach', () => {
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
				if($.inArray(fileType.toLowerCase(), ['pdf']) == -1) {
					alert('File must be PDF');
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
			}); //$('.personalguaranteeattach').on('change', '.attach', () => {
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
				if($.inArray(fileType.toLowerCase(), ['pdf']) == -1) {
					alert('File must be PDF');
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
			}); //$('.corporateguaranteeattach').on('change', '.attach', () => {
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
				if($.inArray(fileType.toLowerCase(), ['pdf']) == -1) {
					alert('File must be PDF');
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
			}); //$('.promissarynoteattach').on('change', '.attach', () => {
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
				if($.inArray(fileType.toLowerCase(), ['pdf']) == -1) {
					alert('File must be PDF');
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
				//tr.cells[3].getElementsByTagName("input")[0].value='A';
				//tr.cells[4].getElementsByTagName("input")[0].value='0';					
			}
			$("#busrefcount").val(parseInt($("#busrefcount").val()) - 1);	
			tr.style.display = 'none';
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
	</script>
@endpush