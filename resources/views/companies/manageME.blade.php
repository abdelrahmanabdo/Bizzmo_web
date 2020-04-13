@extends('layouts.app')
@section('title')
	@if (isset($title))
		{{ $title }}
	@endif
@stop
@section('styles')
	<style>
		.select2 {
		width: 100% !important;
		}
		.form-horizontal .control-label{
			text-align: left;
		}
	</style>
@stop
@section('content')	
	@php
		$activetab = 'BasicInfo';
		$nexttabname = '';
		$action_title = 'Create';
	@endphp
	@if (isset($company))
		{{ Form::model($company, array('id' => 'frmManage', 'class' => 'form-horizontal co-form', 'files' => true)) }}
		{{ Form::hidden('id', $company->id, array('id' => 'id', 'class' => 'form-control')) }}
		@if (isset($mode))
			<div class="row flex-container bm-pg-header">	<!-- row 1 -->
				<h2 class="bm-pg-title">{{ $title}}</h2>
			</div>
		@endif
		@php
			$action_title = 'Edit';
			if ($errors->has('companyname') || $errors->has('address') || $errors->has('district') || $errors->has('phone') || $errors->has('fax') || $errors->has('pobox') || $errors->has('email') || $errors->has('license') || $errors->has('tradefile') || $errors->has('tax') || $errors->has('incorporated') || $errors->has('website') || $errors->has('assocfile') || $errors->has('industries')) 
				$activetab = 'BasicInfo';
			elseif ($errors->has('ownername.*') || $errors->has('owneremail.*') || $errors->has('ownerphone.*') || $errors->has('ownershare.*') || $errors->has('ownerattach.*') || $errors->has('ownercount') || $errors->has('ownershare') || $errors->has('shares') || $errors->has('beneficialname.*') || $errors->has('beneficialemail.*') || $errors->has('beneficialphone.*') || $errors->has('beneficialshare.*') || $errors->has('beneficialattach.*') || $errors->has('beneficialcount') || $errors->has('beneficialshare') || $errors->has('shares')) 
				$activetab = 'Shareholders';
			elseif ($errors->has('directorname.*') || $errors->has('directortitle.*') || $errors->has('directoremail.*') || $errors->has('directorphone.*') || $errors->has('directorattach.*') || $errors->has('directorcount')) 
				$activetab = 'Directors';
			elseif ($errors->has('accountname') || $errors->has('bankname') || $errors->has('accountnumber') || $errors->has('iban') || $errors->has('routingcode') || $errors->has('swift')) 
				$activetab = 'BankData';
			elseif ($errors->has('topproductname.*') || $errors->has('topproductrevenue.*') || $errors->has('topproductcount') || $errors->has('topproductsum') || $errors->has('topcustomername.*') || $errors->has('topcustomercount') || $errors->has('topsuppliername.*') || $errors->has('topsuppliercount')) 
				$activetab = 'Business';
			elseif (strpos(url()->current(), 'BasicInfo')) 
				$activetab = 'BasicInfo';
			elseif (strpos(url()->current(), 'Shareholders')) 
				$activetab = 'Shareholders';
			elseif (strpos(url()->current(), 'Directors')) 
				$activetab = 'Directors';
			elseif (strpos(url()->current(), 'BankData') && ($company->companytype_id == 2 || $company->companytype_id == 3))
				$activetab = 'BankData';
			elseif (strpos(url()->current(), 'Business')) 
				$activetab = 'Business';
			elseif (old('newtab') != '') 
				$activetab = old('newtab');
			elseif ($company->basicinfo != 1) 
				$activetab = 'BasicInfo';
			elseif ($company->shareholders != 1) 
				$activetab = 'Shareholders';
			elseif ($company->banks != 1 && ($company->companytype_id == 2 || $company->companytype_id == 3)) 
				$activetab = 'BankData';
			elseif ($company->directors != 1) 
				$activetab = 'Directors';
			elseif ($company->business != 1) 
				$activetab = 'Business';

			//echo $activetab;
			//die;
		@endphp
	@else
		{{ Form::open(array('id' => 'frmManage', 'class' => 'form-horizontal co-form', 'files' => true)) }}
	@endif
	{{ Form::hidden('activetab', $activetab, array('id' => 'activetab')) }}
	{{ Form::hidden('newtab', old('newtab'), array('id' => 'newtab')) }}
	@if (old('onetab'))
		{{ Form::hidden('onetab', old('onetab'), array('id' => 'onetab')) }}
	@else
		{{ Form::hidden('onetab', $onetab, array('id' => 'onetab')) }}
	@endif	
	{{ Form::hidden('tmptradefilename', old('tmptradefilename'), array('id' => 'tmptradefilename')) }}	
	{{ Form::hidden('tmpassocfilename', old('tmpassocfilename'), array('id' => 'tmpassocfilename')) }}	
	{{ Form::hidden('tmp_tax_file_name', old('tmp_tax_file_name'), array('id' => 'tmp_tax_file_name')) }}
	<div class="tab-content">
		@if (isset($mode))				
			@if (!$company->confirmed)
				<div class="row" >	<!-- row 10 -->
					<div class="col-sm-12" style="padding:0;margin:0 -15px"> <!-- Column 1 -->
						<div class="alert alert-danger">
							<p class="bg-danger"><strong>Review and submit</strong></p>
							@if (Gate::allows('co_cr'))
								<p class="bg-danger">Review company data and submit.</p>
							@else
								<p class="bg-danger">The user did not review this company data yet</p>
							@endif						
						</div>
					</div> <!-- Column 1 end -->
				</div> <!--row 10 end -->
			@elseif (!$company->active)
				<div class="row">	<!-- row 10 -->
					<div class="col-sm-12"> <!-- Column 1 -->
						<div class="alert alert-warning">
							<p class="bg-warning"><strong>Not active</strong></p>
							<p class="bg-warning">This company is not active</p>
						</div>
					</div> <!-- Column 1 end -->
				</div> <!--row 10 end -->
			@endif
		@else
			<div class="row bm-pg-header">	<!-- row 10 -->
				<h2 class="bm-pg-title">{{ $action_title }} Company</h2>
				<div class="tabs-holder">
					<div class="tab-container" id="divbasicinfo"> <!-- Column 1 -->
						<span id="tabbasicinfo" class="tab {{ $activetab == 'BasicInfo' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'BasicInfo' ? 'Basic Info' : '' }}
							<svg class="icon hidden" role="img" viewBox="0 0 22.8 16.6">
								<path fill="currentColor" d="M8.2,16.6A2,2,0,0,1,6.8,16L0,9.2,2.8,6.4l5.4,5.4L20,0l2.8,2.8L9.6,16A2,2,0,0,1,8.2,16.6Z"></path>
							</svg>
						</span>
						<i class="fa fa-chevron-right"></i>					
					</div>
					<div class="tab-container" id="divshareholders"> <!-- Column 1 -->
						<span id="tabshareholders" class="tab {{ $activetab == 'Shareholders' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'Shareholders' ? 'Shareholders' : '' }}
							<svg class="icon hidden" role="img" viewBox="0 0 22.8 16.6">
								<path fill="currentColor" d="M8.2,16.6A2,2,0,0,1,6.8,16L0,9.2,2.8,6.4l5.4,5.4L20,0l2.8,2.8L9.6,16A2,2,0,0,1,8.2,16.6Z"></path>
							</svg>
						</span>
						<i class="fa fa-chevron-right"></i>
					</div>
					<div class="tab-container" id="divdirectors"> <!-- Column 1 -->
						<span id="tabdirectors" class="tab {{ $activetab == 'Directors' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'Directors' ? 'Directors' : '' }}
							<svg class="icon hidden" role="img" viewBox="0 0 22.8 16.6">
								<path fill="currentColor" d="M8.2,16.6A2,2,0,0,1,6.8,16L0,9.2,2.8,6.4l5.4,5.4L20,0l2.8,2.8L9.6,16A2,2,0,0,1,8.2,16.6Z"></path>
							</svg>
						</span>
						<i class="fa fa-chevron-right"></i>
					</div>
					<div class="tab-container" id="divbanks"> <!-- Column 1 -->
						<span id="tabbanks" class="tab {{ $activetab == 'BankData' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'BankData' ? 'Bank Details' : '' }}
							<svg class="icon hidden" role="img" viewBox="0 0 22.8 16.6">
								<path fill="currentColor" d="M8.2,16.6A2,2,0,0,1,6.8,16L0,9.2,2.8,6.4l5.4,5.4L20,0l2.8,2.8L9.6,16A2,2,0,0,1,8.2,16.6Z"></path>
							</svg>
						</span>
						<i class="fa fa-chevron-right"></i>					
					</div>
					<div class="tab-container" id="divbusiness"> <!-- Column 1 -->
						<span id="tabbusiness" class="tab {{ $activetab == 'Business' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'Business' ? 'Business' : '' }}
							<svg class="icon hidden" role="img" viewBox="0 0 22.8 16.6">
								<path fill="currentColor" d="M8.2,16.6A2,2,0,0,1,6.8,16L0,9.2,2.8,6.4l5.4,5.4L20,0l2.8,2.8L9.6,16A2,2,0,0,1,8.2,16.6Z"></path>
							</svg>
						</span>
					</div>
				</div>
			</div>
		@endif
		@if (isset($mode))
			@php $activetab = 'BasicInfo'; @endphp
		@endif
		<div id="basicinfo" class="row {{ $activetab == 'BasicInfo' ? '' : 'hidden' }}">
			@if (isset($mode) || (Gate::allows('co_ch') && isset($company) && isset($mode) && !$company->confirmed))
			<div class="row">	<!-- row 10 -->
				@if (isset($mode))
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Basic Info</h4>
					</div>
				@endif
				@if (Gate::allows('co_ch') && isset($company) && isset($mode) && !$company->confirmed)
					<div class="col-sm-8 edit-icon-view d-ib"> <!-- Column 1 -->
						<a href="{{ url("/company/" . $company->id) . '/BasicInfo' }}"><span class="edit-icon--with-border"></span></a>
					</div>
				@endif
			</div>
			@endif
		<div>  <!-- Column 1 -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- Company name -->  
						{{ Form::label('companyname', 'Company Name', array('class' => 'bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->companyname }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							{{ Form::text('companyname', Input::old('companyname'), array('id' => 'companyname', 'class' => 'form-control')) }}
							@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- Company name -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- address -->  
						{{ Form::label('address', 'Address', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))
							<p class='form-control-static col-sm-9'>{{ $company->address }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							{{ Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control')) }}			
							@if ($errors->has('address')) <p class="bg-danger">{{ $errors->first('address') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- address end -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- country -->  
						{{ Form::label('country_id', 'Country', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->country->countryname }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							@php
								if(Input::old('country_id') || (isset($company) && $company->country_id))
									$country_id = Input::old('country_id') ? Input::old('country_id') : $company->country_id;
							@endphp
							<select name="country_id" class="form-control bm-select" id="country_id">
							@foreach ($countries as $country)
								@php
									$selected = false;
									if((isset($country_id) && $country_id == $country->id) 
										|| (!isset($country_id) && $initial_country_id == $country->id))
										$selected = true;
								@endphp
								<option data-allowed="{{ $country->allowed }}" value="{{ $country->id }}" <?= $selected ? 'selected' : '' ?> >{{ $country->countryname }} ({{ $country->isocode }})</option>
							@endforeach
							</select>
							<!-- {{ Form::select('country_id', $countries, Input::old('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		 -->
							@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- country -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- city -->  
						{{ Form::label('city_id', 'City', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->city->cityname }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							@php
								if(Input::old('city_id') || (isset($company) && $company->city_id))
									$city_id = Input::old('city_id') ? Input::old('city_id') : $company->city_id;
							@endphp
							<select name="city_id" class="form-control bm-select" id="city_id">
								@foreach ($cities as $city)
								<option value="{{ $city->id }}" <?= (isset($city_id) && $city_id == $city->id) ? 'selected' : '' ?> >{{ $city->cityname }}</option>
								@endforeach
							</select>
							@if ($errors->has('city_id')) <p class="bg-danger">{{ $errors->first('city_id') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- city -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- phone -->  
						{{ Form::label('phone', 'Phone', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->phone }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('phone', Input::old('phone'), array('id' => 'phone', 'class' => 'form-control phone', 'placeholder' => '+00000000000000')) }}
								@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- phone end --> 
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- fax -->  
						{{ Form::label('fax', 'Fax', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->fax }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">				
							{{ Form::text('fax', Input::old('fax'), array('id' => 'fax', 'class' => 'form-control phone', 'placeholder' => '+00000000000000')) }}
							@if ($errors->has('fax')) <p class="bg-danger">{{ $errors->first('fax') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- fax end -->
				<div class="col-sm-12"> 
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : ''}}">  <!-- pobox -->  
						{{ Form::label('pobox', 'PO Box', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->pobox }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">				
							{{ Form::text('pobox', Input::old('pobox'), array('id' => 'pobox', 'class' => 'form-control')) }}			
							@if ($errors->has('pobox')) <p class="bg-danger">{{ $errors->first('pobox') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- pobox end -->

				@if(isset($mode) && Gate::allows('cr_ap'))
					@if($company->isCustomer())					
						<div class="col-sm-12"> 
							<div class="form-group {{ isset($mode) ? 'form-group--view'  : ''}}">  <!-- sapnumber -->  
								{{ Form::label('sapnumber', 'SAP Customer Number', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
								<p class='form-control-static col-sm-9'>{{ $company->sapnumber }}</p>
							</div> 
						</div><!-- sapnumber end -->
					@endif
					@if($company->isVendor())					
					<div class="col-sm-12"> 
						<div class="form-group {{ isset($mode) ? 'form-group--view'  : ''}}">  <!-- sapvendornumber -->  
							{{ Form::label('sapvendornumber', 'SAP Vendor Number', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
							<p class='form-control-static col-sm-9'>{{ $company->sapvendornumber }}</p>
						</div> 
					</div><!-- sapvendornumber end -->
					@endif
				@endif
				<div class="col-sm-12"> 
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- email -->  
						{{ Form::label('email', 'Email', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->email }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							{{ Form::email('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'Placeholder' => 'Email of the contact person of the company')) }}			
							@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- email end -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- companytype -->  
						{{ Form::label('companytype_id', 'Function', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->companytype->name }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							<div id="comp_type_wrapper" style="display: none;">
								<div class="radio">
									<label class="checkbox">
										@if((old('companytype_id') == '' && (!isset($company)))
										|| (!empty(old('companytype_id')[0]) && !empty(old('companytype_id')[1]))
										|| (isset($company) && $company->companytype_id == 3)
										|| (!empty(old('companytype_id')[0]) && old('companytype_id')[0] == 1)
										|| (isset($company) && $company->companytype_id == 1))
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_buyer" value="1" checked>
										@else
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_buyer" value="1">
										@endif
										
										<span class="checkmark"></span>
										<span class="bm-sublabel">Buyer</span> 
									</label>
									<small>Choose this option if the company is a buyer</small>
								</div>
								<div class="radio">
									<label class="checkbox">
										@if((!empty(old('companytype_id')[0]) && old('companytype_id')[0] == 2) 
										|| (!empty(old('companytype_id')[0]) && !empty(old('companytype_id')[1]))
										|| (isset($company) && $company->companytype_id == 3)
										|| (isset($company) && $company->companytype_id == 2))
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_supplier" value="2" checked>
										@else
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_supplier" value="2">
										@endif
										<span class="checkmark"></span>
										<span class="bm-sublabel">Supplier</span>
									</label>
									<small>Choose this option if the company is a supplier</small>
								</div>
								@if ($errors->has('companytype_id')) <p class="bg-danger">{{ $errors->first('companytype_id') }}</p> @endif
							</div>
							<div id="supplier_only">
								<p style="margin-bottom: 2px;font-weight: bold;">Supplier</p>
								<small>Other types are only available in <b>Saudi Arabia</b> & <b>United Arab Emirates</b></small>
							</div>
						</div>
						@endif
					</div> 
				</div>
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- license -->  
						{{ Form::label('tradelic', 'Trade License', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
						@if (isset($mode))
							<p class='form-control-static' style="margin-bottom:0">{{ $company->license }}</p>
						@endif
						@if (old('tradefile'))
							<span id="tradefilename" name="tradefilename">Selected file: {{ old('tradefile') }}</span>
							<input name="tradefile" id="tradefile" type="hidden" value="{{ old('tradefile') }}">
							<input name="tradeattachid" id="tradeattachid" type="hidden" value="{{ old('tradeattachid') }}">								
						@else
							@if (isset($tradeattachment))
								@if (isset($mode))
									<a style="bottom:15px;position:relative" href="/{{ $tradeattachment->path }}" download="{{ $tradeattachment->path }}">{{ $tradeattachment->filename }}</a>
								@else
									<span id="tradefilename" name="tradefilename">{{ $tradeattachment->filename }}</span>
								@endif
								<input name="tradefile" id="tradefile" type="hidden" value="{{ $tradeattachment->filename }}">
								<input name="tradeattachid" id="tradeattachid" type="hidden" value="{{ $tradeattachment->id }}">
							@else
								<span id="tradefilename" name="tradefilename">No file attached</span>
								<input name="tradefile" id="tradefile" type="hidden">
								<input name="tradeattachid" id="tradeattachid" type="hidden">									
							@endif
						@endif
						@if ($errors->has('tradefile')) <p class="bg-danger">{{ $errors->first('tradefile') }}</p> @endif
						@if (!isset($mode))
							<div class="flex-container">
								{{ Form::text('license', Input::old('license'), array('id' => 'license', 'class' => 'form-control input-with-icon')) }}
								<br>
								<a href="#" class="col-sm-9 attach-icon" onclick="Uploadtradefile(this);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>								
								<input type="file" name="tradeattach" id="tradeattach" class="tradeattach" style="display:none;">
							</div>
							<progress id="progressBar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<div><small class='form-control-static'>Use only PDF, JPEG, JPG, PNG files. Maximum file size is 2M.</small></div>
						@endif
						@if ($errors->has('license')) <p class="bg-danger">{{ $errors->first('license') }}</p> @endif
						</div>
					</div>
				</div><!-- end col 1 -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- tax -->  
						{{ Form::label('tax', 'Tax Certificate', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
						@if (isset($mode))
							<p class='form-control-static' style="margin-bottom:0">{{ $company->tax }}</p>
						@endif
						@if (old('taxfile'))
							<span id="tax_file_name" name="taxFileName">Selected file: {{ old('tradefile') }}</span>
							<input name="taxFile" id="tax_file" type="hidden" value="{{ old('taxfile') }}">
							<input name="taxAttachId" id="tax_attach_id" type="hidden" value="{{ old('tax_attach_id') }}">								
						@else
							@if (isset($taxAttachment))
								@if (isset($mode))
								<a style="bottom:15px;position:relative" href="/{{ $taxAttachment->path }}" download="{{ $taxAttachment->path }}">{{ $taxAttachment->filename }}</a>
								@else
								<span id="tax_file_name" name="taxFileName">{{ $taxAttachment->filename }}</span>
								@endif
								<input name="taxFile" id="tax_file" type="hidden" value="{{ $taxAttachment->filename }}">
								<input name="taxAttachId" id="tax_attach_id" type="hidden" value="{{ $taxAttachment->id }}">
							@else
								<span id="tax_file_name" name="taxFileName">No file attached</span>
								<input name="taxFile" id="tax_file" type="hidden">
								<input name="taxAttachId" id="tax_attach_id" type="hidden">									
							@endif
						@endif
						@if ($errors->has('taxfile')) <p class="bg-danger">{{ $errors->first('taxfile') }}</p> @endif
						@if (!isset($mode))
							<div class="flex-container">
								{{ Form::text('tax', Input::old('tax'), array('id' => 'tax', 'class' => 'form-control input-with-icon')) }}
								<br>
								<a href="#" class="attach-icon" onclick="uploadTaxFile(this);return false;" id="tax_lnk_attach" role="button" alt="Select file" title="Select file"></a>
								<input type="file" name="taxAttach" id="tax_attach" style="display:none;">		
							</div>
							<progress id="tax_progress_bar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<div><small class='form-control-static'>Use only PDF, JPEG, JPG, PNG files. Maximum file size is 2M.</small></div>
						@endif
						@if ($errors->has('tax')) <p class="bg-danger">{{ $errors->first('tax') }}</p> @endif
						</div>
					</div> 
				</div><!-- tax end -->
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- incorporated -->  
						{{ Form::label('incorporated', 'Incorporation Date', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->incorporated }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container flex-container">
							{{ Form::text('incorporated', Input::old('incorporated'), array('id' => 'incorporated', 'class' => 'input-with-icon form-control')) }}			
							<span class="cal-icon" alt="cal icon"></span>
							@if ($errors->has('incorporated')) <p class="bg-danger">{{ $errors->first('incorporated') }}</p> @endif
						</div>
						@endif
					</div> <!-- incorporated end -->
				</div>
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : ''}}"> <!-- website -->  
						{{ Form::label('website', 'Company Website', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-9'>{{ $company->website }}</p>
						@else
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
							{{ Form::text('website', Input::old('website'), array('id' => 'website', 'class' => 'form-control')) }}			
							@if ($errors->has('website')) <p class="bg-danger">{{ $errors->first('website') }}</p> @endif
						</div>
						@endif
					</div> <!-- website end -->
				</div>
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? ''  : 'required'}}" style="margin-bottom:10px"> <!-- articles of assoc -->  
						{{ Form::label('assoclic', 'Articles Of Assoc.', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
						@if (old('assocfile'))
							<span id="assocfilename" name="assocfilename">{{ old('assocfile') }}</span>
							<input name="assocfile" id="assocfile" type="hidden" value="{{ old('assocfile') }}">
							<input name="assocattachid" id="assocattachid" type="hidden" value="{{ old('assocattachid') }}">								
						@else
							@if (isset($assocattachment))
								@if (isset($mode))
									<a href="/{{ $assocattachment->path }}" download="{{ $assocattachment->path }}">{{ $assocattachment->filename }}</a>
								@else
									<span id="assocfilename" name="assocfilename">{{ $assocattachment->filename }}</span>
								@endif
								<input name="assocfile" id="assocfile" type="hidden" value="{{ $assocattachment->filename }}">
								<input name="assocattachid" id="assocattachid" type="hidden" value="{{ $assocattachment->id }}">									
							@else
								<span id="assocfilename" name="assocfilename">No file attached</span>
								<input name="assocfile" id="assocfile" type="hidden">
								<input name="assocattachid" id="assocattachid" type="hidden">									
							@endif
						@endif
						@if (!isset($mode))
							<progress id="AssocprogressBar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<br>
							<a href="#" class="attach-icon" onclick="Uploadassocfile(this);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>			
							<div><small class='form-control-static'>Use only PDF, JPEG, JPG, PNG files. Maximum file size is 2M.</small></div>
							<input type="file" name="assocattach" id="assocattach" class="assocattach" style="display:none;">
						@endif
						@if ($errors->has('assocfile')) <p class="bg-danger">{{ $errors->first('assocfile') }}</p> @endif
						</div>
					</div><!-- articles of assoc end -->
				</div>
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- operating -->  
						{{ Form::label('operating', 'Industries Operating In', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
						@if (isset($mode))
							@foreach ($company->industries as $industry)
							<p class='form-control-static'>{{ $industry->name }}</p>
							@endforeach
						@else
							{{ Form::select('industries[]', $industries, Input::old('industries'),array('id' => 'industries', 'class' => 'form-control select2m bm-select', 'multiple'))}}									
							@if ($errors->has('industries')) <p class="bg-danger">{{ $errors->first('industries') }}</p> @endif
						@endif
						</div>			
					</div> <!-- operating end --> 
				</div>
				<div class="col-sm-12">
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- employees -->  
						{{ Form::label('employees', 'No. Of Employees', array('class' => 'bm-label col-sm-3 col-xs-12')) }}
						<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->employeenumber->name }}</p>
						@else
							{{ Form::select('employees', $employees, Input::old('employees'),array('id' => 'employees', 'class' => 'form-control bm-select'))}}		
							@if ($errors->has('employees')) <p class="bg-danger">{{ $errors->first('employees') }}</p> @endif
						@endif
						</div>
					</div> <!-- employees end -->
				</div>
				@if (isset($mode) && Gate::any(['cr_ap', 'pt_as']))
					@if (isset($buyerContract))
					<div class="col-sm-12">
						<div class="form-group">
							<label class="bm-label col-lg-6 col-sm-9 col-xs-12">Buyer Contract</label>
							<a href="/{{ $buyerContract->path }}" download="{{ $buyerContract->path }}">{{ $buyerContract->filename }}</a>
						</div>
					</div>
					@endif
					@if (isset($supplierContract))
					<div class="col-sm-12">
					<div class="form-group">
							<label class="bm-label col-lg-6 col-sm-9 col-xs-12">Supplier Contract</label>
							<a href="/{{ $supplierContract->path }}" download="{{ $supplierContract->path }}">{{ $supplierContract->filename }}</a>
						</div>
					</div>
					@endif
				@endif
			</div>					<!-- end col 4 -->
		</div> <!-- end tab -->
		@if (isset($mode))
			@if ($company->companytype_id == 2)
				@php $activetab = ''; @endphp
			@else
				@php $activetab = 'Shareholders'; @endphp
			@endif			
		@endif
		<div id="shareholders" class="row {{ $activetab == 'Shareholders' ? '' : 'hidden' }}">
			@if (isset($mode) || (Gate::allows('co_ch') && isset($company) && isset($mode) && !$company->confirmed))
				<div class="row">	<!-- row 10 -->
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Shareholders</h4>
					</div>
					@if (Gate::allows('co_ch') && isset($company) && isset($mode) && !$company->confirmed)
					<div class="col-sm-8 edit-icon-view d-ib"> <!-- Column 1 -->										
						<a href="{{ url("/company/" . $company->id) . '/Shareholders' }}"><span class="edit-icon--with-border"></span></a>
					</div>
					@endif
				</div>
			@endif
			@if (!isset($mode))
				<div class="row">
					<div class="col-xs-12"> <!-- Column 1 -->
						<div class="radio chk-container">
							<label class="checkbox">
								@if (old('sameowner') != '')
									{{ Form::hidden('sameowner', old('sameowner'), array('id' => 'sameowner')) }}
									@php $sameowner = old('sameowner'); @endphp
									@if (old('sameowner') == "0")
										<input type="checkbox" class="bm-checkbox" name="cbsame" id ="cbsame">
									@else
										<input type="checkbox" class="bm-checkbox" name="cbsame" id ="cbsame" {{old('sameowner')}}>
									@endif
								@else
									@if (isset($company))
										@if ($company->sameowner)
											<input type="checkbox" class="bm-checkbox" name="cbsame" id ="cbsame" checked>
											{{ Form::hidden('sameowner', 1, array('id' => 'sameowner')) }}
											@php $sameowner = 1; @endphp
										@else
											<input type="checkbox" class="bm-checkbox" name="cbsame" id ="cbsame" class="disabled">
											{{ Form::hidden('sameowner', 0, array('id' => 'sameowner')) }}
											@php $sameowner = 0; @endphp
										@endif									
									@else
										<input type="checkbox" class="bm-checkbox" name="cbsame" id ="cbsame" checked>
										{{ Form::hidden('sameowner', 1, array('id' => 'sameowner')) }}
										@php $sameowner = 1; @endphp
									@endif							  
								@endif
								<span class="checkmark"></span>
								<span class="bm-sublabel">Beneficial owners same as shareholders.</span>
							</label>
						</div>
					</div> <!-- Column 1 end -->
				</div>
			@endif
			
			<div class="row">	<!-- row 6 -->
				<div class=" col-sm-12 table-container"> <!-- Column 1 -->					
					<?php $ownercount = 0; ?>
					<table id="ownertable" class="form-table table table-striped table-bordered table-hover">
						<thead>
							<tr>
								@if (isset($mode))
									<th>Name</th>
									<th>Email</th>
									<th width="40%">Mobile</th>
									<th>Share %</th>
									<th>Attachment</th>
								@else
									<th class="no-sort" width="10%">
										<a href="" id="lnkowner" role="button" class="add-icon" title="Add shareholder"></a>	
									</th>
									<th class="col-md-11">&nbsp;</th>
								@endif								
							</tr>		
						</thead>
						<tbody>
							@if (old('ownerid'))
								<!-- -->
								@php
									$i = 0;
								@endphp
								@foreach (old('ownerid') as $item)
									<tr style="{{ (old('ownerdel')[$i]) ? 'display:none' : '' }}">
										<td style="vertical-align:top">
											<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct" title="Delete owner"></a>&nbsp;
											{{ Form::hidden('ownerid[]', old('ownerid')[$i], array('id' => 'owner_id')) }}
											{{ Form::hidden('ownerdel[]', old('ownerdel')[$i], array('id' => 'ownerdel', 'class' => 'form-control')) }}
										</td>
										<td>
											<div>
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('ownername', 'Name', array('class' => 'control-label bm-label tb-label col-sm-3 col-xs-12')) }}
													<div class=" col-lg-6 col-sm-9 col-xs-12">
														{{ Form::text('ownername[]', old('ownername')[$i], array('id' => 'ownername', 'class' => 'form-control')) }}			
														@if ($errors->has('ownername.' . $i)) <p class="bg-danger">{{ $errors->first('ownername.' . $i) }}</p> @endif
													</div>
												</div> <!-- ownername end --> 												
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('owneremail', 'Email', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-lg-6 col-sm-9 col-xs-12">
														{{ Form::text('owneremail[]', old('owneremail')[$i], array('id' => 'owneremail', 'class' => 'form-control')) }}			
														@if ($errors->has('owneremail.' . $i)) <p class="bg-danger">{{ $errors->first('owneremail.' . $i) }}</p> @endif
													</div>
												</div> <!-- owneremail end -->  
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('ownerphone', 'Phone', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-lg-6 col-sm-9 col-xs-12">
														{{ Form::text('ownerphone[]', old('ownerphone')[$i], array('id' => 'ownerphone', 'class' => 'form-control')) }}
														@if ($errors->has('ownerphone.' . $i)) <p class="bg-danger">{{ $errors->first('ownerphone.' . $i) }}</p> @endif
													</div>
												</div> <!-- ownerphone end -->
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('ownershare', 'Share %', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-lg-6 col-sm-9 col-xs-12">
														{{ Form::text('ownershare[]', old('ownershare')[$i], array('id' => 'ownershare', 'class' => 'form-control')) }}			
														@if ($errors->has('ownershare.' . $i)) <p class="bg-danger">{{ $errors->first('ownershare.' . $i) }}</p> @endif
													</div>
												</div> <!-- ownershare end -->  
											</div>
																						
											<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
												{{ Form::label('ownattachment_id', 'Attachments', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
												<div class=" col-lg-6 col-sm-9 col-xs-12" style="position: relative;">
													<?php $j = 0; ?>
													<table class="form-table table uploadtable">
													<tr>
														<td class="td-file-uploader" rowspan="3" style="vertical-align:top">
															<input type="file" name="attach" id="attach" class="attach" style="display:none;">
															<a href="#" class="attach-icon" onclick="Attachment(this,1);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
														</td>
														<td>
															<div class="radio">
																<label class="tb-label">
																	<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="1">
																	<span class="bm-rd-checkmark"></span>
																	<span class="bm-sublabel">ID</span>
																</label>
															</div>
														</td>
														<td class="td-uploaded">
															@if (old('owneridfile')[$i] != '') <!-- ID attachment -->
																@php $j = $j + 1; @endphp
																<a href="#" onclick="DeleteAttachment(this, 1);return false;"><span class="cancel-icon" title="Delete"></span></a>
																<span>{{ old('owneridfile')[$i] }}</span>
																<input name="owneridfile[]" id="owneridfile" type="hidden" value="{{ old('owneridfile')[$i] }}">
																<input name="owneridattachid[]" id="owneridattachid" type="hidden" value="{{ old('owneridattachid')[$i] }}">
															@else
																<a href="#" onclick="DeleteAttachment(this, 1);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																<span>No file selected</span>																		
																<input name="owneridfile[]" id="owneridfile" type="hidden" value="">
																<input name="owneridattachid[]" id="owneridattachid" type="hidden" value="">
															@endif
															<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
														</td>
													</tr>
													<tr>
														<td>
															<div class="radio">
															<label class="tb-label">
																<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="9">
																<span class="bm-rd-checkmark"></span>
																<span class="bm-sublabel">Passport</span>
															  </label>
															</div>
														</td>															
														<td class="td-uploaded">
															@if (old('ownerpptfile')[$i] != '')
																@php $j = $j + 1; @endphp
																<a href="#" onclick="DeleteAttachment(this, 9);return false;"><span class="cancel-icon" title="Delete"></span></a>
																<span>{{ old('ownerpptfile')[$i] }}</span>
																<input name="ownerpptfile[]" id="ownerpptfile" type="hidden" value="{{ old('ownerpptfile')[$i] }}">
																<input name="ownerpptattachid[]" id="ownerpptattachid" type="hidden" value="{{ old('ownerpptattachid')[$i] }}">
															@else
																<a href="#" onclick="DeleteAttachment(this, 9);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																<span>No file selected</span>
																<input name="ownerpptfile[]" id="ownerpptfile" type="hidden" value="">
																<input name="ownerpptattachid[]" id="ownerpptattachid" type="hidden" value="">
															@endif
															<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
														</td>
													</tr>
													<tr>
														<td>
															<div class="radio">
															<label class="tb-label">
																<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="2">
																<span class="bm-rd-checkmark"></span>
																<span class="bm-sublabel">Visa</span>
															  </label>
															</div>
														</td>
														<td class="td-uploaded">
															@if (old('ownervisafile')[$i] != '')
																<a href="#" onclick="DeleteAttachment(this, 2);return false;"><span class="cancel-icon" title="Delete"></span></a>
																<span>{{ old('ownervisafile')[$i] }}</span>
																<input name="ownervisafile[]" id="ownervisafile" type="hidden" value="{{ old('ownervisafile')[$i] }}">
																<input name="ownervisaattachid[]" id="ownervisaattachid" type="hidden" value="">
															@else
																<a href="#" onclick="DeleteAttachment(this, 2);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																<span>No file selected</span>
																<input name="ownervisafile[]" id="ownervisafile" type="hidden" value="">
																<input name="ownervisaattachid[]" id="ownervisaattachid" type="hidden" value="">
															@endif
															<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
														</td>																
													</tr>
													</table>
													<input type="hidden" name="ownerattach[]" id="ownerattach" value="{{ $j }}">
													@if ($errors->has('ownerattach.' . $i)) <p class="bg-danger">{{ $errors->first('ownerattach.' . $i) }}</p> @endif
												</div>
											</div>
										</td>
									</tr>
									<?php $i = $i + 1 ; 
										$ownercount = $i;
									?>
								@endforeach
								<!-- -->
							@else
								<!-- -->
								@if (isset($company))
									<?php $i = 0 ; 
									$j = 0; ?>
									@foreach ($company->companyowners as $owner)
										<tr>							
											@if (isset($mode))								
												<td>{{ $owner->ownername }}</td>												
												<td>{{ $owner->owneremail }}</td>
												<td>{{ $owner->ownerphone }}</td>
												<td>{{ number_format($owner->ownershare, 2, '.', ',') }}</td>
												<td>
													@if ($owner->attachments->count() > 0)
														@foreach ($owner->attachments as $attachment)
															<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a><br>
														@endforeach
													@else
														&nbsp;
													@endif
												</td>
											@else
												<td style="vertical-align:top">
													<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelDirector" title="Delete owner"></a>
													{{ Form::hidden('ownerid[]', $owner->id, array('id' => 'owner_id')) }}
													{{ Form::hidden('ownerdel[]', '', array('id' => 'ownerdel', 'class' => 'form-control')) }}
												</td>
												<td>
													<div>
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('ownername', 'Name', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $owner->ownername }}</p>
															@else
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('ownername[]', $owner->ownername, array('id' => 'ownername', 'class' => 'form-control')) }}			
																	@if ($errors->has('ownername.' . $i)) <p class="bg-danger">{{ $errors->first('ownername.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- ownername end --> 														
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('owneremail', 'Email', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $owner->owneremail }}</p>
															@else
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('owneremail[]', $owner->owneremail, array('id' => 'owneremail', 'class' => 'form-control')) }}			
																	@if ($errors->has('owneremail.' . $i)) <p class="bg-danger">{{ $errors->first('owneremail.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- owneremail end -->  
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('ownerphone', 'Phone', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $owner->ownerphone }}</p>
															@else
																<div class="col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('ownerphone[]', $owner->ownerphone, array('id' => 'ownerphone', 'class' => 'form-control')) }}
																	@if ($errors->has('ownerphone.' . $i)) <p class="bg-danger">{{ $errors->first('ownerphone.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- ownerphone end -->
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('ownershare', 'Share %', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $owner->ownershare }}</p>
															@else
																<div class=" col-lg-6 col-sm-9 col-xs-12">
																	{{ Form::text('ownershare[]', $owner->ownershare, array('id' => 'ownershare', 'class' => 'form-control')) }}			
																	@if ($errors->has('ownershare.' . $i)) <p class="bg-danger">{{ $errors->first('ownershare.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- ownershare end -->  
													</div>
													
													
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
														{{ Form::label('dirattachment_id', 'Attachments', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
														<div class="col-lg-6 col-sm-9 col-xs-12" style="position: relative;">
															<?php $i = 0 ; 
															$j = 0; ?>
															<table class="attach-table form-table table uploadtable">
															<tr>
																<td rowspan="3" style="vertical-align:top">
																	<input type="file" name="attach" id="attach" class="attach" style="display:none;">
																	<a href="#" class="attach-icon" onclick="Attachment(this,1);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
																</td>
																<td>
																	<div class="radio">
																		<label class="tb-label">
																			<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="1">
																			<span class="bm-rd-checkmark"></span>
																			<span class="bm-sublabel">ID</span>
																		</label>
																	</div>
																</td>
																<td class="td-uploaded">
																	@if ($owner->attachments->where('attachmenttype_id', '1')->count() > 0) <!-- ID attachment -->
																		@php $j = $j + $owner->attachments->where('attachmenttype_id', '1')->count(); @endphp
																		<a href="#" onclick="DeleteAttachment(this, 1);return false;"><span class="cancel-icon" title="Delete"></span></a>
																		<span>{{ $owner->attachments->where('attachmenttype_id', '1')->first()->filename }}</span>
																		<input name="owneridfile[]" id="owneridfile" type="hidden" value="{{ $owner->attachments->where('attachmenttype_id', '1')->first()->filename }}">
																		<input name="owneridattachid[]" id="owneridattachid" type="hidden" value="">
																	@else
																		<a href="#" onclick="DeleteAttachment(this, 1);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																		<span>No file selected</span>																		
																		<input name="owneridfile[]" id="owneridfile" type="hidden" value="">
																		<input name="owneridattachid[]" id="owneridattachid" type="hidden" value="">
																	@endif
																	<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
																</td>
															</tr>
															<tr>
																<td>
																	<div class="radio">
																	<label class="tb-label">
																		<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="9">
																		<span class="bm-rd-checkmark"></span>
																		<span class="bm-sublabel">Passport</span>
																	  </label>
																	</div>
																</td>															
																<td class="td-uploaded">
																	@if ($owner->attachments->where('attachmenttype_id', '9')->count() > 0) <!-- Passport attachment -->
																		@php $j = $j + $owner->attachments->where('attachmenttype_id', '9')->count(); @endphp
																		<a href="#" onclick="DeleteAttachment(this, 9);return false;"><span class="cancel-icon" title="Delete"></span></a>
																		<span>{{ $owner->attachments->where('attachmenttype_id', '9')->first()->filename }}</span>
																		<input name="ownerpptfile[]" id="ownerpptfile" type="hidden" value="{{ $owner->attachments->where('attachmenttype_id', '9')->first()->filename }}">
																		<input name="ownerpptattachid[]" id="ownerpptattachid" type="hidden" value="">
																	@else
																		<a href="#" onclick="DeleteAttachment(this, 9);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																		<span>No file selected</span>
																		<input name="ownerpptfile[]" id="ownerpptfile" type="hidden" value="">
																		<input name="ownerpptattachid[]" id="ownerpptattachid" type="hidden" value="">
																	@endif
																	<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="radio">
																	<label class"tb-label">
																		<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="2">
																		<span class="bm-rd-checkmark"></span>
																		<span class="bm-sublabel">Visa</span>
																	  </label>
																	</div>
																</td>
																<td class="td-uploaded">
																	@if ($owner->attachments->where('attachmenttype_id', '2')->count() > 0) <!-- Visa attachment -->
																		<a href="#" onclick="DeleteAttachment(this, 2);return false;"><span class="cancel-icon" title="Delete"></span></a>
																		<span>{{ $owner->attachments->where('attachmenttype_id', '2')->first()->filename }}</span>
																		<input name="ownervisafile[]" id="ownervisafile" type="hidden" value="{{ $owner->attachments->where('attachmenttype_id', '2')->first()->filename }}">
																		<input name="ownervisaattachid[]" id="ownervisaattachid" type="hidden" value="">
																	@else
																		<a href="#" onclick="DeleteAttachment(this, 2);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																		<span>No file selected</span>
																		<input name="ownervisafile[]" id="ownervisafile" type="hidden" value="">
																		<input name="ownervisaattachid[]" id="ownervisaattachid" type="hidden" value="">
																	@endif
																	<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
																</td>																
															</tr>
															</table>
															<input type="hidden" name="ownerattach[]" id="ownerattach" value="{{ $j }}">
															@if ($errors->has('ownerattach.' . $i)) <p class="bg-danger">{{ $errors->first('ownerattach.' . $i) }}</p> @endif
														</div>
													</div>
												</td>
											@endif
										</tr>
										<?php $i = $i + 1 ; 
											$ownercount = $ownercount + 1;
										?>
									@endforeach
								@endif
								<!-- -->
							@endif
						</tbody>
					</table>
					<input type="hidden" name="ownercount" id="ownercount" value="{{ old('ownercount', $ownercount) }}">
					@if ($errors->has('ownercount')) <p class="bg-danger">{{ $errors->first('ownercount') }}</p> @endif
					@if ($errors->has('ownershare')) <p class="bg-danger">{{ $errors->first('ownershare') }}</p> @endif
					@if ($errors->has('shares')) <p class="bg-danger">{{ $errors->first('shares') }}</p> @endif
				</div>					<!-- end col 1 -->
			</div>				<!-- end row 6 -->
			@if (isset($mode) && $company->sameowner)
				@php $showbeneficialowners = 0; @endphp
			@else
				@php $showbeneficialowners = 1; @endphp
			@endif
			@if ($showbeneficialowners)
				<div class="row">	<!-- row 7 -->
					<div class="col-md-3 col-sm-4 d-ib"> <!-- Column 1 -->
						<h4>Beneficial Owners</h4>
					</div>
					<div class=" col-sm-7 edit-icon-view d-ib"> <!-- Column 1 -->
						@if (Gate::allows('co_ch') && isset($company))
							@if (isset($mode) && !$company->confirmed)
								<a href="{{ url("/company/" . $company->id) . '/Shareholders' }}"><span class="edit-icon--with-border"></span></a>
							@endif
						@endif
					</div>			
				</div>
				<div class="row">	<!-- row 7 -->
					<div class=" col-sm-12 table-container"> <!-- Column 1 -->					
						<?php $beneficialcount = 0; ?>
						<table class="form-table table table-striped table-bordered table-hover" id="beneficialtable">
							<thead>
								<tr>
									@if (isset($mode))
										<th class="col-md-2">Name</th>
										<th class="col-md-3">Email</th>
										<th class="col-md-2">Mobile</th>
										<th class="col-md-1">Share %</th>
										<th class="col-md-2">Attachment</th>
									@else
										<th class="no-sort" width="10%">									
											<button id="lnkbeneficial" role="button" class="add-icon {{ $sameowner == 1 ? 'disabled' : '' }}" {{ $sameowner == 1 ? 'disabled=disabled' : '' }} title="Add shareholder"></button>	
										</th>
										<th class="col-md-11">&nbsp;</th>
									@endif								
								</tr>		
							</thead>
							<tbody>
								@if (old('beneficialid'))
									<!-- -->
									@php
										$i = 0;
									@endphp
									@foreach (old('beneficialid') as $item)
										<tr style="{{ (old('beneficialdel')[$i]) ? 'display:none' : '' }}">
											<td>
												<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct" title="Delete beneficial"></a>&nbsp;
												{{ Form::hidden('beneficialid[]', old('beneficialid')[$i], array('id' => 'beneficial_id')) }}
												{{ Form::hidden('beneficialdel[]', old('beneficialdel')[$i], array('id' => 'beneficialdel', 'class' => 'form-control')) }}
											</td>
											<td>
												<div>
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- beneficialname -->  
														{{ Form::label('beneficialname', 'Name', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-sm-6">
															{{ Form::text('beneficialname[]', old('beneficialname')[$i], array('id' => 'beneficialname', 'class' => 'form-control')) }}			
															@if ($errors->has('beneficialname.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialname.' . $i) }}</p> @endif
														</div>
													</div> <!-- beneficialname end --> 												
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- beneficialemail -->  
														{{ Form::label('beneficialemail', 'Email', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-sm-6">
															{{ Form::text('beneficialemail[]', old('beneficialemail')[$i], array('id' => 'beneficialemail', 'class' => 'form-control')) }}			
															@if ($errors->has('beneficialemail.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialemail.' . $i) }}</p> @endif
														</div>
													</div> <!-- beneficialemail end -->  
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
														{{ Form::label('beneficialphone', 'Phone', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-sm-6">
															{{ Form::text('beneficialphone[]', old('beneficialphone')[$i], array('id' => 'beneficialphone', 'class' => 'form-control')) }}			
															@if ($errors->has('beneficialphone.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialphone.' . $i) }}</p> @endif
														</div>
													</div> <!-- beneficialphone end -->
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
														{{ Form::label('beneficialshare', 'Share %', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-sm-6">
															{{ Form::text('beneficialshare[]', old('beneficialshare')[$i], array('id' => 'beneficialshare', 'class' => 'form-control')) }}			
															@if ($errors->has('beneficialshare.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialshare.' . $i) }}</p> @endif
														</div>
													</div> <!-- beneficialshare end -->  
												</div>
																							
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
													{{ Form::label('ownattachment_id', 'Attachments', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-sm-6" style="position: relative;">
														<?php $j = 0; ?>
														<table class="form-table table uploadtable">
														<tr>
															<td rowspan="3" style="vertical-align:top">
																<input type="file" name="attach" id="attach" class="attach" style="display:none;">
																<a href="#" class="attach-icon" onclick="Attachment(this,11);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
															</td>
															<td>
																<div class="radio">
																	<label class="tb-label">
																		<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="11">
																		<span class="bm-rd-checkmark"></span>
																		<span class="bm-sublabel">ID</span>
																	</label>
																</div>
															</td>
															<td class="td-uploaded">
																@if (old('beneficialidfile')[$i] != '') <!-- ID attachment -->
																	@php $j = $j + 1; @endphp
																	<a href="#" onclick="DeleteAttachment(this, 11);return false;"><span class="cancel-icon" title="Delete"></span></a>
																	<span>{{ old('beneficialidfile')[$i] }}</span>
																	<input name="beneficialidfile[]" id="beneficialidfile" type="hidden" value="{{ old('beneficialidfile')[$i] }}">
																	<input name="beneficialidattachid[]" id="beneficialidattachid" type="hidden" value="{{ old('beneficialidattachid')[$i] }}">
																@else
																	<a href="#" onclick="DeleteAttachment(this, 11);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																	<span>No file selected</span>																		
																	<input name="beneficialidfile[]" id="beneficialidfile" type="hidden" value="">
																	<input name="beneficialidattachid[]" id="beneficialidattachid" type="hidden" value="">
																@endif
																<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
															</td>
														</tr>
														<tr>
															<td>
																<div class="radio">
																<label class="tb-label">
																	<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="13">
																	<span class="bm-rd-checkmark"></span>
																	<span class="bm-sublabel">Passport</span>
																  </label>
																</div>
															</td>															
															<td class="td-uploaded">
																@if (old('beneficialpptfile')[$i] != '')
																	@php $j = $j + 1; @endphp
																	<a href="#" onclick="DeleteAttachment(this, 13);return false;"><span class="cancel-icon" title="Delete"></span></a>
																	<span>{{ old('beneficialpptfile')[$i] }}</span>
																	<input name="beneficialpptfile[]" id="beneficialpptfile" type="hidden" value="{{ old('beneficialpptfile')[$i] }}">
																	<input name="beneficialpptattachid[]" id="beneficialpptattachid" type="hidden" value="{{ old('beneficialpptattachid')[$i] }}">
																@else
																	<a href="#" onclick="DeleteAttachment(this, 13);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																	<span>No file selected</span>
																	<input name="beneficialpptfile[]" id="beneficialpptfile" type="hidden" value="">
																	<input name="beneficialpptattachid[]" id="beneficialpptattachid" type="hidden" value="">
																@endif
																<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
															</td>
														</tr>
														<tr>
															<td>
																<div class="radio">
																<label class="tb-label">
																	<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="12">
																	<span class="bm-rd-checkmark"></span>
																	<span class="bm-sublabel">Visa</span>
																  </label>
																</div>
															</td>
															<td class="td-uploaded">
																@if (old('beneficialvisafile')[$i] != '')
																	<a href="#" onclick="DeleteAttachment(this, 12);return false;"><span class="cancel-icon" title="Delete"></span></a>
																	<span>{{ old('beneficialvisafile')[$i] }}</span>
																	<input name="beneficialvisafile[]" id="beneficialvisafile" type="hidden" value="{{ old('beneficialvisafile')[$i] }}">
																	<input name="beneficialvisaattachid[]" id="beneficialvisaattachid" type="hidden" value="">
																@else
																	<a href="#" onclick="DeleteAttachment(this, 12);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																	<span>No file selected</span>
																	<input name="beneficialvisafile[]" id="beneficialvisafile" type="hidden" value="">
																	<input name="beneficialvisaattachid[]" id="beneficialvisaattachid" type="hidden" value="">
																@endif
																<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
															</td>																
														</tr>
														</table>
														<input type="hidden" name="beneficialattach[]" id="beneficialattach" value="{{ $j }}">
														@if ($errors->has('beneficialattach.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialattach.' . $i) }}</p> @endif
													</div>
												</div>
											</td>
										</tr>
										<?php $i = $i + 1 ; 
											$beneficialcount = $i;
										?>
									@endforeach
									<!-- -->
								@else
									<!-- -->
									@if (isset($company))
										<?php $i = 0 ; 
										$j = 0; ?>
										@foreach ($company->companybeneficials as $beneficial)
											<tr>							
												@if (isset($mode))								
													<td>{{ $beneficial->beneficialname }}</td>												
													<td>{{ $beneficial->beneficialemail }}</td>
													<td>{{ $beneficial->beneficialphone }}</td>
													<td>{{ number_format($beneficial->beneficialshare, 2, '.', ',') }}</td>
													<td>
														@if ($beneficial->attachments->count() > 0)
															@foreach ($beneficial->attachments as $attachment)
																<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a><br>
															@endforeach
														@else
															&nbsp;
														@endif
													</td>
												@else
													<td style="vertical-align:top">
														<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelDirector" title="Delete beneficial"></a>
														{{ Form::hidden('beneficialid[]', $beneficial->id, array('id' => 'beneficial_id')) }}
														{{ Form::hidden('beneficialdel[]', '', array('id' => 'beneficialdel', 'class' => 'form-control')) }}
													</td>
													<td>
														<div>
															<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- beneficialname -->  
																{{ Form::label('beneficialname', 'Name', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $beneficial->beneficialname }}</p>
																@else
																	<div class=" col-sm-6">
																		{{ Form::text('beneficialname[]', $beneficial->beneficialname, array('id' => 'beneficialname', 'class' => 'form-control')) }}			
																		@if ($errors->has('beneficialname.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialname.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- beneficialname end --> 														
															<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- beneficialemail -->  
																{{ Form::label('beneficialemail', 'Email', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $beneficial->beneficialemail }}</p>
																@else
																	<div class=" col-sm-6">
																		{{ Form::text('beneficialemail[]', $beneficial->beneficialemail, array('id' => 'beneficialemail', 'class' => 'form-control')) }}			
																		@if ($errors->has('beneficialemail.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialemail.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- beneficialemail end -->  
															<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
																{{ Form::label('beneficialphone', 'Phone', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $beneficial->beneficialphone }}</p>
																@else
																	<div class=" col-sm-6">
																		{{ Form::text('beneficialphone[]', $beneficial->beneficialphone, array('id' => 'beneficialphone', 'class' => 'form-control')) }}			
																		@if ($errors->has('beneficialphone.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialphone.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- beneficialphone end -->
															<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
																{{ Form::label('beneficialshare', 'Share %', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $beneficial->beneficialshare }}</p>
																@else
																	<div class=" col-sm-6">
																		{{ Form::text('beneficialshare[]', $beneficial->beneficialshare, array('id' => 'beneficialshare', 'class' => 'form-control')) }}			
																		@if ($errors->has('beneficialshare.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialshare.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- beneficialshare end -->  
														</div>
														
														
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
															{{ Form::label('dirattachment_id', 'Attachments', array('class' => 'tb-label control-label bm-label col-sm-3 col-xs-12')) }}
															<div class=" col-sm-6" style="position: relative;">
																<?php $i = 0 ; 
																$j = 0; ?>
																<table class="attach-table form-table table uploadtable">
																<tr>
																	<td rowspan="3" style="vertical-align:top">
																		<input type="file" name="attach" id="attach" class="attach" style="display:none;">
																		<a href="#" class="attach-icon" onclick="Attachment(this,11);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
																	</td>
																	<td>
																		<div class="radio">
																			<label class"tb-label">
																				<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="11">
																				<span class="bm-rd-checkmark"></span>
																				<span class="bm-sublabel">ID</span>
																			</label>
																		</div>
																	</td>
																	<td class="td-uploaded">
																		@if ($beneficial->attachments->where('attachmenttype_id', '11')->count() > 0) <!-- ID attachment -->
																			@php $j = $j + $beneficial->attachments->where('attachmenttype_id', '11')->count(); @endphp
																			<a href="#" onclick="DeleteAttachment(this, 11);return false;"><span class="cancel-icon" title="Delete"></span></a>
																			<span>{{ $beneficial->attachments->where('attachmenttype_id', '11')->first()->filename }}</span>
																			<input name="beneficialidfile[]" id="beneficialidfile" type="hidden" value="{{ $beneficial->attachments->where('attachmenttype_id', '11')->first()->filename }}">
																			<input name="beneficialidattachid[]" id="beneficialidattachid" type="hidden" value="">
																		@else
																			<a href="#" onclick="DeleteAttachment(this, 11);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																			<span>No file selected</span>																		
																			<input name="beneficialidfile[]" id="beneficialidfile" type="hidden" value="">
																			<input name="beneficialidattachid[]" id="beneficialidattachid" type="hidden" value="">
																		@endif
																		<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
																	</td>
																</tr>
																<tr>
																	<td>
																		<div class="radio">
																		<label class="tb-label">
																			<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="13">
																			<span class="bm-rd-checkmark"></span>
																			<span class="bm-sublabel">Passport</span>
																		  </label>
																		</div>
																	</td>															
																	<td class="td-uploaded">
																		@if ($beneficial->attachments->where('attachmenttype_id', '13')->count() > 0) <!-- Passport attachment -->
																			@php $j = $j + $beneficial->attachments->where('attachmenttype_id', '13')->count(); @endphp
																			<a href="#" onclick="DeleteAttachment(this, 13);return false;"><span class="cancel-icon" title="Delete"></span></a>
																			<span>{{ $beneficial->attachments->where('attachmenttype_id', '13')->first()->filename }}</span>
																			<input name="beneficialpptfile[]" id="beneficialpptfile" type="hidden" value="{{ $beneficial->attachments->where('attachmenttype_id', '13')->first()->filename }}">
																			<input name="beneficialpptattachid[]" id="beneficialpptattachid" type="hidden" value="">
																		@else
																			<a href="#" onclick="DeleteAttachment(this, 13);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																			<span>No file selected</span>
																			<input name="beneficialpptfile[]" id="beneficialpptfile" type="hidden" value="">
																			<input name="beneficialpptattachid[]" id="beneficialpptattachid" type="hidden" value="">
																		@endif
																		<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
																	</td>
																</tr>
																<tr>
																	<td>
																		<div class="radio">
																		<label class="tb-label">
																			<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="12">
																			<span class="bm-rd-checkmark"></span>
																			<span class="bm-sublabel">Visa</span>
																		  </label>
																		</div>
																	</td>
																	<td class="td-uploaded">
																		@if ($beneficial->attachments->where('attachmenttype_id', '12')->count() > 0) <!-- Visa attachment -->
																			<a href="#" onclick="DeleteAttachment(this, 12);return false;"><span class="cancel-icon" title="Delete"></span></a>
																			<span>{{ $beneficial->attachments->where('attachmenttype_id', '12')->first()->filename }}</span>
																			<input name="beneficialvisafile[]" id="beneficialvisafile" type="hidden" value="{{ $beneficial->attachments->where('attachmenttype_id', '12')->first()->filename }}">
																			<input name="beneficialvisaattachid[]" id="beneficialvisaattachid" type="hidden" value="">
																		@else
																			<a href="#" onclick="DeleteAttachment(this, 12);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																			<span>No file selected</span>
																			<input name="beneficialvisafile[]" id="beneficialvisafile" type="hidden" value="">
																			<input name="beneficialvisaattachid[]" id="beneficialvisaattachid" type="hidden" value="">
																		@endif
																		<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
																	</td>																
																</tr>
																</table>
																<input type="hidden" name="beneficialattach[]" id="beneficialattach" value="{{ $j }}">
																@if ($errors->has('beneficialattach.' . $i)) <p class="bg-danger">{{ $errors->first('beneficialattach.' . $i) }}</p> @endif
															</div>
														</div>
													</td>
												@endif
											</tr>
											<?php $i = $i + 1 ; 
												$beneficialcount = $beneficialcount + 1;
											?>
										@endforeach
									@endif
									<!-- -->
								@endif
							</tbody>
						</table>
						<input type="hidden" name="beneficialcount" id="beneficialcount" value="{{ old('beneficialcount', $beneficialcount) }}">
						@if ($errors->has('beneficialcount')) <p class="bg-danger">{{ $errors->first('beneficialcount') }}</p> @endif
						@if ($errors->has('beneficialshare')) <p class="bg-danger">{{ $errors->first('beneficialshare') }}</p> @endif
						@if ($errors->has('shares')) <p class="bg-danger">{{ $errors->first('shares') }}</p> @endif
					</div>					<!-- end col 1 -->
				</div>				<!-- end row 7 -->
			@endif
		</div> <!-- end owner tab -->
		@if (isset($mode))
			@if ($company->companytype_id == 2)
				@php $activetab = ''; @endphp
			@else
				@php $activetab = 'Directors'; @endphp
			@endif			
		@endif
		<div id="directors" class="row {{$activetab == 'Directors' ? '' : 'hidden' }}">
			<div class="row">	<!-- row 7 -->
				<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
					@if (isset($mode))
						<h4>Directors</h4>
					@endif
				</div>
				<div class="edit-icon-view col-sm-3 d-ib"> <!-- Column 1 -->
					@if (Gate::allows('co_ch') && isset($company))
						@if (isset($mode) && !$company->confirmed)
							<a href="{{ url("/company/" . $company->id) . '/Directors' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					@endif
				</div>			
			</div>
			<div class="row">	<!-- row 8 --> 
				<div class=" col-sm-12 table-container"> <!-- Column 1 -->					
					<?php $directorcount = 0; ?>
					<table id="directortable" class="form-table table table-striped table-bordered table-hover">
						<thead>
							<tr>
								@if (isset($mode))
									<th class="col-md-2">Name</th>
									<th class="col-md-2">Job Title</th>
									<th class="col-md-3">Email</th>
									<th class="col-md-2">Mobile</th>
									<th class="col-md-2">Attachment</th>
								@else
									<th class="no-sort"  class="col-md-1">
										<a href="" id="lnkdirector" role="button" class="add-icon" title="Add director"></a>
									</th>
									<th class="col-md-11">&nbsp;</th>
								@endif								
							</tr>		
						</thead>
						<tbody>
							@if (old('directorid'))
								@php
									$i = 0;
								@endphp
								@foreach (old('directorid') as $item)
									<tr style="{{ (old('directordel')[$i]) ? 'display:none' : '' }}">
										<td style="vertical-align: top">
											<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct" title="Delete director"></a>&nbsp;
											{{ Form::hidden('directorid[]', old('directorid')[$i], array('id' => 'director_id')) }}
											{{ Form::hidden('directordel[]', old('directordel')[$i], array('id' => 'directordel', 'class' => 'form-control')) }}
										</td>
										<td>
											<div>
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('directorname', 'Name', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-sm-6">
														{{ Form::text('directorname[]', old('directorname')[$i], array('id' => 'directorname', 'class' => 'form-control')) }}			
														@if ($errors->has('directorname.' . $i)) <p class="bg-danger">{{ $errors->first('directorname.' . $i) }}</p> @endif
													</div>
												</div> <!-- directorname end --> 
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('directortitle', 'Job Title', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-sm-6">
														{{ Form::text('directortitle[]', old('directortitle')[$i], array('id' => 'directortitle', 'class' => 'form-control')) }}			
														@if ($errors->has('directortitle.' . $i)) <p class="bg-danger">{{ $errors->first('directortitle.' . $i) }}</p> @endif
													</div>
												</div> <!-- directortitle end -->  
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('directoremail', 'Email', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-sm-6">
														{{ Form::text('directoremail[]', old('directoremail')[$i], array('id' => 'directoremail', 'class' => 'form-control')) }}			
														@if ($errors->has('directoremail.' . $i)) <p class="bg-danger">{{ $errors->first('directoremail.' . $i) }}</p> @endif
													</div>
												</div> <!-- directoremail end -->  
												<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
													{{ Form::label('directorphone', 'Phone', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
													<div class=" col-sm-6">
														{{ Form::text('directorphone[]', old('directorphone')[$i], array('id' => 'directorphone', 'class' => 'form-control')) }}
														@if ($errors->has('directorphone.' . $i)) <p class="bg-danger">{{ $errors->first('directorphone.' . $i) }}</p> @endif
													</div>
												</div> <!-- directorphone end -->  
											</div>
																						
											<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
												{{ Form::label('dirattachment_id', 'Attachments', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
												<div class=" col-sm-6" style="position: relative;">
													<?php $j = 0; ?>
													<table class="attach-table form-table table uploadtable">
													<tr>
														<td rowspan="3" style="vertical-align:top">
															<input type="file" name="attach" id="attach" class="attach" style="display:none;">
															<a href="#" class="attach-icon" onclick="Attachment(this,3);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
														</td>
														<td>
															<div class="radio">
																<label>
																	<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="3">
																	<span class="bm-rd-checkmark"></span>
																	<span class="bm-sublabel">ID</span>
																</label>
															</div>
														</td>
														<td class="td-uploaded">
															@if (old('directoridfile')[$i] != '') <!-- ID attachment -->
																@php $j = $j + 1; @endphp
																<a href="#" onclick="DeleteAttachment(this, 3);return false;"><span class="cancel-icon" title="Delete"></span></a>
																<span>{{ old('directoridfile')[$i] }}</span>
																<input name="directoridfile[]" id="directoridfile" type="hidden" value="{{ old('directoridfile')[$i] }}">
																<input name="directoridattachid[]" id="directoridattachid" type="hidden" value="{{ old('directoridattachid')[$i] }}">
															@else
																<a href="#" onclick="DeleteAttachment(this, 3);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																<span>No file selected</span>																		
																<input name="directoridfile[]" id="directoridfile" type="hidden" value="">
																<input name="directoridattachid[]" id="directoridattachid" type="hidden" value="">
															@endif
															<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
														</td>
													</tr>
													<tr>
														<td>
															<div class="radio">
															<label>
																<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="10">
																<span class="bm-rd-checkmark"></span>
																<span class="bm-sublabel">Passport</span>
															  </label>
															</div>
														</td>															
														<td class="td-uploaded">
															@if (old('directorpptfile')[$i] != '')
																@php $j = $j + 1; @endphp
																<a href="#" onclick="DeleteAttachment(this, 10);return false;"><span class="cancel-icon" title="Delete"></span></a>
																<span>{{ old('directorpptfile')[$i] }}</span>
																<input name="directorpptfile[]" id="directorpptfile" type="hidden" value="{{ old('directorpptfile')[$i] }}">
																<input name="directorpptattachid[]" id="directorpptattachid" type="hidden" value="{{ old('directorpptattachid')[$i] }}">
															@else
																<a href="#" onclick="DeleteAttachment(this, 10);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																<span>No file selected</span>
																<input name="directorpptfile[]" id="directorpptfile" type="hidden" value="">
																<input name="directorpptattachid[]" id="directorpptattachid" type="hidden" value="">
															@endif
															<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
														</td>
													</tr>
													<tr>
														<td>
															<div class="radio">
															<label>
																<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="4">
																<span class="bm-rd-checkmark"></span>
																<span class="bm-sublabel">Visa</span>
															  </label>
															</div>
														</td>
														<td class="td-uploaded">
															@if (old('directorvisafile')[$i] != '')
																<a href="#" onclick="DeleteAttachment(this, 4);return false;"><span class="cancel-icon" title="Delete"></span></a>
																<span>{{ old('directorvisafile')[$i] }}</span>
																<input name="directorvisafile[]" id="directorvisafile" type="hidden" value="{{ old('directorvisafile')[$i] }}">
																<input name="directorvisaattachid[]" id="directorvisaattachid" type="hidden" value="">
															@else
																<a href="#" onclick="DeleteAttachment(this, 4);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																<span>No file selected</span>
																<input name="directorvisafile[]" id="directorvisafile" type="hidden" value="">
																<input name="directorvisaattachid[]" id="directorvisaattachid" type="hidden" value="">
															@endif
															<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
														</td>																
													</tr>
													</table>
													<input type="hidden" name="directorattach[]" id="directorattach" value="{{ $j }}">
													@if ($errors->has('directorattach.' . $i)) <p class="bg-danger">{{ $errors->first('directorattach.' . $i) }}</p> @endif
												</div>
											</div>										
										</td>
									</tr>
									<?php $i = $i + 1 ; 
										$directorcount = $i;
									?>
								@endforeach
							@else
								@if (isset($company))
									<?php $i = 0 ; 
									$j = 0; ?>
									@foreach ($company->companydirectors as $director)
										<tr>							
											@if (isset($mode))								
												<td>{{ $director->directorname }}</td>
												<td>{{ $director->directortitle }}</td>
												<td>{{ $director->directoremail }}</td>
												<td>{{ $director->directorphone }}</td>
												<td>
													@if ($director->attachments->count() > 0)
														@foreach ($director->attachments as $attachment)
															<a href="/{{ $attachment->path }}" download="{{ $attachment->path }}">{{ $attachment->filename }}</a><br>
														@endforeach
													@else
														&nbsp;
													@endif
												</td>
											@else
												<td style="vertical-align:top">
													<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelDirector" title="Delete director"></a>
													{{ Form::hidden('directorid[]', $director->id, array('id' => 'director_id')) }}
													{{ Form::hidden('directordel[]', '', array('id' => 'directordel', 'class' => 'form-control')) }}
												</td>
												<td>
													<div>
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('directorname', 'Name', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $director->directorname }}</p>
															@else
																<div class=" col-sm-6">
																	{{ Form::text('directorname[]', $director->directorname, array('id' => 'directorname', 'class' => 'form-control')) }}			
																	@if ($errors->has('directorname.' . $i)) <p class="bg-danger">{{ $errors->first('directorname.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- directorname end --> 
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('directortitle', 'Job Title', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $director->directortitle }}</p>
															@else
																<div class=" col-sm-6">
																	{{ Form::text('directortitle[]', $director->directortitle, array('id' => 'directortitle', 'class' => 'form-control')) }}			
																	@if ($errors->has('directortitle.' . $i)) <p class="bg-danger">{{ $errors->first('directortitle.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- directortitle end -->  
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('directoremail', 'Email', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $director->directoremail }}</p>
															@else
																<div class=" col-sm-6">
																	{{ Form::text('directoremail[]', $director->directoremail, array('id' => 'directoremail', 'class' => 'form-control')) }}			
																	@if ($errors->has('directoremail.' . $i)) <p class="bg-danger">{{ $errors->first('directoremail.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- directoremail end -->  
														<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- district -->  
															{{ Form::label('directorphone', 'Phone', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
															@if (isset($mode))	
																<p class='form-control-static'>{{ $director->directorphone }}</p>
															@else
																<div class=" col-sm-6">
																	{{ Form::text('directorphone[]', $director->directorphone, array('id' => 'directorphone', 'class' => 'form-control')) }}		
																	@if ($errors->has('directorphone.' . $i)) <p class="bg-danger">{{ $errors->first('directorphone.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- directorphone end -->  
													</div>
													
													
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- license -->  
														{{ Form::label('dirattachment_id', 'Attachments', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-sm-6" style="position: relative;">
															<?php $i = 0 ; 
															$j = 0; ?>
															<table class="attach-table form-table table uploadtable">
															<tr>
																<td rowspan="3" style="vertical-align:top">
																	<input type="file" name="attach" id="attach" class="attach" style="display:none;">
																	<a href="#" class="attach-icon" onclick="Attachment(this,3);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
																</td>
																<td>
																	<div class="radio">
																		<label>
																			<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="3">
																			<span class="bm-rd-checkmark"></span>
																			<span class="bm-sublabel">ID</span>
																		</label>
																	</div>
																</td>
																<td class="td-uploaded">
																	@if ($director->attachments->where('attachmenttype_id', '3')->count() > 0) <!-- ID attachment -->
																		@php $j = $j + $director->attachments->where('attachmenttype_id', '3')->count(); @endphp
																		<a href="#" onclick="DeleteAttachment(this, 3);return false;"><span class="cancel-icon" title="Delete"></span></a>
																		<span>{{ $director->attachments->where('attachmenttype_id', '3')->first()->filename }}</span>
																		<input name="directoridfile[]" id="directoridfile" type="hidden" value="{{ $director->attachments->where('attachmenttype_id', '3')->first()->filename }}">
																		<input name="directoridattachid[]" id="directoridattachid" type="hidden" value="">
																	@else
																		<a href="#" onclick="DeleteAttachment(this, 3);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																		<span>No file selected</span>																		
																		<input name="directoridfile[]" id="directoridfile" type="hidden" value="">
																		<input name="directoridattachid[]" id="directoridattachid" type="hidden" value="">
																	@endif
																	<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
																</td>
															</tr>
															<tr>
																<td>
																	<div class="radio">
																	<label>
																		<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="10">
																		<span class="bm-rd-checkmark"></span>
																		<span class="bm-sublabel">Passport</span>
																	  </label>
																	</div>
																</td>															
																<td class="td-uploaded">
																	@if ($director->attachments->where('attachmenttype_id', '10')->count() > 0) <!-- Passport attachment -->
																		@php $j = $j + $director->attachments->where('attachmenttype_id', '10')->count(); @endphp
																		<a href="#" onclick="DeleteAttachment(this, 10);return false;"><span class="cancel-icon" title="Delete"></span></a>
																		<span>{{ $director->attachments->where('attachmenttype_id', '10')->first()->filename }}</span>
																		<input name="directorpptfile[]" id="directorpptfile" type="hidden" value="{{ $director->attachments->where('attachmenttype_id', '10')->first()->filename }}">
																		<input name="directorpptattachid[]" id="directorpptattachid" type="hidden" value="">
																	@else
																		<a href="#" onclick="DeleteAttachment(this, 10);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																		<span>No file selected</span>
																		<input name="directorpptfile[]" id="directorpptfile" type="hidden" value="">
																		<input name="directorpptattachid[]" id="directorpptattachid" type="hidden" value="">
																	@endif
																	<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="radio">
																	<label>
																		<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="4">
																		<span class="bm-rd-checkmark"></span>
																		<span class="bm-sublabel">Visa</span>
																	  </label>
																	</div>
																</td>
																<td class="td-uploaded">
																	@if ($director->attachments->where('attachmenttype_id', '4')->count() > 0) <!-- Visa attachment -->
																		<a href="#" onclick="DeleteAttachment(this, 4);return false;"><span class="cancel-icon" title="Delete"></span></a>
																		<span>{{ $director->attachments->where('attachmenttype_id', '4')->first()->filename }}</span>
																		<input name="directorvisafile[]" id="directorvisafile" type="hidden" value="{{ $director->attachments->where('attachmenttype_id', '4')->first()->filename }}">
																		<input name="directorvisaattachid[]" id="directorvisaattachid" type="hidden" value="">
																	@else
																		<a href="#" onclick="DeleteAttachment(this, 4);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
																		<span>No file selected</span>
																		<input name="directorvisafile[]" id="directorvisafile" type="hidden" value="">
																		<input name="directorvisaattachid[]" id="directorvisaattachid" type="hidden" value="">
																	@endif
																	<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
																</td>																
															</tr>
															</table>
															<input type="hidden" name="directorattach[]" id="directorattach" value="{{ $j }}">
															@if ($errors->has('directorattach.' . $i)) <p class="bg-danger">{{ $errors->first('directorattach.' . $i) }}</p> @endif
														</div>
													</div>
												</td>
											@endif
										</tr>
										<?php $i = $i + 1 ; 
											$directorcount = $directorcount + 1;
										?>
									@endforeach
								@endif
							@endif
						</tbody>
					</table>
					<input type="hidden" name="directorcount" id="directorcount" value="{{ old('directorcount', $directorcount) }}">
					@if ($errors->has('directorcount')) <p class="bg-danger">{{ $errors->first('directorcount') }}</p> @endif
				</div>					<!-- end col 1 -->
			</div>				<!-- end row 8 -->
		</div> <!-- end directors tab -->
		@if (isset($mode))
			@if ($company->companytype_id == 1)
				@php $activetab = ''; @endphp
			@else
				@php $activetab = 'BankData'; @endphp
			@endif			
		@endif
		<div id="bankdata" class="row {{$activetab == 'BankData' ? '' : 'hidden' }}">
			<div class="row">	<!-- row 9 -->
				<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
					@if (isset($mode))
						<h4>Bank Details</h4>
					@endif
				</div>
				@if (Gate::allows('co_ch') && isset($company))
					<div class="col-sm-3 edit-icon-view d-ib"> <!-- Column 1 -->
						@if (isset($mode) && !$company->confirmed)
							<a href="{{ url("/company/" . $company->id) . '/BankData' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					</div>
				@endif
			</div>
			<div>	<!-- row 10 -->
				<div class="col-sm-12">  <!-- column 1 -->
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- account name -->  
						{{ Form::label('accountname', 'Account Name', array('class' => 'control-label bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->accountname }}</p>
						@else	
							<div class=" col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('accountname', Input::old('accountname'), array('id' => 'accountname', 'class' => 'form-control')) }}								
								@if ($errors->has('accountname')) <p class="bg-danger">{{ $errors->first('accountname') }}</p> @endif
							</div>
						@endif
					</div> <!-- Bank name -->
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- bank name -->  
						{{ Form::label('bankname', 'Bank Name', array('class' => 'control-label bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->bankname }}</p>
						@else	
							<div class=" col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('bankname', Input::old('bankname'), array('id' => 'bankname', 'class' => 'form-control')) }}								
								@if ($errors->has('bankname')) <p class="bg-danger">{{ $errors->first('bankname') }}</p> @endif
							</div>
						@endif
					</div> <!-- bank name --> 
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- account number -->  
						{{ Form::label('accountnumber', 'Account Number', array('class' => 'control-label bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->accountnumber }}</p>
						@else
							<div class=" col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('accountnumber', Input::old('accountnumber'), array('id' => 'accountnumber', 'class' => 'form-control')) }}								
								@if ($errors->has('accountnumber')) <p class="bg-danger">{{ $errors->first('accountnumber') }}</p> @endif
							</div>
						@endif
					</div> <!-- account number -->  
					<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- iban -->  
						{{ Form::label('iban', 'IBAN', array('class' => 'control-label bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->iban }}</p>
						@else		
							<div class=" col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('iban', Input::old('iban'), array('id' => 'iban', 'class' => 'form-control')) }}								
								@if ($errors->has('iban')) <p class="bg-danger">{{ $errors->first('iban') }}</p> @endif
							</div>
						@endif
					</div> <!-- iban -->  
					<div class="form-group {{ isset($mode) ? 'form-group--view' : 'required'}}"> <!-- routing code -->  
						{{ Form::label('routingcode', 'Routing Code', array('class' => 'control-label bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->routingcode }}</p>
						@else
							<div class=" col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('routingcode', Input::old('routingcode'), array('id' => 'routingcode', 'class' => 'form-control')) }}								
								@if ($errors->has('routingcode')) <p class="bg-danger">{{ $errors->first('routingcode') }}</p> @endif
							</div>
						@endif
					</div> <!-- routing code -->  
					<div class="form-group {{ isset($mode) ? 'form-group--view' : 'required'}}"> <!-- swift -->  
						{{ Form::label('swift', 'SWIFT Code', array('class' => 'control-label bm-label col-sm-3 col-xs-12' )) }}
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->swift }}</p>
						@else
							<div class=" col-lg-6 col-sm-9 col-xs-12 inp-container">
								{{ Form::text('swift', Input::old('swift'), array('id' => 'swift', 'class' => 'form-control')) }}								
								@if ($errors->has('swift')) <p class="bg-danger">{{ $errors->first('swift') }}</p> @endif
							</div>
						@endif
					</div> <!-- swift -->  
				</div>					<!-- end col 3 -->
			</div>				<!-- end row 10 -->
		</div> <!-- end banks tab -->
		@if (isset($mode))
			@php $activetab = 'Business'; @endphp
		@endif
		<div id="business" class="row {{$activetab == 'Business' ? '' : 'hidden' }}">
			<div class="row">	<!-- row 7 -->
				<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
					<h4>Top 5 Brands</h4>
				</div>
				<div class="edit-icon-view col-sm-7 d-ib"> <!-- Column 1 -->
					@if (Gate::allows('co_ch') && isset($company))
						@if (isset($mode) && !$company->confirmed)
							<a href="{{ url("/company/" . $company->id) . '/Business' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					@endif
				</div>			
			</div>
			<div class="row">	<!-- row 9 -->
				<div class="col-sm-12 table-container"> <!-- Column 1 -->					
					@php 
						$topproductcount = 0; 
						$topproductsum = 0; 
					@endphp
					<table id="topproducttable" class="form-table table table-striped table-bordered table-hover">
						<thead>
							<tr>
								@if (isset($mode))
									<th>Brand</th>
									<th>Revenue %</th>
								@else
									<th class="no-sort" width="10%">
										<a href="" id="lnktopproduct" role="button" class="add-icon" title="Add brand"></a>	
									</th>
									<th class="col-md-11">&nbsp;</th>
								@endif								
							</tr>		
						</thead>
						<tbody>
						@if (old('topproductname') || old('topproductid'))
							@for ($i = 0; $i < count(old('topproductname')); $i++)
								<tr style="{{ old('topproductdel')[$i] ? 'display:none' : ''  }}">
									<td style="vertical-align:top">
										<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
										{{ Form::hidden('topproductid[]', old('topproductid')[$i], array('id' => 'topproduct_id')) }}
										{{ Form::hidden('topproductdel[]', old('topproductdel')[$i], array('id' => 'topproductdel', 'class' => 'form-control')) }}
									</td>
									<td>
										<div>
											<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- brand -->
												{{ Form::label('topproductname', 'Brand', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
												<div class="col-lg-6 col-sm-9 col-xs-12">
													<select name="topproductname[]" class="form-control bm-select" id="topproductname">
														@foreach ($brandsarr as $brand_id => $brand_name)
														<option value="{{ $brand_id }}" <?= (!empty(old('topproductname')) && old('topproductname')[$i] == $brand_id) ? 'selected' : '' ?> >{{ $brand_name }}</option>
														@endforeach
													</select>
													@if ($errors->has('topproductname.' . $i)) <p class="bg-danger">{{ $errors->first('topproductname.' . $i) }}</p> @endif
												</div>
											</div> <!-- brand end --> 
											<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- revenue % -->  
												{{ Form::label('topproductrevenue', 'Revenue %', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
												<div class=" col-lg-6 col-sm-9 col-xs-12">
													{{ Form::text('topproductrevenue[]', old('topproductrevenue')[$i], array('id' => 'topproductrevenue', 'class' => 'form-control')) }}
													@if ($errors->has('topproductrevenue.' . $i)) <p class="bg-danger">{{ $errors->first('topproductrevenue.' . $i) }}</p> @endif
												</div>
											</div> <!-- revenue % end --> 
										</div>
									</td>
								</tr>
								@php
									if(!$errors->has('topproductrevenue.' . $i)){
										$topproductsum = $topproductsum + old('topproductrevenue')[$i];
										$topproductcount = $i + 1;
									}
								@endphp	
							@endfor
						@else
							@if (isset($company))
								<?php $i = 0 ; ?>
								@foreach ($company->companytopproducts as $topproduct)
									<tr>							
										@if (isset($mode))
											<td>{{ $topproduct->brand->name }}</td>
											<td align="right">{{ $topproduct->topproductrevenue }}</td>										
										@else
											<td style="vertical-align:top">
												<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelTopproduct"></a>
												{{ Form::hidden('topproductid[]', $topproduct->id, array('id' => 'topproduct_id')) }}
												{{ Form::hidden('topproductdel[]', '', array('id' => 'topproductdel', 'class' => 'form-control')) }}
											</td>
											<td>
												<div>
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- brand -->  
														{{ Form::label('topproductname', 'Brand', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
														@if (isset($mode))	
															<p class='form-control-static'>{{ $owner->ownername }}</p>
														@else
															<div class=" col-lg-6 col-sm-9 col-xs-12">
																{{ Form::select('topproductname[]', $brandsarr, $topproduct->topproductname,array('id' => 'topproductname', 'class' => 'form-control bm-select'))}}		
																@if ($errors->has('topproductname.' . $i)) <p class="bg-danger">{{ $errors->first('topproductname.' . $i) }}</p> @endif
															</div>
														@endif
													</div> <!-- brand end -->
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- revenue % -->  
														{{ Form::label('topproduct', 'Revenue %', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
														@if (isset($mode))	
															<p class='form-control-static'>{{ $topproduct->topproductrevenue }}</p>
														@else
															<div class=" col-lg-6 col-sm-9 col-xs-12">
																{{ Form::text('topproductrevenue[]', $topproduct->topproductrevenue, array('id' => 'topproductrevenue', 'class' => 'form-control')) }}
																@if ($errors->has('topproduct.' . $i)) <p class="bg-danger">{{ $errors->first('topproduct.' . $i) }}</p> @endif
															</div>
														@endif
													</div> <!-- Revenue % end --> 														

												</div>
											</td>
										@endif
									</tr>
									<?php $i = $i + 1 ; 
										$topproductcount = $i;
										$topproductsum = $topproductsum + $topproduct->topproductrevenue;
									?>
								@endforeach
							@endif
						@endif
						</tbody>
					</table>
					<input type="hidden" name="topproductcount" id="topproductcount" value="{{ old('topproductcount', $topproductcount) }}">
					<input type="hidden" name="topproductsum" id="topproductsum" value="{{ $topproductsum }}">
					@if ($errors->has('topproductcount')) <p class="bg-danger">{{ $errors->first('topproductcount') }}</p> @endif
					@if ($errors->has('topproductsum')) <p class="bg-danger">{{ $errors->first('topproductsum') }}</p> @endif
				</div> <!-- Column 2 end -->
			</div>
			@if (isset($company) && ($company->companytype_id == 2 || $company->companytype_id == 3))
				<div class="row">
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Top 5 Buyers</h4>
					</div>
					<div class="edit-icon-view col-sm-7 d-ib"> <!-- Column 1 -->
						@if (Gate::allows('co_ch') && isset($company))
							@if (isset($mode) && !$company->confirmed)
								<a href="{{ url("/company/" . $company->id) . '/Business' }}"><span class="edit-icon--with-border"></span></a>
							@endif
						@endif
					</div>
				</div>
				<div class="row">
					<div class=" col-sm-12 table-container"> <!-- Column 2 -->
						<?php $topcustomercount = 0; ?>
						<table id="topcustomertable" class="form-table table table-striped table-bordered table-hover">
							<thead>
								<tr>
									@if (isset($mode))
										<th>Buyer</th>
									@else									
										<th class="no-sort" width="10%">
											<a href="" id="lnktopcustomer" role="button" class="add-icon" title="Add customer"></a>	
										</th>
										<th class="col-md-11">&nbsp;</th>
									@endif								
								</tr>		
							</thead>
							<tbody>
								@if (old('topcustomerid'))
									@php
										$i = 0;
									@endphp
									@foreach (old('topcustomerid') as $item)
										<tr style="{{ (old('topcustomerdel')[$i]) ? 'display:none' : '' }}">
											<td style="vertical-align:top">
												<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
												{{ Form::hidden('topcustomerid[]', old('topcustomerid')[$i], array('id' => 'topcustomer_id')) }}
												{{ Form::hidden('topcustomerdel[]', old('topcustomerdel')[$i], array('id' => 'topcustomerdel', 'class' => 'form-control')) }}
											</td>
											<td>
												<div>
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- customer -->  
														{{ Form::label('topcustomername', 'Buyer', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-lg-6 col-sm-9 col-xs-12">
															{{ Form::text('topcustomername[]', old('topcustomername')[$i], array('id' => 'topcustomername', 'class' => 'form-control')) }}
															@if ($errors->has('topcustomername.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomername.' . $i) }}</p> @endif
														</div>
													</div> <!-- customer end --> 
												</div>
											</td>
										</tr>
									@php
										$i++;
										$topcustomercount = $i;
									@endphp	
									@endforeach
								@else
									@if (isset($company))
										<?php $i = 0 ; ?>
										@foreach ($company->companytopcustomers as $topcustomer)
											<tr>							
												@if (isset($mode))								
													<td>{{ $topcustomer->topcustomername }}</td>
												@else
													<td style="vertical-align:top">
														<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelTopcustomer"></a>
														{{ Form::hidden('topcustomerid[]', $topcustomer->id, array('id' => 'topcustomer_id')) }}
														{{ Form::hidden('topcustomerdel[]', '', array('id' => 'topcustomerdel', 'class' => 'form-control')) }}
													</td>
													<td>
														<div>
															<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- brand -->  
																{{ Form::label('topcustomername', 'Buyer', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $owner->ownername }}</p>
																@else
																	<div class=" col-lg-6 col-sm-9 col-xs-12">
																		{{ Form::text('topcustomername[]', $topcustomer->topcustomername, array('id' => 'topcustomername', 'class' => 'form-control')) }}
																		@if ($errors->has('topcustomername.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomername.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- brand end -->
														</div>
													</td>
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
				</div>				<!-- end row 9 -->
			@endif
			@if (isset($company) && ($company->companytype_id == 1 || $company->companytype_id == 3))
				<div class="row">
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Top 5 Suppliers</h4>
					</div>
					<div class="edit-icon-view col-sm-7 d-ib"> <!-- Column 1 -->
						@if (Gate::allows('co_ch') && isset($company))
							@if (isset($mode) && !$company->confirmed)
								<a href="{{ url("/company/" . $company->id) . '/Business' }}"><span class="edit-icon--with-border"></span></a>
							@endif
						@endif
					</div>
				</div>
				<div class="row">
					<div class=" col-sm-12 table-container"> <!-- Column 2 -->
						<?php $topsuppliercount = 0; ?>
						<table id="topsuppliertable" class="form-table table table-striped table-bordered table-hover">
							<thead>
								<tr>
									@if (isset($mode))
										<th>Supplier</th>
									@else									
										<th class="no-sort" width="10%">
											<a href="" id="lnktopsupplier" role="button" class="add-icon" title="Add supplier"></a>	
										</th>
										<th class="col-md-11">&nbsp;</th>
									@endif								
								</tr>		
							</thead>
							<tbody>
								@if (old('topsupplierid'))
									@php
										$i = 0;
									@endphp
									@foreach (old('topsupplierid') as $item)
										<tr style="{{ (old('topsupplierdel')[$i]) ? 'display:none' : '' }}">
											<td style="vertical-align:top">
												<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
												{{ Form::hidden('topsupplierid[]', old('topsupplierid')[$i], array('id' => 'topsupplier_id')) }}
												{{ Form::hidden('topsupplierdel[]', old('topsupplierdel')[$i], array('id' => 'topsupplierdel', 'class' => 'form-control')) }}
											</td>
											<td>
												<div>
													<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- supplier -->  
														{{ Form::label('topsuppliername', 'Supplier', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
														<div class=" col-lg-6 col-sm-9 col-xs-12">
															{{ Form::text('topsuppliername[]', old('topsuppliername')[$i], array('id' => 'topsuppliername', 'class' => 'form-control')) }}
															@if ($errors->has('topsuppliername.' . $i)) <p class="bg-danger">{{ $errors->first('topsuppliername.' . $i) }}</p> @endif
														</div>
													</div> <!-- supplier end --> 
												</div>
											</td>
										</tr>
									@php
										$i++;
										$topsuppliercount = $i;
									@endphp	
									@endforeach
								@else
									@if (isset($company))
										<?php $i = 0 ; ?>
										@foreach ($company->companytopsuppliers as $topsupplier)
											<tr>							
												@if (isset($mode))								
													<td>{{ $topsupplier->topsuppliername }}</td>
												@else
													<td style="vertical-align:top">
														<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelTopsupplier"></a>
														{{ Form::hidden('topsupplierid[]', $topsupplier->id, array('id' => 'topsupplier_id')) }}
														{{ Form::hidden('topsupplierdel[]', '', array('id' => 'topsupplierdel', 'class' => 'form-control')) }}
													</td>
													<td>
														<div>
															<div class="form-group {{ isset($mode) ? ''  : 'required'}}"> <!-- brand -->  
																{{ Form::label('topsuppliername', 'Supplier', array('class' => 'control-label bm-label col-sm-3 col-xs-12')) }}
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $owner->ownername }}</p>
																@else
																	<div class=" col-lg-6 col-sm-9 col-xs-12">
																		{{ Form::text('topsuppliername[]', $topsupplier->topsuppliername, array('id' => 'topsuppliername', 'class' => 'form-control')) }}
																		@if ($errors->has('topsuppliername.' . $i)) <p class="bg-danger">{{ $errors->first('topsuppliername.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- brand end -->
														</div>
													</td>
													</td>
												@endif
											</tr>
											<?php $i = $i + 1 ; 
												$topsuppliercount = $i;
											?>
										@endforeach
									@endif
								@endif
							</tbody>
						</table>
						<input type="hidden" name="topsuppliercount" id="topsuppliercount" value="{{ old('topsuppliercount', $topsuppliercount) }}">
						@if ($errors->has('topsuppliercount')) <p class="bg-danger">{{ $errors->first('topsuppliercount') }}</p> @endif
					</div> <!-- Column 2 end -->
				</div>				<!-- end row 9 -->
			@endif
		</div> <!-- end products tab -->
		<div class="row">
			<div class=" col-sm-12 table-container tb-footer-container"> <!-- Column 1 -->
				@if (isset($mode))
					@if (!$company->confirmed && Gate::allows('co_cr') && $company->iscomplete)
						<div class="row">
						<div class="col-md-9 col-sm-7">
								<div class="checkbox">
									<label class="checkbox">
										<input class="bm-checkbox" type="checkbox" name="cbconfirm" id ="cbconfirm">
										<span class="checkmark"></span>
										<span class="bm-sublabel">I hereby confirm that the above data and attachments are correct.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-3 col-md-2"> <!-- Column 1 -->
								{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
								<a href="" class="btn btn-primary bm-btn green" id="lnkconfirm" type="button" title="Confirm">
									Confirm
								</a>
							</div>
						</div>
					@endif
				@else
				<span class="note">
					<span class="red">*</span><small> denotes a required field</small>
				</span>
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
					
					<ul class="list-inline">
						@if ($onetab  != 1)
							<li class="hidden-xs"><button id="previous" type="button" class="btn btn-default prev-step bm-btn">Previous</button></li>
						@endif
						@switch($activetab)
							@case('BasicInfo')
								@if (isset($company) && ($company->companytype_id == 2 || $company->companytype_id == 3))
									@php $nexttabname = 'Bank Details' @endphp
								@else
									@php $nexttabname = 'Shareholders' @endphp
								@endif						
								@break
							@case('Shareholders')
								@php $nexttabname = 'Directors' @endphp
								@break
							@case('Directors')
								@if ($company->companytype_id != 1)
									@php $nexttabname = 'Bank Details' @endphp
								@else
									@php $nexttabname = 'Business' @endphp
								@endif						
								@break
							@case('BankData')
								@php $nexttabname = 'Business' @endphp
								@break
							@case('Business')
								@php $nexttabname = 'Finish' @endphp
								@break
						@endswitch
						@if ($onetab  == 1)
							<li style="width: 100%"><button type="button" class="btn btn-default next-step bm-btn green">Save</button></li>
						@else
							<li><button type="button" class="btn btn-default next-step bm-btn"><?= $nexttabname != 'Finish' ? 'Next <i class="fa fa-arrow-right"></i>' : '' ?> {{ $nexttabname }}</button></li>
						@endif
					</ul>  
				@endif			
		</div> <!-- Column 1 end -->
	</div> <!--row 10 end -->
	{{ Form::close() }}
	@if (isset($mode))
		@if (isset($company) && $company->confirmed && $company->iscomplete && Gate::allows('co_ch', $company->id))
				@if ($company->confirmed)
				<div class="row">
					<div class="col-xs-12" style="padding-left: 0">
							@if(isset($confirmMessage))
								<div class="alert alert-success">
									<p class="bg-success"><strong>Sign contract</strong></p>
									<p class="bg-success">{{ $confirmMessage }}</a></p>
								</div>
							@else
								<div class="alert alert-warning">
									<p class="bg-warning"><strong>You can not edit company</strong></p>
									<p class="bg-warning">If you want to change company data, please send an email to <a href="mailto:bizzmo@bizzmo.com" target="_top"><strong>bizzmo@bizzmo.com</strong></a></p>
								</div>
							@endif							
						</div>
					</div>
				@else
					<a href="{{ url('/companies/' . $company->id) }}" title="Edit" role="button" class="btn bm-btn sun-flower fixedw_button">
						<span class="edit-icon-white hidden-xs hidden-sm"></span>
						<span class="visible-sm visible-xs">Edit</span>
					</a>									
				@endif
			@endif
		@if ($company->active && $company->creditrequests->count() == 0 && $company->customer_signed && Gate::allows('cr_cr') && $company->companytype_id != 2)
			<div class="row">	<!-- row 10 -->
				<div class="col-sm-12"> <!-- Column 1 -->
					<div class="alert alert-warning">
						<p class="bg-warning"><strong>Credit alert</strong></p>
						<p class="bg-warning">You did not apply for a credit line yet. Click <a href="/creditrequests/create/{!!$company->id!!}">here</a> to apply.</p>
					</div>
				</div> <!-- Column 1 end -->
			</div> <!--row 10 end -->
		@endif
	@endif
@stop	
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			var phone = document.getElementById("phone");
			var fax = document.getElementById("fax");
			var ownerphone = document.getElementById("ownerphone");
			var directorphone = document.getElementById("directorphone");
			if (phone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(phone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(phone);
			}
			if (fax) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(fax);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(fax);
			}
			if (ownerphone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(ownerphone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(ownerphone);
			}
			if (directorphone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(directorphone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(directorphone);
			}

			if (performance.navigation.type == 1)
				$("#activetab").val("{{ $activetab }}");
			var country_changed_to_allowed = false;
			checkCountryForType(false);
			SetTabsStatus();
			var activetab = $("#activetab").val();
			if (activetab == 'BasicInfo')
				$("#previous").hide();
			else
				$("#previous").show();
				
			if ($("#companytype_buyer").is(":checked") && !$("#companytype_supplier").is(":checked"))
				tabdivs($("#companytype_buyer").val());
			else if ($("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked"))
				tabdivs($("#companytype_supplier").val());
			else 
				tabdivs('3');

			$("#submit").hide();
			
			$("#lnkconfirm").bind('click', function(e) {
				e.preventDefault();
				if (!$("#cbconfirm").is(':checked')) {
					alert('You must check the confirmation text.');
				} else {
					$("#submit").click();
				}
			});

			$('.select2m').select2();
				
			$("#cbsame").bind('click', function(e) {
				if ($("#cbsame").hasClass('disabled')) {
						e.preventDefault();
						return false;
				}
				if ($("#cbsame").is(':checked')) {					
					$("#lnkbeneficial").addClass('disabled');
					$("#lnkbeneficial").attr("disabled", true)
					$("#sameowner").val(1);
				} else {
					$("#lnkbeneficial").removeClass('disabled');
					$("#lnkbeneficial").attr("disabled", false)
					$("#sameowner").val(0);
				}
			});
			
			$("input[id^='companytype_']").change(function(){
				is_buyer = $("#companytype_buyer").is(":checked");
				is_supplier = $("#companytype_supplier").is(":checked");

				if (is_buyer && is_supplier)
					tabdivs('3')
				else if(is_buyer)
					tabdivs($("#companytype_buyer").val());
				else if(is_supplier)
					tabdivs($("#companytype_supplier").val());
				
			})
			
			// $('input[type=radio][name=companytype_id]').change(function() {
			// 	tabdivs($(this).val());
			// });
			
			
			//next prev
			$(".next-step").click(function (e) {
				e.preventDefault();
				var activetab = $("#activetab").val();
				// Check for file(s) being uploaded
				if($("progress:visible").length)
					alert('Please wait till file is uploaded');
				switch (activetab) {
					case 'BasicInfo':
						if (!$("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked")) {
							alert('Please select company type');
							return;
						}
						if ($("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked")) 
							$("#newtab").val('BankData');
						else 
							$("#newtab").val('Shareholders');
						break;
					case 'Shareholders':
						var sum = 0;
						var allshares = document.getElementsByName('ownershare[]');
						for(var i=0;i<allshares.length;i++)
						{
							sum += parseFloat(allshares[i].value);
						}
						var allshares = document.getElementsByName('beneficialshare[]');
						for(var i=0;i<allshares.length;i++)
						{
							sum += parseFloat(allshares[i].value);
						}
						if (!isNaN(sum)) {
							if (sum < 100) {
								alert('Total shares is less than 100%');
							}
						}
						$("#newtab").val('Directors');
						break;
					case 'Directors':
						if ($("#companytype_buyer").is(":checked") && !$("#companytype_supplier").is(":checked")) 
							$("#newtab").val('Business');
						else 
							$("#newtab").val('BankData');
						break;
					case 'BankData':
						$("#newtab").val('Business');						
						break;
					case 'Business':
						$("#newtab").val('');
						break;
				}
				if ($(".next-step").html() == 'Save') {
					$("#newtab").val('');
				}
				var brandsum = Brandsum();
				$("#submit").click();
				return false;
			});
			$(".prev-step").click(function (e) {
				e.preventDefault;
				var activetab = $("#activetab").val();
				switch (activetab) {
					case 'BasicInfo':
						break;
					case 'Shareholders':
						$("#ownertable").remove();
						$("#activetab").val('BasicInfo');
						$("#newtab").val('');
						$("#shareholders").hide();
						$("#tabshareholders").removeClass('tab--active').addClass('tab--idle is-circle');
						$("#basicinfo").removeClass('hidden');
						$("#tabbasicinfo").removeClass('tab--done is-circle').addClass('tab--active');
						document.getElementById('tabshareholders').innerHTML = ' ';
						document.getElementById('tabbasicinfo').innerHTML = 'Basic Info';
						$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Shareholders');
						$("#previous").hide();
						break;
					case 'Directors':
						$('#directortable').remove();
						$("#activetab").val('Shareholders');
						$("#newtab").val('');
						$("#directors").hide();
						$("#shareholders").removeClass('hidden');
						$("#tabdirectors").addClass('tab--idle is-circle').removeClass('tab--active');
						$("#tabshareholders").addClass('tab--active').removeClass('tab--done is-circle');
						document.getElementById('tabdirectors').innerHTML = ' ';
						document.getElementById('tabshareholders').innerHTML = 'Shareholders';						
						$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Directors')
						break;
					case 'BankData':
						if ($("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked")) {
							$("#activetab").val('BasicInfo');
							$("#newtab").val('');
							$("#bankdata").hide();
							$("#basicinfo").removeClass('hidden');
							$("#tabbanks").addClass('tab--idle is-circle').removeClass('tab--active');
							$("#tabbasicinfo").addClass('tab--active').removeClass('tab--done is-circle');
							document.getElementById('tabbanks').innerHTML = ' ';
							document.getElementById('tabbasicinfo').innerHTML = 'Basic Info';
							$("#previous").hide();
						} else {
							$("#activetab").val('Directors');
							$("#newtab").val('');
							$("#bankdata").hide();
							$("#directors").removeClass('hidden');
							$("#tabbanks").addClass('tab--idle is-circle').removeClass('tab--active');
							$("#tabdirectors").addClass('tab--active').removeClass('tab--done is-circle');
							document.getElementById('tabbanks').innerHTML = ' ';
							document.getElementById('tabdirectors').innerHTML = 'Directors';						
						}
						$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Bank Details');
						break;
					case 'Business':
						$('#topsuppliertable').remove();
						$('#topcustomertable').remove();
						$('#topproducttable').remove();
						if ($("#companytype_buyer").is(":checked") && !$("#companytype_supplier").is(":checked")) {
							$("#activetab").val('Directors');
							$("#newtab").val('');
							$("#business").hide();
							$("#directors").removeClass('hidden');
							$("#tabbusiness").addClass('tab--idle is-circle').removeClass('tab--active');
							$("#tabdirectors").addClass('tab--active').removeClass('tab--done is-circle');
							document.getElementById('tabbusiness').innerHTML = ' ';
							document.getElementById('tabdirectors').innerHTML = 'Directors';						
						} else {
							$("#activetab").val('BankData');
							$("#newtab").val('');
							$("#business").hide();
							$("#bankdata").removeClass('hidden');
							$("#tabbusiness").addClass('tab--idle is-circle').removeClass('tab--active');
							$("#tabbanks").addClass('tab--active').removeClass('tab--done is-circle');
							document.getElementById('tabbusiness').innerHTML = ' ';
							document.getElementById('tabbanks').innerHTML = 'banks';						
						}
						$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Business')
						break;
				}
				//$("#submit").click();
				return false;

			});
			
			$(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
				alert('xxx');
			  if ($(this).hasClass("disabled")) {
				  alert('yy');
				e.preventDefault();
				return false;
			  }
			});
			//tabs end
			
			$("#country_id").change(function(){
				Updatecity();
				checkCountryForType();
			}); // $("#country_id").change end
			function checkCountryForType(changed_by_user = true) {
				var allowed = parseInt($('#country_id option:selected').attr("data-allowed"));
				if(changed_by_user) {
					buyer_checked = $("#companytype_buyer").is(":checked");
					supplier_checked = $("#companytype_supplier").is(":checked");
				}
				if(allowed) {
					if (changed_by_user) {
						if(!buyer_checked)
							$('#companytype_buyer').trigger('click');
						if(supplier_checked)
							$('#companytype_supplier').trigger('click');
					}
					$('#comp_type_wrapper').show();
					$('#supplier_only').hide();
					country_changed_to_allowed = true;
				}
				if (!allowed && country_changed_to_allowed) {
					if (changed_by_user) {
						if(buyer_checked)
							$('#companytype_buyer').trigger('click');
						if(!supplier_checked)
							$('#companytype_supplier').trigger('click');
					}
					$('#comp_type_wrapper').hide();
					$('#supplier_only').show();
				}
			}
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
			var phones = '<div>';
			phones += '<div style="position: absolute;right: 0;top: 0;margin-right: 20px;margin-top: 7px;">';
			phones += '<div class="tooltipnew">';
			phones += '<span class="tooltiptext">';
			phones += '+(1) 000 0000 0000<br/>';
			phones += '+(20) 00 0000 0000<br/>';
			phones += '+(971) 00 0000 0000<br/>';
			phones += '+(353) 0 000 0000<br/>';
			phones += '</span>';
			phones += '<span class="glyphicon glyphicon-question-sign" style="font-size: 20px;color:#717171">';
			phones += '</span>';
			phones += '</div>';
			phones += '</div>';
			phones += '</div>';

			$("#lnkowner").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('ownertable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td style="vertical-align:top">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelPDirector" type="button" title="Delete owner"></a>&nbsp;';
				row = row + '<input name="ownerid[]" type="hidden">';
				row = row + '<input name="ownerdel[]" id="ownerdel" type="hidden">';
				row = row + '</td>';
				row = row + '<td>';
				row = row + '<div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="ownername" class="tb-label control-label bm-label col-sm-3 col-xs-12">Name</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="ownername[]" class="form-control" name="ownername[]" value="" type="text"></div>';
				row = row + '</div>';			
				row = row + '<div class="form-group required">';
				row = row + '<label for="owneremail" class="tb-label control-label bm-label col-sm-3 col-xs-12">Email</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="owneremail[]" class="form-control" name="owneremail[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="ownerphone" class="tb-label control-label bm-label col-sm-3 col-xs-12">Phone</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="ownerphone[]" class="form-control mobile" name="ownerphone[]" value="" type="text", placeholder="+00000000000000">';
				//row = row + phones;
				row = row + '</div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="ownershare" class="tb-label control-label bm-label col-sm-3 col-xs-12">Share %</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="ownershare[]" class="form-control" name="ownershare[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="dirattachment_id" class="tb-label control-label bm-label col-sm-3 col-xs-12">Attachments</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12" style="position: relative;">';
				row = row + '<table class="attach-table form-table table uploadtable">';
				row = row + '<tr>';
				row = row + '<td rowspan="3" style="vertical-align:top">';
				row = row + '<input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<a href="#" class="attach-icon" onclick="Attachment(this,1);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>';
				row = row + '</td>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="1"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">ID</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 1);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="owneridfile[]" id="owneridfile" type="hidden" value=""> <input name="owneridattachid[]" id="owneridattachid" type="hidden" value=""><progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '<tr>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="9"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">Passport</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 9);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="ownerpptfile[]" id="ownerpptfile" type="hidden" value=""> <input name="ownerpptattachid[]" id="ownerpptattachid" type="hidden" value=""> <progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '</tr>';
				row = row + '<tr>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="2"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">Visa</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 3);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="ownervisafile[]" id="ownervisafile" type="hidden" value=""> <input name="ownervisaattachid[]" id="ownervisaattachid" type="hidden" value=""> <progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '</tr>';
				row = row + '</table>';
				row = row + '<input type="hidden" name="ownerattach[]" id="ownerattach" value="0">';
				row = row + '</div>';
				row = row + '</div>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#ownertable').append(row);
				$("#ownercount").val(parseInt($("#ownercount").val()) + 1);
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask($(".mobile"));
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask($(".mobile"));
				$('.uploadtable').on('change', '.attach', (event) => {
					AttachmentChange(event);
					pendingFileUpload();
				}); //$('.table').on('change', '.attach', () => {
			});
			$("#lnkbeneficial").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('beneficialtable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td style="vertical-align:top">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelPDirector" type="button" title="Delete beneficial"></a>&nbsp;';
				row = row + '<input name="beneficialid[]" type="hidden">';
				row = row + '<input name="beneficialdel[]" id="beneficialdel" type="hidden">';
				row = row + '</td>';
				row = row + '<td>';
				row = row + '<div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="beneficialname" class="tb-label control-label bm-label col-sm-3 col-xs-12">Name</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="beneficialname[]" class="form-control" name="beneficialname[]" value="" type="text"></div>';
				row = row + '</div>';			
				row = row + '<div class="form-group required">';
				row = row + '<label for="beneficialemail" class="tb-label control-label bm-label col-sm-3 col-xs-12">Email</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="beneficialemail[]" class="form-control" name="beneficialemail[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="beneficialphone" class="tb-label control-label bm-label col-sm-3 col-xs-12">Phone</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="beneficialphone[]" class="form-control mobile" name="beneficialphone[]" value="" type="text", placeholder="+00000000000000"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="beneficialshare" class="tb-label control-label bm-label col-sm-3 col-xs-12">Share %</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="beneficialshare[]" class="form-control" name="beneficialshare[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="dirattachment_id" class="tb-label control-label bm-label col-sm-3 col-xs-12">Attachments</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12" style="position: relative;">';
				row = row + '<table class="attach-table form-table table uploadtable">';
				row = row + '<tr>';
				row = row + '<td rowspan="3" style="vertical-align:top">';
				row = row + '<input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<a href="#" class="attach-icon" onclick="Attachment(this,1);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>';
				row = row + '</td>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="1"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">ID</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 1);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span><input name="beneficialidfile[]" id="beneficialidfile" type="hidden" value=""> <input name="beneficialidattachid[]" id="beneficialidattachid" type="hidden" value=""><progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '<tr>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="9"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">Passport</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 9);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="beneficialpptfile[]" id="beneficialpptfile" type="hidden" value=""> <input name="beneficialpptattachid[]" id="beneficialpptattachid" type="hidden" value=""> <progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '</tr>';
				row = row + '<tr>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="12"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">Visa</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 12);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="beneficialvisafile[]" id="beneficialvisafile" type="hidden" value=""> <input name="beneficialvisaattachid[]" id="beneficialvisaattachid" type="hidden" value=""> <progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '</tr>';
				row = row + '</table>';
				row = row + '<input type="hidden" name="beneficialattach[]" id="beneficialattach" value="0">';
				row = row + '</div>';
				row = row + '</div>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#beneficialtable').append(row);
				$("#beneficialcount").val(parseInt($("#beneficialcount").val()) + 1);
				$('#cbsame').addClass('disabled');
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask($(".mobile"));
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask($(".mobile"));
				$('.uploadtable').on('change', '.attach', (event) => {
					AttachmentChange(event);
					pendingFileUpload();
				}); //$('.table').on('change', '.attach', () => {
			});
			$("#lnkdirector").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('directortable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td style="vertical-align:top">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelPDirector" type="button" title="Delete director"></a>&nbsp;';
				row = row + '<input name="directorid[]" type="hidden">';
				row = row + '<input name="directordel[]" id="directordel" type="hidden">';
				row = row + '</td>';
				row = row + '<td>';
				row = row + '<div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="directorname" class="control-label bm-label col-sm-3 col-xs-12">Name</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="directorname[]" class="form-control" name="directorname[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="directortitle" class="control-label bm-label col-sm-3 col-xs-12">Job Title</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="directortitle[]" class="form-control" name="directortitle[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="directoremail" class="control-label bm-label col-sm-3 col-xs-12">Email</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="directoremail[]" class="form-control" name="directoremail[]" value="" type="text"></div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="directorphone" class="control-label bm-label col-sm-3 col-xs-12">Phone</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="directorphone[]" class="form-control mobile" name="directorphone[]" value="" type="text", placeholder="+00000000000000">';
				row = row + phones;
				row = row + '</div>';
				row = row + '</div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="dirattachment_id" class="control-label bm-label col-sm-3 col-xs-12">Attachments</label>';
				row = row + '<div class=" col-lg-6 col-sm-9 col-xs-12" style="position: relative;">';
				row = row + '<table class="attach-table form-table table uploadtable">';
				row = row + '<tr>';
				row = row + '<td rowspan="3" style="vertical-align:top">';
				row = row + '<input type="file" name="attach" id="attach" class="attach" style="display:none;">';
				row = row + '<a href="#" class="attach-icon" onclick="Attachment(this,3);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>';
				row = row + '</td>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="3"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">ID</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 3);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span>																		 <input name="directoridfile[]" id="directoridfile" type="hidden" value=""> <input name="directoridattachid[]" id="directoridattachid" type="hidden" value=""><progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '<tr>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="10"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">Passport</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 10);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="directorpptfile[]" id="directorpptfile" type="hidden" value=""> <input name="directorpptattachid[]" id="directorpptattachid" type="hidden" value=""> <progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '</tr>';
				row = row + '<tr>';
				row = row + '<td> <div class="radio"><label class="tb-label"><input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="4"><span class="bm-rd-checkmark"></span><span class="bm-sublabel">Visa</span></label></div></td>';
				row = row + '<td class="td-uploaded"><a href="#" onclick="DeleteAttachment(this, 4);return false;"><span class="cancel-icon hidden" title="Delete"></span></a> <span>No file selected</span> <input name="directorvisafile[]" id="directorvisafile" type="hidden" value=""> <input name="directorvisaattachid[]" id="directorvisaattachid" type="hidden" value=""> <progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress></td>';
				row = row + '</tr>';
				row = row + '</table>';
				row = row + '<input type="hidden" name="directorattach[]" id="directorattach" value="0">';
				row = row + '</div>';
				row = row + '</div>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#directortable').append(row);
				$("#directorcount").val(parseInt($("#directorcount").val()) + 1);
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask($(".mobile"));
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask($(".mobile"));
				$('.uploadtable').on('change', '.attach', (event) => {
					AttachmentChange(event);
					pendingFileUpload();
				}); //$('.table').on('change', '.attach', () => {
			});
			$("#lnktopproduct").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('topproducttable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td style="vertical-align: top">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelPTopproduct" type="button" title="Delete brand"></a>';
				row = row + '<input name="topproductid[]" type="hidden" class="form-control">';
				row = row + '<input name="topproductdel[]" id="topproductdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="topproductname" class="control-label bm-label col-sm-3 col-xs-12">Brand</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><select name="topproductname[]" class="form-control bm-select">';
				<?php
					if (isset($brands)) {
						foreach ($brands as $brand) {
							echo "row = row + '<option value=" . $brand->id .">" .$brand->name . "</option>';";
						}
					}
				?>
				row = row + '</select></div>';
				row = row + '</div>';			
				row = row + '<div class="form-group required">';
				row = row + '<label for="topproductrevenue" class="control-label bm-label col-sm-3 col-xs-12">Revenue %</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input name="topproductrevenue[]" type="text" class="form-control"></div>';
				row = row + '</div>';			
				row = row + '</div></td>';
				row = row + '</tr>';
				$('#topproducttable').append(row);							
				$("#topproductcount").val(parseInt($("#topproductcount").val()) + 1);				
			});
			$("#lnktopcustomer").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('topcustomertable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td style="vertical-align: top">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelPTopcustomer" type="button" title="Delete customer"></a>';
				row = row + '<input name="topcustomerid[]" type="hidden" class="form-control">';
				row = row + '<input name="topcustomerdel[]" id="topcustomerdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="topcustomername" class="control-label bm-label col-sm-3 col-xs-12">Buyer</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input name="topcustomername[]" type="text" class="form-control"></div>';
				row = row + '</div>';		
				row = row + '</div></td>';
				row = row + '</tr>';
				$('#topcustomertable').append(row);							
				$("#topcustomercount").val(parseInt($("#topcustomercount").val()) + 1);
			});
			$("#lnktopsupplier").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('topsuppliertable');
				var rowLength = table.rows.length;
				var row = '<tr>';							
				row = row + '<td>';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelPTopsupplier" type="button" title="Delete supplier"></a>';
				row = row + '<input name="topsupplierid[]" type="hidden" class="form-control">';
				row = row + '<input name="topsupplierdel[]" id="topsupplierdel" type="hidden" class="form-control">';
				row = row + '</td>';
				row = row + '<td><div>';
				row = row + '<div class="form-group required">';
				row = row + '<label for="topsuppliername" class="control-label bm-label col-sm-3 col-xs-12">Supplier</label>';
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input name="topsuppliername[]" type="text" class="form-control"></div>';
				row = row + '</div>';		
				row = row + '</div></td>';
				row = row + '</tr>';
				$('#topsuppliertable').append(row);							
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
			$('.uploadtable').on('change', '.attach', (event) => {
				AttachmentChange(event);
				pendingFileUpload();
			}); //$('.table').on('change', '.attach', () => {
			
			$('#tradeattach').on('change', (event) => {
				var inputElem = event.target;
				var file = inputElem.files[0];
				$('#tmptradefilename').val(file.name);

				type_is_valid = checkFileType(file.type);
				if (!type_is_valid)
					return false;
				
				size_is_valid = checkFileSize(file.size);
				if(!size_is_valid)
					return false;

				pendingFileUpload();
			
				var formData = new FormData;
				formData.append('attach', file);
				formData.append('_token', $('input[name=_token]').val());
					
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				$("#tradefilename").addClass('hidden');
				$("#progressBar").removeClass('hidden');
				ajax.open("POST", "/attach");
				ajax.send(formData);
				///////
				
                // $.ajax({					
                    // url: '/attach',
                    // type: 'POST',
                    // processData: false,
                    // contentType: false,
                    // cache: false,
                    // data: formData,
                    // dataType: 'JSON',
                    // success: function(response){
						// console.log(filename);						
						// $('#tradefile').val(filename);
						// $('#tradefilename').text('Selected file: ' + filename);
						// $('#tradeattachid').val(response);
                    // },
                    // error: function(e,a,b){
                        // console.log(e,a,b);
                    // }
                // });
			}); //$('#tradeattach').on('change', (event) => {
			
			$('#assocattach').on('change', (event) => {
				var inputElem = event.target;
				var file = inputElem.files[0];
				$('#tmpassocfilename').val(file.name);

				type_is_valid = checkFileType(file.type);
				if (!type_is_valid)
					return false;
				
				size_is_valid = checkFileSize(file.size);
				if(!size_is_valid)
					return false;

				pendingFileUpload();
			
				var formData = new FormData;
				formData.append('attach', file);
				formData.append('_token', $('input[name=_token]').val());
					
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", AssocprogressHandler, false);
				ajax.addEventListener("load", AssoccompleteHandler, false);
				$("#assocfilename").addClass('hidden');
				$("#AssocprogressBar").removeClass('hidden');
				ajax.open("POST", "/attach");
				ajax.send(formData);
				///////
				
                // $.ajax({					
                    // url: '/attach',
                    // type: 'POST',
                    // processData: false,
                    // contentType: false,
                    // cache: false,
                    // data: formData,
                    // dataType: 'JSON',
                    // success: function(response){
						// console.log(filename);						
						// $('#assocfile').val(filename);
						// $('#assocfilename').text(filename);
						// $('#assocattachid').val(response);
                    // },
                    // error: function(e,a,b){
                        // console.log(e,a,b);
                    // }
                // });
			}); //$('#assocattach').on('change', (event) => {
				
			@if (isset($company) && $topproductcount == 0 && !$errors->has('topproductcount'))
			for (let i = 0; i < 5; i++) {
				$("#lnktopproduct").trigger('click');
				$("#topproductcount").val(5);
				$("#topproductsum").val(0);
			}
			@endif	
		});
		
		$('#tax_attach').on('change', (event) => {
			var inputElem = event.target;
			var file = inputElem.files[0];
			$('#tmp_tax_file_name').val(file.name);
			type_is_valid = checkFileType(file.type);
			if (!type_is_valid)
				return false;
			
			size_is_valid = checkFileSize(file.size);
			if(!size_is_valid)
				return false;
			
			pendingFileUpload();

			var formData = new FormData;
			formData.append('attach', file);
			formData.append('_token', $('input[name=_token]').val());

			var ajax = new XMLHttpRequest();
			ajax.upload.addEventListener("progress", taxProgressHandler, false);
			ajax.addEventListener("load", taxCompleteHandler, false);
			$("#rax_file_name").addClass('hidden');
			$("#tax_progress_bar").removeClass('hidden');
			ajax.open("POST", "/attach");
			ajax.send(formData);
		});

		function checkFileSize(fileSize) {
			if (fileSize > 2097152) {
				alert('Maximum file size should be 2M');
				return false;
			}
			return true;
		}

		function checkFileType(fileType) {
			var plainType = fileType.split('/')[1];
			if($.inArray(plainType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
				alert('Only PDF, JPEG, JPG, PNG files are allowed');
				return false;
			}
			return true;
		}
		
		// Handle Upload Side Effects for Next btn
		function pendingFileUpload() {
			$(".next-step").attr( "title", "Please wait till file is uploaded" );
			$(".next-step").attr("disabled", true);
		}

		function completeFileUpload() {
			if (!$("progress:visible").length) {
				$(".next-step").attr( "title", "" );
				$(".next-step").attr("disabled", false);
			}
		}

		function _(el) {
			return document.getElementById(el);
		}

		function progressHandler(event) {
			//console.log('as' + event.loaded);
			//_("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
			var percent = (event.loaded / event.total) * 100;
			_("progressBar").value = Math.round(percent);
			// console.log(percent);
			//_("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
		}
		
		function completeHandler(event) {
			//console.log(event.target.responseText);
			//_("status").innerHTML = event.target.responseText;
			$("#tradefilename").removeClass('hidden');
			$("#progressBar").addClass('hidden');
			_("progressBar").value = 0;
			$('#tradefile').val($('#tmptradefilename').val());
			$('#tradefilename').text('Selected file: ' + $('#tmptradefilename').val());
			$('#tradeattachid').val(event.target.responseText);
			completeFileUpload();
		}

		function AssocprogressHandler(event) {
			//console.log('as' + event.loaded);
			//_("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
			var percent = (event.loaded / event.total) * 100;
			_("AssocprogressBar").value = Math.round(percent);
			// console.log(percent);
			//_("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
		}
		
		function AssoccompleteHandler(event) {
			//console.log(event.target.responseText);
			//_("status").innerHTML = event.target.responseText;
			$("#assocfilename").removeClass('hidden');
			$("#AssocprogressBar").addClass('hidden');
			_("AssocprogressBar").value = 0;
			$('#assocfile').val($('#tmpassocfilename').val());
			$('#assocfilename').text('Selected file: ' + $('#tmpassocfilename').val());
			$('#assocattachid').val(event.target.responseText);
			completeFileUpload();
		}

		function taxProgressHandler(event) {
			var percent = (event.loaded / event.total) * 100;
			_("tax_progress_bar").value = Math.round(percent);
		}

		function taxCompleteHandler(event) {
			$("#tax_file_name").removeClass('hidden');
			$("#tax_progress_bar").addClass('hidden');
			_("tax_progress_bar").value = 0;
			$('#tax_file').val($('#tmp_tax_file_name').val());
			$('#tax_file_name').text('Selected file: ' + $('#tmp_tax_file_name').val());
			$('#tax_attach_id').val(event.target.responseText);
			completeFileUpload();
		}
		
		function UploadprogressHandler(event, atttype, table) {
			if (atttype == 1 || atttype == 3 || atttype == 11) {
				var span = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("progress")[0];
			} else if (atttype == 9 || atttype == 10 || atttype == 13){
				var span = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];				
			}  else if (atttype == 2 || atttype == 4 || atttype == 12){
				var span = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
			}			
			//console.log(span.innerText);
			
			//var progresses = td.getElementsByTagName("progress");
			var percent = (event.loaded / event.total) * 100;
			//_("progressBar").value = Math.round(percent);
			progress.value = Math.round(percent);
			//console.log('p' + percent);
		}
		
		function UploadcompleteHandler(event, atttype, filename, table) {
			var attachcount = table.parentNode.getElementsByTagName("input")[10];
			// console.log(attachcount);
			if (atttype == 1 || atttype == 3 || atttype == 11) {
				var span = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[1];
				var delspan = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[0];
				var progress = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("progress")[0];
				var thefilename = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("input")[0];
				var fileid = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("input")[1];
				if (fileid.value == '') {
					attachcount.value = parseInt(attachcount.value) + 1;
				}
			} else if (atttype == 9 || atttype == 10 || atttype == 13){
				var span = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var delspan = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[0];
				var progress = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
				var thefilename = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("input")[0];
				var fileid = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("input")[1];
				if (fileid.value == '') {
					attachcount.value = parseInt(attachcount.value) + 1;
				}
			}  else if (atttype == 2 || atttype == 4 || atttype == 12){
				var span = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var delspan = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("span")[0];
				var progress = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
				var thefilename = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("input")[0];
				var fileid = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("input")[1];
			}
			span.innerText = filename;
			span.classList.remove('hidden');
			delspan.classList.remove('hidden');			
			progress.value = 0;
			progress.classList.add('hidden');
			$("#tradefilename").removeClass('hidden');
			$("#progressBar").addClass('hidden');
			thefilename.value = filename;
			fileid.value = event.target.responseText;
			_("progressBar").value = 0;
			$('#tradefile').val($('#tmptradefilename').val());
			$('#tradefilename').text('Selected file: ' + $('#tmptradefilename').val());
			$('#tradeattachid').val(event.target.responseText);
			completeFileUpload();
		}
		
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
						tr.cells[1].getElementsByTagName("input")[2].value='A';
						tr.cells[1].getElementsByTagName("input")[1].value='A@a.com';
						tr.cells[1].getElementsByTagName("input")[3].value='0';
						var tbl = tr.cells[1].getElementsByTagName("table")[0];
						// console.log(tbl.getElementsByTagName("input")[2]);
						tbl.getElementsByTagName("input")[2].value = 'A'
						tbl.getElementsByTagName("input")[7].value = '1'
						$("#ownercount").val(parseInt($("#ownercount").val()) - 1);
					} else if (inputval == 'beneficialdel') {
						inputs[j].value  = 1;
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';												
						tr.cells[1].getElementsByTagName("input")[2].value='A';
						tr.cells[1].getElementsByTagName("input")[1].value='a@a.com';
						tr.cells[1].getElementsByTagName("input")[3].value='0';
						var tbl = tr.cells[1].getElementsByTagName("table")[0];
						// console.log(tbl.getElementsByTagName("input")[2]);
						tbl.getElementsByTagName("input")[2].value = 'A'
						tbl.getElementsByTagName("input")[7].value = '1'
						$("#beneficialcount").val(parseInt($("#beneficialcount").val()) - 1);
						if ($("#beneficialcount").val() == "0") {
							$("#cbsame").removeClass('disabled');
						}
					} else if (inputval == 'directordel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						tr.cells[1].getElementsByTagName("input")[1].value='A';
						tr.cells[1].getElementsByTagName("input")[2].value='A@a.a';
						tr.cells[1].getElementsByTagName("input")[3].value='A';
						var tbl = tr.cells[1].getElementsByTagName("table")[0];
						// console.log(tbl.getElementsByTagName("input")[2]);
						tbl.getElementsByTagName("input")[2].value = 'A'
						tbl.getElementsByTagName("input")[7].value = '1'
						$("#directorcount").val(parseInt($("#directorcount").val()) - 1);
					} else if (inputval == 'topproductdel') {
						inputs[j].value  = 1;
						// tr.cells[1].getElementsByTagName("input")[0].value='A';						
						tr.cells[1].getElementsByTagName("input")[0].value='0';
						$("#topproductcount").val(parseInt($("#topproductcount").val()) - 1);
					} else if (inputval == 'topcustomerdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						$("#topcustomercount").val(parseInt($("#topcustomercount").val()) - 1);
					} else if (inputval == 'topsupplierdel') {
						inputs[j].value  = 1;
						tr.cells[1].getElementsByTagName("input")[0].value='A';						
						$("#topsuppliercount").val(parseInt($("#topsuppliercount").val()) - 1);
					}
					
				}
			tr.style.display = 'none';
		}
		
		function DeleteAttachment(lnk, atttype) {
			var table =lnk.parentNode.parentNode.parentNode.parentNode;
			var attachcount = table.parentNode.getElementsByTagName("input")[10];
			if (atttype == 3 || atttype == 10 || atttype == 11 || atttype == 13 || atttype == 1 || atttype == 9) {
				attachcount.value = parseInt(attachcount.value) - 1;
			}
			var td = lnk.parentNode;
			var span = td.getElementsByTagName("span")[0];
			var filespan = td.getElementsByTagName("span")[1];
			var filename = td.getElementsByTagName("input")[0];
			var fileid = td.getElementsByTagName("input")[1];			
			span.classList.add('hidden');
			filespan.innerText = "No file selected";
			filename.value="";
			fileid.value = "";			
		}
		
		function Attachment(lnk, atttype) {
			var table =lnk.parentNode.parentNode.parentNode.parentNode;
			var tr = lnk.parentNode.parentNode.parentNode;
			var td = lnk.parentNode.parentNode;
			//var spans = td.getElementsByTagName("span");
			//alert(spans[1].innerText);
			var cell = tr.children[1];
			var ids = td.getElementsByTagName("div")[0].getElementsByTagName("label")[0].getElementsByTagName("input");			
			idchecked = ids[0].checked;
			if (idchecked) {
				atttype = ids[0].value;
			}
			var passport = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[0].getElementsByTagName("div")[0].getElementsByTagName("label")[0].getElementsByTagName("input");			
			passportchecked = passport[0].checked;
			if (passportchecked) {
				atttype = passport[0].value;
			}
			var visa = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[0].getElementsByTagName("div")[0].getElementsByTagName("label")[0].getElementsByTagName("input");
			visachecked = visa[0].checked;
			if (visachecked) {
				atttype = visa[0].value;
			}
			//alert(visachecked);
			if (!idchecked && !passportchecked && !visachecked) {
				alert('Choose attachment type');
				return false;
			}
			// var as = td.getElementsByTagName("a");
			var inputs = td.getElementsByTagName("input");
			inputs[0].click();
			return false;
		}
		function Uploadtradefile(lnk) {			
			$("#tradeattach").click();
		}
		function Uploadassocfile(lnk) {			
			$("#assocattach").click();
		}
		function uploadTaxFile(lnk) {			
			$("#tax_attach").click();
		}
		function Updatecity() {
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
							// console.log(j);
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
			if (table) {
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
						if (row.style.display != 'none') {
							if (isNumber(row.cells[1].getElementsByTagName("input")[0].value))  {
								var revenue = parseInt(revenue) + parseInt(row.cells[1].getElementsByTagName("input")[0].value);
							}
						}
					}		
				}
				$("#topproductsum").val(revenue);
			}
			return true;
		}
		
		function AttachmentChange(event) {
			//alert('xx');
			//get selected attachment type
			var table = event.target.parentNode.parentNode.parentNode.parentNode;
			var tr = event.target.parentNode.parentNode.parentNode;
			var td = event.target.parentNode.parentNode;
			var cell = tr.children[1];
			var ids = td.getElementsByTagName("div")[0].getElementsByTagName("label")[0].getElementsByTagName("input");			
			idchecked = ids[0].checked;
			if (idchecked) {
				atttype = ids[0].value;
			}
			var passport = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[0].getElementsByTagName("div")[0].getElementsByTagName("label")[0].getElementsByTagName("input");			
			passportchecked = passport[0].checked;
			if (passportchecked) {
				atttype = passport[0].value;
			}
			var visa = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[0].getElementsByTagName("div")[0].getElementsByTagName("label")[0].getElementsByTagName("input");
			visachecked = visa[0].checked;
			if (visachecked) {
				atttype = visa[0].value;
			}
			//get selected attachment type end
			var fileInput = event.target,
				file = event.target.files[0],
				fileType = file.type.split('/')[1];
			var filename = file.name;
			if (atttype == 1 || atttype == 3|| atttype == 11) {
				var span = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("progress")[0];
			} else if (atttype == 9 || atttype == 10 || atttype == 13) {
				var span = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
			} else if (atttype == 2 || atttype == 4 || atttype == 12) {
				var span = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[2].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
			}
			var filesize = file.size;
			if (filesize > 2097152) {
				alert('Maximum file size should be 2M');
				return false;
			}
			if($.inArray(fileType.toLowerCase(), ['pdf', 'jpeg', 'jpg', 'png']) == -1) {
				alert('Only PDF, JPEG, JPG, PNG files are allowed');
				return false;
			}
			if (span)
				span.classList.add('hidden');

			if(progress)
				progress.classList.remove('hidden');	

			var formData = new FormData;
				formData.append('attach', file);
				formData.append('_token', $('input[name=_token]').val());
			
			///////	
			var cell = tr.children[2];
			var file = event.target.files[0];
			var ajax = new XMLHttpRequest();
			ajax.fileInfo = {filename: "" + event.target.files[0].name};
			ajax.upload.addEventListener("progress", function(e){UploadprogressHandler(e,atttype, event.target.parentNode.parentNode.parentNode.parentNode); }, false);
			ajax.addEventListener("load", function(e){UploadcompleteHandler(e,atttype, event.target.files[0].name, event.target.parentNode.parentNode.parentNode.parentNode); }, false);
			$("#tradefilename").addClass('hidden');
			$("#progressBar").removeClass('hidden');
			ajax.open("POST", "/attach");
			ajax.send(formData);
			///////
		}
		
		function tabdivs(companytype) {
			switch(companytype) {
				case '1' :
					$("#divshareholders").removeClass('hidden');
					$("#divdirectors").removeClass('hidden');
					$("#divbanks").addClass('hidden');
					if ($("#onetab").val() != '1') {
						switch($("#activetab").val()) {
							case 'BasicInfo' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Shareholders')
								break;
							case 'Directors' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Business')
						}						
					}
					break;
				case '2' :
					$("#divshareholders").addClass('hidden');
					$("#divdirectors").addClass('hidden');
					$("#divbanks").removeClass('hidden');
					if ($("#onetab").val() != '1') {
						switch($("#activetab").val()) {
							case 'BasicInfo' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Banks')
								break;
						}						
					}
					break;
				case '3' :
					$("#divshareholders").removeClass('hidden');
					$("#divdirectors").removeClass('hidden');
					$("#divbanks").removeClass('hidden');
					if ($("#onetab").val() != '1') {
						switch($("#activetab").val()) {
							case 'BasicInfo' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Shareholders')
								break;
							case 'Directors' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Banks')
						}						
					}
					break;
			}
		}

		function SetTabsStatus() {
			var activetab = $("#activetab").val();
				switch (activetab) {
					case 'BasicInfo':
						break;
					case 'Shareholders':
						SetAsDone("#tabbasicinfo");
						break;
					case 'Directors':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabshareholders");
						break;
					case 'BankData':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabshareholders");
						SetAsDone("#tabdirectors");				
						break;
					case 'Business':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabshareholders");
						SetAsDone("#tabdirectors");
						SetAsDone("#tabbanks");
						break;
				}
		}

		function SetAsDone(tab_id) {
			if(!$(tab_id).hasClass("hidden")){
				$(tab_id).removeClass("tab--idle").addClass("tab--done");
				$(tab_id + " .icon").removeClass("hidden");
			}
		}

		$(document).ready(function(){
			$(".cal-icon").on('click', function () {
				$("#incorporated").focus()
			})
		});
	</script>
@endpush