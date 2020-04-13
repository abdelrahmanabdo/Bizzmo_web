@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if (isset($vendor)) 
		{{ Form::model($vendor, array('id' => 'frmManage', 'files' => true)) }}
		{{ Form::hidden('id', $vendor->id, array('id' => 'id', 'class' => 'form-control')) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'files' => true)) }}
	@endif 
	@if (isset($mode))
		@if (!$vendor->confirmed)
			<div class="row">	<!-- row 10 -->
				<div class="col-sm-12"> <!-- Column 1 -->
					<div class="alert alert-danger">
						<p class="bg-danger"><strong>Not confirmed</strong></p>
						@if (Gate::allows('vn_cr'))
							<p class="bg-danger">The company data is not yet confirmed.</p>
						@else
							<p class="bg-danger">The user did not confirm this company data yet</p>
						@endif						
					</div>
				</div> <!-- Column 1 end -->
			</div> <!--row 10 end -->
		@elseif (!$vendor->active)
			<div class="row">	<!-- row 10 -->
				<div class="col-sm-12"> <!-- Column 1 -->
					<div class="alert alert-warning">
						<p class="bg-warning"><strong>Not active</strong></p>
						<p class="bg-warning">This company is not active</p>
					</div>
				</div> <!-- Column 1 end -->
			</div> <!--row 10 end -->
		@endif
	@endif
	<div class="row">	<!-- row 1 -->		
		<div class="col-sm-6">  <!-- Column 1 -->
			<div class="form-group"> <!-- Company name -->  
				{{ Form::label('companyname', 'Company name') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->companyname }}</p>
				@else					
					{{ Form::text('companyname', Input::old('companyname'), array('id' => 'companyname', 'class' => 'form-control')) }}								
					@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
				@endif
			</div> <!-- Company name -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-6">  <!-- Column 2 -->
			<div class="form-group"> <!-- address -->  
				{{ Form::label('address', 'address') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->address }}</p>
				@else					
					{{ Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control')) }}			
					@if ($errors->has('address')) <p class="bg-danger">{{ $errors->first('address') }}</p> @endif
				@endif
			</div> <!-- address end -->  
		</div>					<!-- end col 2 -->					
	</div>				<!-- end row 1 -->
	<div class="row">	<!-- row 2 -->
		<div class="col-sm-4">  <!-- Column 1 -->
			<div class="form-group"> <!-- district -->  
				{{ Form::label('district', 'District') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->district }}</p>
				@else					
					{{ Form::text('district', Input::old('district'), array('id' => 'district', 'class' => 'form-control')) }}			
					@if ($errors->has('district')) <p class="bg-danger">{{ $errors->first('district') }}</p> @endif
				@endif
			</div> <!-- district end -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-4">  <!-- Column 2 -->
			<div class="form-group"> <!-- country -->  
				{{ Form::label('country_id', 'Country') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->country->countryname }}</p>
				@else					
					{{ Form::select('country_id', $countries, Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control'))}}		
					@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
				@endif
			</div> <!-- country --> 			
		</div>					<!-- end col 2 -->
		<div class="col-sm-4">  <!-- Column 1 -->
			<div class="form-group"> <!-- city -->  
				{{ Form::label('city_id', 'City') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->city->cityname }}</p>
				@else					
					{{ Form::select('city_id', $cities, Input::old('city_id'),array('id' => 'city_id', 'class' => 'form-control'))}}		
					@if ($errors->has('city_id')) <p class="bg-danger">{{ $errors->first('city_id') }}</p> @endif
				@endif
			</div> <!-- city --> 			
		</div>					<!-- end col 1 -->		
	</div>				<!-- end row 2 -->
	<div class="row">	<!-- row 3 -->
		<div class="col-sm-3">  <!-- Column 1 -->
			<div class="form-group"> <!-- phone -->  
				{{ Form::label('phone', 'Phone') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->phone }}</p>
				@else					
					{{ Form::text('phone', Input::old('phone'), array('id' => 'phone', 'class' => 'form-control phone', 'placeholder' => '(000) 0 0000000')) }}			
					@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
				@endif
			</div> <!-- phone end -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-3">  <!-- Column 2 -->
			<div class="form-group"> <!-- fax -->  
				{{ Form::label('fax', 'Fax') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->fax }}</p>
				@else					
					{{ Form::text('fax', Input::old('fax'), array('id' => 'fax', 'class' => 'form-control phone', 'placeholder' => '(000) 0 0000000')) }}			
					@if ($errors->has('fax')) <p class="bg-danger">{{ $errors->first('fax') }}</p> @endif
				@endif
			</div> <!-- fax end -->  
		</div>					<!-- end col 2 -->
		<div class="col-sm-2">  <!-- Column 3 -->
			<div class="form-group"> <!-- pobox -->  
				{{ Form::label('pobox', 'PO Box') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->pobox }}</p>
				@else					
					{{ Form::text('pobox', Input::old('pobox'), array('id' => 'pobox', 'class' => 'form-control')) }}			
					@if ($errors->has('pobox')) <p class="bg-danger">{{ $errors->first('pobox') }}</p> @endif
				@endif
			</div> <!-- pobox end -->  
		</div>					<!-- end col 3 -->
		<div class="col-sm-4">  <!-- Column 4 -->
			<div class="form-group"> <!-- email -->  
				{{ Form::label('email', 'Email') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->email }}</p>
				@else					
					{{ Form::email('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'Placeholder' => 'Email of the contact person of the company')) }}			
					@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
				@endif
			</div> <!-- email end -->  
		</div>					<!-- end col 4 -->
	</div>				<!-- end row 3 -->
	<div class="row">	<!-- row 4 -->
		<div class="col-sm-2">  <!-- Column 1 -->
			<div class="form-group"> <!-- license -->  
				{{ Form::label('license', 'Trade license') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->license }}&nbsp;&nbsp;&nbsp;</p>
				@else					
					{{ Form::text('license', Input::old('license'), array('id' => 'license', 'class' => 'form-control')) }}					
					@if ($errors->has('license')) <p class="bg-danger">{{ $errors->first('license') }}</p> @endif
				@endif				
			</div> <!-- license end -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-2">  <!-- column 2 -->
			<div class="form-group"> <!-- license -->  
			{{ Form::label('tradelic', '&nbsp;') }}<br>
			@if (!isset($mode))
				<a href="#" class="btn btn-success" onclick="Uploadtradefile(this);return false;" id="lnkattach" alt="Upload PDF, JPG, JPEG or PNG file that has a copy of the Trade License" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the Trade License"><span class="glyphicon glyphicon-link"></span></a>			
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
						<a href="/{{ $vendor->attachments->first()->path }}" download="{{ $vendor->attachments->first()->path }}">{{ $vendor->attachments->first()->filename }}</a>
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
		<div class="col-sm-2">  <!-- column 3 -->
			<div class="form-group"> <!-- tax -->  
				{{ Form::label('tax', 'Tax ID') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->tax }}</p>
				@else					
					{{ Form::text('tax', Input::old('tax'), array('id' => 'tax', 'class' => 'form-control')) }}			
					@if ($errors->has('tax')) <p class="bg-danger">{{ $errors->first('tax') }}</p> @endif
				@endif
			</div> <!-- tax end -->  
		</div>					<!-- end col 3 -->
		<div class="col-sm-2">  <!-- column 4 -->
			<div class="form-group"> <!-- incorporated -->  
				{{ Form::label('incorporated', 'Incorporation date') }}
				@if (isset($mode))	
					<p class='form-control-static text-right'>{{ $vendor->incorporated }}</p>
				@else					
					{{ Form::text('incorporated', Input::old('incorporated'), array('id' => 'incorporated', 'class' => 'form-control')) }}			
					@if ($errors->has('incorporated')) <p class="bg-danger">{{ $errors->first('incorporated') }}</p> @endif
				@endif
			</div> <!-- incorporated end -->  
		</div>					<!-- end col 4 -->		
		<div class="col-sm-4">  <!-- column 5 -->
			<div class="form-group"> <!-- website -->  
				{{ Form::label('website', 'Company website') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->website }}</p>
				@else					
					{{ Form::text('website', Input::old('website'), array('id' => 'website', 'class' => 'form-control')) }}			
					@if ($errors->has('website')) <p class="bg-danger">{{ $errors->first('website') }}</p> @endif
				@endif
			</div> <!-- website end -->  
		</div>					<!-- end col 5 -->
	</div>				<!-- end row 4 -->
	<div class="row">	<!-- row 5 -->
		<div class="col-sm-2">  <!-- column 1 -->
			<div class="form-group"> <!-- articles of assoc -->  
			{{ Form::label('assoclic', 'Articles of Assoc.') }}<br>
			
			@if (!isset($mode))
				<a href="#" class="btn btn-success" onclick="Uploadassocfile(this);return false;" id="lnkattach" alt="Upload PDF, JPG, JPEG or PNG file that has a copy of the articles of association" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the articles of association"><span class="glyphicon glyphicon-link"></span></a>			
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
			<div class="form-group"> <!-- operating -->  
				{{ Form::label('operating', 'Operating in industries') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->operating }}</p>
				@else					
					{{ Form::text('operating', Input::old('operating'), array('id' => 'operating', 'class' => 'form-control', 'Placeholder' => 'Enter a comma separated list')) }}			
					@if ($errors->has('operating')) <p class="bg-danger">{{ $errors->first('operating') }}</p> @endif
				@endif				
			</div> <!-- operating end -->  
		</div>					<!-- end col 2 -->
		<div class="col-sm-2">  <!-- column 3 -->
			<div class="form-group"> <!-- employees -->  
				{{ Form::label('employees', 'No. of employees') }}
				@if (isset($mode))	
					<p class='form-control-static text-right'>{{ $vendor->employeenumber->name }}</p>
				@else					
					{{ Form::select('employees', $employees, Input::old('employees'),array('id' => 'employees', 'class' => 'form-control'))}}		
					@if ($errors->has('employees')) <p class="bg-danger">{{ $errors->first('employees') }}</p> @endif
				@endif
			</div> <!-- employees end -->  
		</div>					<!-- end col 3 -->
	</div>				<!-- end row 5 -->
	<div class="row">	<!-- row 6 -->
		<div class="col-sm-4">  <!-- column 1 -->
			<div class="form-group"> <!-- account name -->  
				{{ Form::label('accountname', 'Account name') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->accountname }}</p>
				@else					
					{{ Form::text('accountname', Input::old('accountname'), array('id' => 'accountname', 'class' => 'form-control')) }}								
					@if ($errors->has('accountname')) <p class="bg-danger">{{ $errors->first('accountname') }}</p> @endif
				@endif
			</div> <!-- Bank name -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-4">  <!-- column 2 -->
			<div class="form-group"> <!-- bank name -->  
				{{ Form::label('bankname', 'Bank name') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->bankname }}</p>
				@else					
					{{ Form::text('bankname', Input::old('bankname'), array('id' => 'bankname', 'class' => 'form-control')) }}								
					@if ($errors->has('bankname')) <p class="bg-danger">{{ $errors->first('bankname') }}</p> @endif
				@endif
			</div> <!-- bank name -->  
		</div>					<!-- end col 2 -->
		<div class="col-sm-4">  <!-- column 3 -->
			<div class="form-group"> <!-- account number -->  
				{{ Form::label('accountnumber', 'Account number') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->accountnumber }}</p>
				@else					
					{{ Form::text('accountnumber', Input::old('accountnumber'), array('id' => 'accountnumber', 'class' => 'form-control')) }}								
					@if ($errors->has('accountnumber')) <p class="bg-danger">{{ $errors->first('accountnumber') }}</p> @endif
				@endif
			</div> <!-- account number -->  
		</div>					<!-- end col 3 -->	
	</div>				<!-- end row 6 -->
	<div class="row">	<!-- row 7 -->
		<div class="col-sm-4">  <!-- column 1 -->
			<div class="form-group"> <!-- iban -->  
				{{ Form::label('iban', 'IBAN') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->iban }}</p>
				@else					
					{{ Form::text('iban', Input::old('iban'), array('id' => 'iban', 'class' => 'form-control')) }}								
					@if ($errors->has('iban')) <p class="bg-danger">{{ $errors->first('iban') }}</p> @endif
				@endif
			</div> <!-- iban -->  
		</div>					<!-- end col 1 -->
		<div class="col-sm-4">  <!-- column 2 -->
			<div class="form-group"> <!-- routing code -->  
				{{ Form::label('routingcode', 'Routing code') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->routingcode }}</p>
				@else					
					{{ Form::text('routingcode', Input::old('routingcode'), array('id' => 'routingcode', 'class' => 'form-control')) }}								
					@if ($errors->has('routingcode')) <p class="bg-danger">{{ $errors->first('routingcode') }}</p> @endif
				@endif
			</div> <!-- routing code -->  
		</div>					<!-- end col 2 -->
		<div class="col-sm-4">  <!-- column 3 -->
			<div class="form-group"> <!-- swift -->  
				{{ Form::label('swift', 'SWIFT code') }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $vendor->swift }}</p>
				@else					
					{{ Form::text('swift', Input::old('swift'), array('id' => 'swift', 'class' => 'form-control')) }}								
					@if ($errors->has('swift')) <p class="bg-danger">{{ $errors->first('swift') }}</p> @endif
				@endif
			</div> <!-- swift -->  
		</div>					<!-- end col 3 -->
	</div>				<!-- end row 7 -->
	<div class="row">	<!-- row 8 -->
		<div class=" col-sm-6"> <!-- Column 1 -->
			<h4>Top 5 brands</h4>
			<?php $topproductcount = 0; ?>
			<table id="topproducttable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						@if (!isset($mode))
							<th class="no-sort" width="10%">
								<a href="" id="lnktopproduct" role="button" class="btn btn-info"><span class="glyphicon glyphicon-plus" title="Add brand"></span></a>	
							</th>
						@endif
						<th>Brand</th>
						<th>Revenue</th>
					</tr>		
				</thead>
				<tbody>
					@if (old('topproductid'))
						@php
							$i = 0;
						@endphp
						@foreach (old('topproductid') as $item)
							<tr style="{{ (old('topproductdel')[$i]) ? 'display:none' : '' }}">
								<td>
									<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelProduct"><span class="glyphicon glyphicon-trash"></span></a>
									{{ Form::hidden('topproductid[]', old('topproductid')[$i], array('id' => 'topproduct_id')) }}
									{{ Form::hidden('topproductdel[]', old('topproductdel')[$i], array('id' => 'topproductdel', 'class' => 'form-control')) }}
								</td>
								<td>
									{{ Form::text('topproductname[]', old('topproductname')[$i], array('id' => 'topproductname', 'class' => 'form-control')) }}
									@if ($errors->has('topproductname.' . $i)) <p class="bg-danger">{{ $errors->first('topproductname.' . $i) }}</p> @endif
								</td>
								<td>
									{{ Form::select('topproductrevenue[]', $arrpercentages, Input::old('topproductrevenue')[$i], array('id' => 'topproductrevenue', 'class' => 'form-control'))}}		
									@if ($errors->has('topproductrevenue.' . $i)) <p class="bg-danger">{{ $errors->first('topproductrevenue.' . $i) }}</p> @endif
								</td>
							</tr>
						@php
							$i++;
							$topproductcount = $i;
						@endphp	
						@endforeach
					@else
						@if (isset($vendor))
							<?php $i = 0 ; ?>
							@foreach ($vendor->vendortopproducts as $topproduct)
								<tr>							
									@if (isset($mode))								
										<td>{{ $topproduct->topproductname }}</td>
										<td align="right">{{ $topproduct->revenue->name }}</td>
										
									@else
										<td>
											<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelTopproduct"><span class="glyphicon glyphicon-trash" type="button"></span></a>
											{{ Form::hidden('topproductid[]', $topproduct->id, array('id' => 'topproduct_id')) }}
											{{ Form::hidden('topproductdel[]', '', array('id' => 'topproductdel', 'class' => 'form-control')) }}
										</td>
										<td>{{ Form::text('topproductname[]', $topproduct->topproductname, array('id' => 'topproductname', 'class' => 'form-control')) }}</td>
										<td>{{ Form::select('topproductrevenue[]', $arrpercentages, $topproduct->topproductrevenue,array('id' => 'topproductrevenue', 'class' => 'form-control'))}} </td>
										</td>
									@endif
								</tr>
								<?php $i = $i + 1 ; 
									$topproductcount = $i;
								?>
							@endforeach
						@endif
					@endif
				</tbody>
			</table>
			<input type="hidden" name="topproductcount" id="topproductcount" value="{{ old('topproductcount', $topproductcount) }}">
			@if ($errors->has('topproductcount')) <p class="bg-danger">{{ $errors->first('topproductcount') }}</p> @endif
		</div> <!-- Column 2 end -->
		<div class=" col-sm-6"> <!-- Column 2 -->
			<h4>Top 5 buyers</h4>
			<?php $topcustomercount = 0; ?>
			<table id="topcustomertable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						@if (!isset($mode))
							<th class="no-sort" width="10%">
								<a href="" id="lnktopcustomer" role="button" class="btn btn-info"><span class="glyphicon glyphicon-plus" title="Add customer"></span></a>	
							</th>
						@endif
						<th>Buyer</th>
					</tr>		
				</thead>
				<tbody>
					@if (old('topcustomerid'))
						@php
							$i = 0;
						@endphp
						@foreach (old('topcustomerid') as $item)
							<tr style="{{ (old('topcustomerdel')[$i]) ? 'display:none' : '' }}">
								<td>
									<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelProduct"><span class="glyphicon glyphicon-trash"></span></a>
									{{ Form::hidden('topcustomerid[]', old('topcustomerid')[$i], array('id' => 'topcustomer_id')) }}
									{{ Form::hidden('topcustomerdel[]', old('topcustomerdel')[$i], array('id' => 'topcustomerdel', 'class' => 'form-control')) }}
								</td>
								<td>
									{{ Form::text('topcustomername[]', old('topcustomername')[$i], array('id' => 'topcustomername', 'class' => 'form-control')) }}
									@if ($errors->has('topcustomername.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomername.' . $i) }}</p> @endif
								</td>
							</tr>
						@php
							$i++;
							$topcustomercount = $i;
						@endphp	
						@endforeach
					@else
						@if (isset($vendor))
							<?php $i = 0 ; ?>
							@foreach ($vendor->vendortopcustomers as $topcustomer)
								<tr>							
									@if (isset($mode))								
										<td>{{ $topcustomer->topcustomername }}</td>
									@else
										<td>
											<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelTopcustomer"><span class="glyphicon glyphicon-trash" type="button"></span></a>
											{{ Form::hidden('topcustomerid[]', $topcustomer->id, array('id' => 'topcustomer_id')) }}
											{{ Form::hidden('topcustomerdel[]', '', array('id' => 'topcustomerdel', 'class' => 'form-control')) }}
										</td>
										<td>{{ Form::text('topcustomername[]', $topcustomer->topcustomername, array('id' => 'topcustomername', 'class' => 'form-control')) }}</td>
										</td>
									@endif
								</tr>
								<?php $i = $i + 1 ; 
									$topcustomercount = $i;
								?>
							@endforeach
						@endif
					@endif
				</tbody>
			</table>
			<input type="hidden" name="topcustomercount" id="topcustomercount" value="{{ old('topcustomercount', $topcustomercount) }}">
			@if ($errors->has('topcustomercount')) <p class="bg-danger">{{ $errors->first('topcustomercount') }}</p> @endif
		</div> <!-- Column 2 end -->
	</div>				<!-- end row 8 -->
	<div class="row">	<!-- row 9 --> 
		<div class=" col-sm-12"> <!-- Column 1 -->
		@if (isset($mode))
			@if ($vendor->confirmed)
				@if (Gate::allows('vn_cr'))
					<div class="col-xs-4"> <!-- Column 1 -->			
						<a href="{{ url("/vendors/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>						
					</div> <!-- Column 1 end -->
				@endif
				@if (Gate::allows('vn_sc'))
					<div class="col-xs-4"> <!-- Column 2 -->
						<a href="{{ url("/vendors") }}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				@endif
				@if (Gate::allows('vn_ch', $vendor->id))
					<div class="col-xs-4"> <!-- Column 3 -->
						<a href="{{ url("/vendors/" . $vendor->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
					</div>
				@endif
			@else
				@if (Gate::allows('vn_cr'))
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
				@if (Gate::allows('vn_sc'))
					<div class="col-xs-6"> <!-- Column 2 -->
						<a href="{{ url("/vendors") }}" class="btn btn-info fixedw_button" role="button" title="Search"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				@endif
				@if (Gate::allows('vn_ch', $vendor->id))
					<div class="col-xs-6"> <!-- Column 3 -->
						<a href="{{ url("/vendors/" . $vendor->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
					</div>
				@endif
				</div>
			@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn btn-primary fixedw_button" id="lnksubmit" type="button" title="Save">
				<span class="glyphicon glyphicon-ok"></span>
			</a>
		@endif 	
		</div> <!-- Column 1 end -->
	</div> <!--row 9 end -->
	{{ Form::close() }}
@stop	
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			Updatecity();
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
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
			var phonemask = '(000) 0 0000000';
			var mobilemask = '(000) 00 0000000';
			$('.phone').mask(phonemask);
			$('.mobile').mask(mobilemask);
			$("#country_id").change(function(){
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
						$.each(data, function(i, item) {
							$('#city_id').append($("<option></option>").attr("value", i).text(item));
						});
					}, // End of success function of ajax form
					error: function(output_string){				
						alert(jxhr.responseText);
					}
				}); //ajax call end
			}); // $("#country_id").change end
			
			$( "#incorporated" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
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
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelOwner" type="button"><span class="glyphicon glyphicon-trash" title="Delete owner"></span></a>&nbsp;';
				row = row + '<a href="#" class="btn btn-success" onclick="Uploadfile(this);return false;" id="lnkattach" alt="Upload attachment" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the visa and ID (or passport)"><span class="glyphicon glyphicon-link"></span></a>',
				row = row + '<input name="ownerid[]" type="hidden" class="form-control">';
				row = row + '<input name="ownerdel[]" id="ownerdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="ownername[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="owneremail[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="ownerphone[]" type="text" class="form-control mobile" placeholder="(000) 00 0000000"></td>';
				row = row + '<td><input name="ownershare[]" type="text" class="form-control"></td>';
				row = row + '<td><span></span><input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<input name="ownerfile[]" id="ownerfile" type="hidden"><input name="ownerattachid[]" id="ownerattachid" type="hidden"></td>';
				row = row + '</tr>';
				$('#ownertable').append(row);
				$("#ownercount").val(parseInt($("#ownercount").val()) + 1);
				$('.mobile').mask(mobilemask);
			});
			$("#lnkdirector").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('directortable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelPDirector" type="button"><span class="glyphicon glyphicon-trash" title="Add director"></span></a>&nbsp;';
				row = row + '<a href="#" class="btn btn-success" onclick="Uploadfile(this);return false;" id="lnkattach" alt="Upload attachment" title="Upload PDF, JPG, JPEG or PNG file that has a copy of the visa and ID (or passport)"><span class="glyphicon glyphicon-link"></span></a>',
				row = row + '<input name="directorid[]" type="hidden" class="form-control">';
				row = row + '<input name="directordel[]" id="directordel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="directorname[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="directortitle[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="directoremail[]" type="text" class="form-control"></td>';
				row = row + '<td><input name="directorphone[]" type="text" class="form-control mobile" placeholder="(000) 00 0000000"></td>';
				row = row + '<td><span></span><input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<input name="directorfile[]" id="directorfile" type="hidden"><input name="directorattachid[]" id="directorattachid" type="hidden"></td>';
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
				row = row + '<td><select name="topproductrevenue[]" class="form-control">';
				@php
					if (isset($percentages)) {
						foreach ($percentages as $percentage) {
							echo "row = row + '<option value=" . $percentage->id . ">" . $percentage->name . "</option>';";
						}
					}
				@endphp
				row = row + '</td>';
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
				row = row + '<a href="#" class="btn btn-info" onclick="DelRow(this);return false;" id="btnDelPTopcustomer" type="button"><span class="glyphicon glyphicon-trash" title="Delete customer"></span></a>';
				row = row + '<input name="topcustomerid[]" type="hidden" class="form-control">';
				row = row + '<input name="topcustomerdel[]" id="topcustomerdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><input name="topcustomername[]" type="text" class="form-control"></td>';
				row = row + '</tr>';
				$('#topcustomertable').append(row);							
				$("#topcustomercount").val(parseInt($("#topcustomercount").val()) + 1);
			});
			$('.table').on('change', '.attach', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('حجم الملف اكبر من المسموح');
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
					switch (table.rows[0].cells[i].innerHTML) {
						case 'Attachment':
							var attachmentcell = i;
							break;
					}
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
						tr.cells[attachmentcell].getElementsByTagName("input")[1].value = filename;
						tr.cells[attachmentcell].getElementsByTagName("input")[2].value = response;
						tr.cells[attachmentcell].getElementsByTagName("span")[0].innerText = filename;
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
					alert('حجم الملف اكبر من المسموح');
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
			}); //$('.table').on('change', '.attach', () => {
			
			$('#assocattach').on('change', (event) => {
				var fileInput = event.target,
					file = event.target.files[0],
					fileType = file.type.split('/')[1];
				var filename = file.name;
				var filesize = file.size;
				if (filesize > 2097152) {
					alert('حجم الملف اكبر من المسموح');
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
						$("#ownercount").val(parseInt($("#ownercount").val()) - 1);
					} else if (inputval == 'directordel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						tr.cells[2].getElementsByTagName("input")[0].value='A';
						tr.cells[3].getElementsByTagName("input")[0].value='A@a.a';
						tr.cells[4].getElementsByTagName("input")[0].value='A';
						$("#directorcount").val(parseInt($("#directorcount").val()) - 1);
					} else if (inputval == 'topproductdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						//tr.cells[2].getElementsByTagName("input")[0].value='0';
						$("#topproductcount").val(parseInt($("#topproductcount").val()) - 1);
					} else if (inputval == 'topcustomerdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						//tr.cells[2].getElementsByTagName("input")[0].value='0';
						$("#topcustomercount").val(parseInt($("#topcustomercount").val()) - 1);
					}
					
				}
			tr.style.display = 'none';
		}
		function Uploadfile(lnk) {
			var table =lnk.parentNode.parentNode.parentNode.parentNode;
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
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

		$(document).ready(function(){
			$(".cal-icon").on('click', function () {
				$("#incorporated").focus()
			})
		});
	</script>
@endpush