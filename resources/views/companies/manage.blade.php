
@extends('layouts.app'  )
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
		.co-history-table th, .co-history-table td {
			font-size: 11px;
		}
		.bo-history-table th, .bo-history-table td {
			font-size: 11px;
		}
		.do-history-table th, .do-history-table td {
			font-size: 11px;
		}
		.br-history-table th, .br-history-table td {
			font-size: 11px;
		}
		.bu-history-table th, .bu-history-table td {
			font-size: 11px;
		}
		.su-history-table th, .su-history-table td {
			font-size: 11px;
		}
	</style>
@stop	
@section('content')	
	@include('includes.company-profile-head')
	@if(Gate::allows('cr_ap'))
	<div class="header-container col-sm-12">
		<div class="title"></div>
		<div class="buttons">
		@if ($company->active && $company->confirmed)
			<a href="{{ url('/companies/deactivate/' . $company->id) }}" title="Deactivate" role="button" class="biz-button colored-yellow">
				<span>Deactivate</span>
			</a>
			@if (isset($pendingunregister) && !$pendingunregister)
				<a href="{{ url('/companies/deregisterrequest/' . $company->id) }}" title="Deregister"  class="biz-button colored-red">
					Deregister
				</a>
			@endif										  
		@elseif (!$company->active && $company->confirmed)
			<a href="{{ url('/companies/activate/' . $company->id) }}" title="Activate" role="button" class="biz-button colored-yellow">
				<span>Activate</span>
			</a>
		@endif
		<a href="{{ url('/company/changes/' . $company->id) }}" title="Changes" role="button" class="biz-button colored-green">
				<span>Changes</span>
		</a>
	</div>
	</div>
@endif
	@php
		$activetab = 'BasicInfo';
		$nexttabname = '';
		$action_title = 'Create';
	@endphp
	@if (isset($company))
		{{ Form::model($company, array('id' => 'frmManage', 'class' => 'form-horizontal col-md-12 col-xs-12', 'files' => true)) }}
		{{ Form::hidden('id', $company->id, array('id' => 'id', 'class' => 'form-control')) }}
		@if (isset($mode))
			{{-- <div class="flex-container bm-pg-header">	<!-- row 1 -->
				<!-- <h3 class="bm-title">{{ $title}}</h3> -->
			</div> --}}
		@endif
		@php
			$action_title = 'Edit';
			if ($errors->has('companyname') || $errors->has('address') || $errors->has('district') || $errors->has('phone') || $errors->has('fax') || $errors->has('pobox') || $errors->has('email') || $errors->has('license') || $errors->has('tradefile') || $errors->has('tax') || $errors->has('incorporated') || $errors->has('website') || $errors->has('assocfile') || $errors->has('taxFile') || $errors->has('industries')) 
				$activetab = 'BasicInfo';
			elseif ($errors->has('signatoryname') || $errors->has('signatoryemail') || $errors->has('signatoryphone'))
				$activetab = 'AuthorizedSignatory';
			elseif ($errors->has('ownername.*') || $errors->has('owneremail.*') || $errors->has('ownerphone.*') || $errors->has('ownershare.*') || $errors->has('ownerattach.*') || $errors->has('ownercount') || $errors->has('ownershare') || $errors->has('shares') || $errors->has('beneficialname.*') || $errors->has('beneficialemail.*') || $errors->has('beneficialphone.*') || $errors->has('beneficialshare.*') || $errors->has('beneficialattach.*') || $errors->has('beneficialcount') || $errors->has('beneficialshare') || $errors->has('shares')) 
				$activetab = 'Shareholders';
			elseif ($errors->has('directorname.*') || $errors->has('directortitle.*') || $errors->has('directoremail.*') || $errors->has('directorphone.*') || $errors->has('directorattach.*') || $errors->has('directorcount')) 
				$activetab = 'Directors';
			elseif ($errors->has('accountname') || $errors->has('bankname') || $errors->has('accountnumber') || $errors->has('iban') || $errors->has('routingcode') || $errors->has('swift') || $errors->has('currency_id')) 
				$activetab = 'BankData';
			elseif ($errors->has('topproductname.*') || $errors->has('topproductrevenue.*') || $errors->has('topproductcount') || $errors->has('topproductsum') || $errors->has('topcustomername.*') || $errors->has('topcustomercount') || $errors->has('topsuppliername.*') || $errors->has('topsuppliercount')) 
				$activetab = 'Business';
			elseif (strpos(url()->current(), 'BasicInfo')) 
				$activetab = 'BasicInfo';
			elseif (strpos(url()->current(), 'AuthorizedSignatory')) 
				$activetab = 'AuthorizedSignatory';
			elseif (strpos(url()->current(), 'Shareholders')) 
				$activetab = 'Shareholders';
			elseif (strpos(url()->current(), 'Directors')) 
				$activetab = 'Directors';
			elseif (strpos(url()->current(), 'BankData') && ($company->companytype_id == 2 || $company->companytype_id == 3 || $company->companytype_id == 4))
				$activetab = 'BankData';
			elseif (strpos(url()->current(), 'Business')) 
				$activetab = 'Business';
			elseif (old('newtab') != '') 
				$activetab = old('newtab');
			elseif ($company->basicinfo != 1) 
				$activetab = 'BasicInfo';
			elseif ($company->authsignatory != 1) 
				$activetab = 'AuthorizedSignatory';
			elseif ($company->shareholders != 1) 
				$activetab = 'Shareholders';
			elseif ($company->banks != 1 && ($company->companytype_id == 2 || $company->companytype_id == 3 || $company->companytype_id == 4))
				$activetab = 'BankData';
			elseif ($company->directors != 1) 
				$activetab = 'Directors';
			elseif ($company->business != 1) 
				$activetab = 'Business';

			//echo $activetab;
			//die;
		@endphp
	@else
	{{ Form::open(array('id' => 'frmManage', 'class' => 'form-horizontal ', 'files' => true)) }}
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
							@endif							
						</div>
					</div>
				@endif
			@endif
			@if (!$company->confirmed)
				<div class="row" >	<!-- row 10 -->
					<div class="col-sm-12" style="padding:0;margin:0 "> <!-- Column 1 -->
						<div class="alert alert-danger">
							<p class="bg-danger"><strong>Review and confirm</strong></p>
							@if (Gate::allows('co_cr'))
								<p class="bg-danger">Review company data and confirm.</p>
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
			@elseif (!$company->customer_signed && !$company->vendor_signed && !isset($confirmMessage))
				<div class="row" >	<!-- row 10 -->
					<div class="col-sm-12" style="padding-left: 0"> <!-- Column 1 -->
						<div class="alert alert-danger">
							<p class="bg-danger"><strong>Contract signature</strong></p>
							@if (Gate::allows('co_cr'))
								<p class="bg-danger">We have sent you the company contract by mail. Please sign it.</p>
							@else
								<p class="bg-danger">The user did not sign this company contract yet</p>
							@endif						
						</div>
					</div> <!-- Column 1 end -->
				</div> <!--row 10 end -->
			@endif
		@else
			<div class="bm-header">	<!-- row 10 -->
				<!-- <h2 class="bm-title">{{ $action_title }} Company</h2> -->
				<div class="tabs-holder">
					<div class="tab-container" id="divbasicinfo"> <!-- Column 1 -->
						<span id="tabbasicinfo" class="tab {{ $activetab == 'BasicInfo' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'BasicInfo' ? 'Basic Info' : '' }}
							<svg class="icon hidden" role="img" viewBox="0 0 22.8 16.6">
								<path fill="currentColor" d="M8.2,16.6A2,2,0,0,1,6.8,16L0,9.2,2.8,6.4l5.4,5.4L20,0l2.8,2.8L9.6,16A2,2,0,0,1,8.2,16.6Z"></path>
							</svg>
						</span>
						<i class="fa fa-chevron-right"></i>					
					</div>
					<div class="tab-container" id="divauthsignatory"> <!-- Column 1 -->
						<span id="tabauthsignatory" class="tab {{ $activetab == 'AuthorizedSignatory' ? 'tab--active' : 'tab--idle is-circle' }}">{{ $activetab == 'AuthorizedSignatory' ? 'Authorized Signatory' : '' }}
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
			<div class="white-box row">

			<div class="row">	<!-- row 10 -->
				<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
					<h4>Basic Info</h4>
				</div>
				@if (Gate::allows('co_ch') && isset($company) && isset($mode) && !$company->confirmed)
					<div class="col-sm-8 edit-icon-view d-ib"> <!-- Column 1 -->
						<a href="{{ url("/company/" . $company->id) . '/BasicInfo' }}"><span class="edit-icon--with-border"></span></a>
					</div>
				@endif
			</div>
			
				<div class="col-sm-6">
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- Company name -->  
						{{ Form::label('companyname', 'Name', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->companyname }}</p>
						@else
							{{ Form::text('companyname', Input::old('companyname'), array('id' => 'companyname', 'class' => 'form-control' , 'style' => 'width :100%')) }}
							@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
						@endif
					</div> 
				</div><!-- Company name -->
				<div class="col-sm-6">
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- address -->  
						{{ Form::label('address', 'Address', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))
							<p class='form-control-static col-sm-8'>{{ $company->address }}</p>
						@else
							{{ Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control' , 'style' => 'width :100%')) }}			
							@if ($errors->has('address')) <p class="bg-danger">{{ $errors->first('address') }}</p> @endif
						@endif
					</div> 
				</div><!-- address end -->
				<div class="col-sm-6">
					<div class="form-group select-input"> <!-- country -->  
						{{ Form::label('country_id', 'Country', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))
							@if ($company->city_id == 0)
								<p class='form-control-static'>{{ $company->country_name }}</p>
							@else
								<p class='form-control-static'>{{ $company->city->country->countryname }}</p>
							@endif					
						@else
						<div>
							<div class="col-sm-12">
								@if (isset($company))
									<?php $cntry = $company->city->country_id; ?>
								@else
									<?php $cntry = ''; ?>
								@endif	
								@php
									if(Input::old('country_id') || (isset($company) && $company->country_id))
									$country_id = Input::old('country_id') ? Input::old('country_id') : $company->country_id;
								@endphp
								<select name="country_id" class="form-control bm-select" id="select_country">
								@foreach ($countries as $country)
									@php
										$selected = false;
										if((isset($country_id) && $country_id == $country->id) 
											|| (!isset($country_id) && $initial_country_id == $country['id']))
											$selected = true;
									@endphp
									<option data-allowed="{{ $country['allowed'] }}" value="{{ $country['id'] }}" <?= $selected ? 'selected' : '' ?> >{{ $country['countryname'] }} ({{ $country['isocode'] }})</option>
								@endforeach
								</select>


							</div>
								@if (Input::old('country_id') == '0')
									{{ Form::text('country_name', old('country_name'), array('id' => 'country_name', 'class' => 'form-control', 'placeholder' => 'Country name')) }}
								@else
									{{ Form::text('country_name', old('country_name'), array('id' => 'country_name', 'class' => 'form-control hidden', 'placeholder' => 'Country name')) }}
								@endif
							@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
							@if ($errors->has('country_name')) <p class="bg-danger">{{ $errors->first('country_name') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- country -->	
				<div class="col-sm-6">
					<div class="form-group select-input "> <!-- city -->  
						{{ Form::label('city_id', 'City', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))
							@if ($company->city_id == 0)
								<p class='form-control-static'>{{ $company->city_name }}</p>
							@else
								<p class='form-control-static'>{{ $company->city->cityname }}</p>
							@endif					
						@else
						<div>
							<div class="col-sm-12">
								@if (isset($company))
									<?php $city = $company->city_id; ?>
								@else
									<?php $city = ''; ?>
								@endif
								{{ Form::select('city_id', $cities, Input::old('city_id', $city),array( 'id' => "select_city",'class' => '  select-input form-control ' ,'placeholder' => '','style' => 'width: 100%' )) }}

							</div>
							<div class="col-sm-12">
								@if (Input::old('country_id') == '0')
									{{ Form::text('city_name', old('city_name'), array('id' => 'city_name', 'class' => 'form-control', 'placeholder' => 'City name')) }}
								@else
									{{ Form::text('city_name', old('city_name'), array('id' => 'city_name', 'class' => 'form-control hidden', 'placeholder' => 'City name')) }}
								@endif
							</div>
							@if ($errors->has('city_id')) <p class="bg-danger">{{ $errors->first('city_id') }}</p> @endif
							@if ($errors->has('city_name')) <p class="bg-danger">{{ $errors->first('city_name') }}</p> @endif
							<input name="selectedcity" id="selectedcity" type="hidden" value="{{ old('city_id', $city) }}">
						</div>
						@endif
					</div> 
				</div><!-- city -->	
				<div class="col-sm-6">
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- phone -->  
						{{ Form::label('phone', 'Phone', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->phone }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}}  col-xs-12 input-container">
								{{ Form::text('phone', Input::old('phone'), array('id' => 'phone', 'class' => 'form-control phone', 'placeholder' => '')) }}
								@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- phone end --> 
				<div class="col-sm-6">
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : ''}}"> <!-- fax -->  
						{{ Form::label('fax', 'Fax', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->fax }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">				
							{{ Form::text('fax', Input::old('fax'), array('id' => 'fax', 'class' => 'form-control phone', 'placeholder' => '')) }}
							@if ($errors->has('fax')) <p class="bg-danger">{{ $errors->first('fax') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- fax end -->
				<div class="col-sm-6"> 
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : ''}}">  <!-- pobox -->  
						{{ Form::label('pobox', 'PO Box', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->pobox }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">				
							{{ Form::text('pobox', Input::old('pobox'), array('id' => 'pobox', 'class' => 'form-control')) }}			
							@if ($errors->has('pobox')) <p class="bg-danger">{{ $errors->first('pobox') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- pobox end -->

				@if(isset($mode) && Gate::allows('cr_ap'))
					@if($company->isCustomer())					
						<div class="col-sm-12"> 
							<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : ''}}">  <!-- sapnumber -->  
								{{ Form::label('sapnumber', 'SAP Customer Number', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
								<p class='form-control-static col-sm-9'>{{ $company->sapnumber }}</p>
							</div> 
						</div><!-- sapnumber end -->
					@endif
					@if($company->isVendor())					
					<div class="col-sm-12"> 
						<div class="form-group {{ isset($mode) ? 'form-group--view'  : ''}}">  <!-- sapvendornumber -->  
							{{ Form::label('sapvendornumber', 'SAP Vendor Number', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
							<p class='form-control-static col-sm-9'>{{ $company->sapvendornumber }}</p>
						</div> 
					</div><!-- sapvendornumber end -->
					@endif
				@endif
				<div class="col-sm-6"> 
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- email -->  
						{{ Form::label('email', 'Email', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->email }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
							{{ Form::email('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'Placeholder' => '')) }}			
							@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- email end -->
				<div class="col-sm-6">
					<div class="form-group text-input {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- companytype -->  
						{{ Form::label('companytype_id', 'Registeration Type', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4' : 'control-label bm-label col-sm-12')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->companytype->name }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
							<div id="comp_type_wrapper">
								<div class="radio" id="cb_buyer">
									<label class="checkbox">
										@if((old('companytype_id') == '' && (!isset($company)))
										|| (!empty(old('companytype_id')[0]) && !empty(old('companytype_id')[1]))
										|| (isset($company) && $company->companytype_id == 3)
										|| (!empty(old('companytype_id')[0]) && old('companytype_id')[0] == 1)
										|| (isset($company) && $company->companytype_id == 1))
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_buyer" value="1" checked onclick="CompanyTypeSelect(this);">
										@else
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_buyer" value="1" onclick="CompanyTypeSelect(this);">
										@endif
										
										<span class="checkmark"></span>
										<span class="bm-sublabel">Buyer</span> 
									</label>
									<small>Choose this option if the company is a buyer</small>
								</div>
								<div class="radio" id="cb_supplier">
									<label class="checkbox">
										@if((!empty(old('companytype_id')[0]) && old('companytype_id')[0] == 2) 
										|| (!empty(old('companytype_id')[0]) && !empty(old('companytype_id')[1]))
										|| (isset($company) && $company->companytype_id == 3)
										|| (isset($company) && $company->companytype_id == 2))
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_supplier" value="2" checked onclick="CompanyTypeSelect(this);">
										@else
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_supplier" value="2" onclick="CompanyTypeSelect(this);">
										@endif
										<span class="checkmark"></span>
										<span class="bm-sublabel">Supplier</span>
									</label>
									<small>Choose this option if the company is a supplier</small>
								</div>
								<div class="radio" id="cb_forwarder" style="display:{{ isset($showFF) ? 'initial' : 'none' }}">
									<label class="checkbox">
										@if((!empty(old('companytype_id')[0]) && old('companytype_id')[0] == 4)
										|| (isset($company) && $company->companytype_id == 4))
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_forwarder" value="4" checked onclick="CompanyTypeSelect(this);">
										@else
											<input class="bm-checkbox" type="checkbox" name="companytype_id[]" id="companytype_forwarder" value="4" onclick="CompanyTypeSelect(this);">
										@endif
										<span class="checkmark"></span>
										<span class="bm-sublabel">Freight Forwarder</span>
									</label>
									<small>Choose this option if the company is a freight forwarder</small>
								</div>
								@if ($errors->has('companytype_id')) <p class="bg-danger">{{ $errors->first('companytype_id') }}</p> @endif
							</div>
							<div id="supplier_only" style="display: none;">
								<p style="margin-bottom: 2px;font-weight: bold; colof : gray">Supplier</p>
								<small>Other types are only available in <b>Saudi Arabia</b> & <b>United Arab Emirates</b></small>
							</div>
						</div>
						@endif
					</div> 
				</div>
				
				<div class="col-sm-6">
					<div class="form-group  text-input {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- trade license no -->  
						{{ Form::label('license', 'Trade License No.', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))
							<p class='form-control-static col-sm-8'>{{ $company->license }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
							{{ Form::text('license', old('license'), array('id' => 'license', 'class' => 'form-control')) }}			
							@if ($errors->has('license')) <p class="bg-danger">{{ $errors->first('license') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- trade license no end -->
				<div class="col-sm-6">
					<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- incorporated -->  
						{{ Form::label('incorporated', 'Date of Establishing the Company', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->incorporated }}</p>
						@else
							<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12">
								<div class="input-container flex-container">
									{{ Form::text('incorporated', Input::old('incorporated'), array('id' => 'incorporated', 'class' => 'input-with-icon form-control')) }}			
									<span class="cal-icon" alt="cal icon"></span>
								</div>								
							</div>
							<div class="col-lg-offset-3 col-lg-6 col-sm-8 col-xs-12">
								@if ($errors->has('incorporated')) <p class="bg-danger">{{ $errors->first('incorporated') }}</p> @endif
							</div>
						@endif
					</div> <!-- incorporated end -->
				</div>
				
	
				
				<div class="col-sm-6">
					<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- tax -->  
						{{ Form::label('tax', 'Tax Certificate No.', array('class' =>isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))
							<p class='form-control-static col-sm-8'>{{ $company->tax }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
							{{ Form::text('tax', old('tax'), array('id' => 'tax', 'class' => 'form-control')) }}			
							@if ($errors->has('tax')) <p class="bg-danger">{{ $errors->first('tax') }}</p> @endif
						</div>
						@endif
					</div> 
				</div><!-- tax end -->

				<div class="col-sm-6">
					<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- tax -->  
						{{ Form::label('tx', 'Tax Certificate Attachment', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}}col-xs-12 input-container">
						@if (old('taxfile'))							
							<input name="taxFile" id="tax_file" type="hidden" value="{{ old('taxfile') }}">
							<input name="taxAttachId" id="tax_attach_id" type="hidden" value="{{ old('tax_attach_id') }}">								
						@else
							@if (isset($taxAttachment))
								@if (isset($mode))
									<a href="/{{ $taxAttachment->path }}" download="{{ $taxAttachment->path }}">{{ $taxAttachment->filename }}</a>
								@endif
								<input name="taxFile" id="tax_file" type="hidden" value="{{ $taxAttachment->filename }}">
								<input name="taxAttachId" id="tax_attach_id" type="hidden" value="{{ $taxAttachment->id }}">
							@else
								<input name="taxFile" id="tax_file" type="hidden">
								<input name="taxAttachId" id="tax_attach_id" type="hidden">									
							@endif
						@endif						
						@if (!isset($mode))
							<div class="flex-container">
								@if (isset($taxAttachment))
									<span id="tax_file_name" name="taxFileName" onclick="uploadTaxFile(this);return false;" class="form-control">{{ $taxAttachment->filename }}</span>
								@else
									<span id="tax_file_name" name="taxFileName" onclick="uploadTaxFile(this);return false;" class="form-control">{{ old('taxFile', 'No file attached') }}</span>
								@endif	
								<a href="#" class="attach-icon" onclick="uploadTaxFile(this);return false;" id="tax_lnk_attach" role="button" alt="Select file" title="Select file"></a>								
							</div>
							<div><small class='form-control-static'>Use only PDF, JPEG, JPG, PNG files. Maximum file size is 2M.</small></div>
							<progress id="tax_progress_bar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<input type="file" name="taxAttach" id="tax_attach" style="display:none;">									
						@endif
						@if ($errors->has('taxFile')) <p class="bg-danger">{{ $errors->first('taxFile') }}</p> @endif
						</div>
					</div> 
					
				</div><!-- tax end -->
				<div class="col-sm-6">
					<div class="form-group  text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- license -->  
						{{ Form::label('tradelic', 'Trade License Attachment', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">						
						@if (old('tradefile'))							
							<input name="tradefile" id="tradefile" type="hidden" value="{{ old('tradefile') }}">
							<input name="tradeattachid" id="tradeattachid" type="hidden" value="{{ old('tradeattachid') }}">								
						@else
							@if (isset($tradeattachment))
								@if (isset($mode))
									<a href="/{{ $tradeattachment->path }}" download="{{ $tradeattachment->path }}">{{ $tradeattachment->filename }}</a>									
								@endif
								<input name="tradefile" id="tradefile" type="hidden" value="{{ $tradeattachment->filename }}">
								<input name="tradeattachid" id="tradeattachid" type="hidden" value="{{ $tradeattachment->id }}">
							@else
								<input name="tradefile" id="tradefile" type="hidden">
								<input name="tradeattachid" id="tradeattachid" type="hidden">									
							@endif
						@endif
						@if (!isset($mode))
							<div class="flex-container">
								@if (isset($assocattachment))
									<span id="tradefilename" name="tradefilename" onclick="Uploadtradefile(this);return false;" class="form-control">{{ $tradeattachment->filename }}</span>
								@else
									<span id="tradefilename" name="tradefilename" onclick="Uploadtradefile(this);return false;" class="form-control">{{ old('tradefile', 'No file attached') }}</span>
								@endif								
								<a href="#" class="attach-icon" onclick="Uploadtradefile(this);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
							</div>
							<div><small class='form-control-static'>Use only PDF, JPEG, JPG, PNG files. Maximum file size is 2M.</small></div>
							<progress id="progressBar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<input type="file" name="tradeattach" id="tradeattach" class="tradeattach" style="display:none;">							
						@endif
						@if ($errors->has('tradefile')) <p class="bg-danger">{{ $errors->first('tradefile') }}</p> @endif
						</div>
					</div>
				</div><!-- end col 1 -->
				<div class="col-sm-6">
					<div class="form-group  text-input  {{ isset($mode) ? 'form-group--view'  : ''}}"> <!-- website -->  
						{{ Form::label('website', 'Company Website', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						@if (isset($mode))	
							<p class='form-control-static col-sm-8'>{{ $company->website }}</p>
						@else
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
							{{ Form::text('website', Input::old('website'), array('id' => 'website', 'class' => 'form-control')) }}			
							@if ($errors->has('website')) <p class="bg-danger">{{ $errors->first('website') }}</p> @endif
						</div>
						@endif
					</div> <!-- website end -->
				</div>
				<div class="col-sm-6">
					<div class="form-group text-input  {{ isset($mode) ? ''  : 'required'}}" > <!-- articles of assoc -->  
						{{ Form::label('assoclic', 'Articles Of Assoc.', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
						@if (old('assocfile'))							
							<input name="assocfile" id="assocfile" type="hidden" value="{{ old('assocfile') }}">
							<input name="assocattachid" id="assocattachid" type="hidden" value="{{ old('assocattachid') }}">								
						@else
							@if (isset($assocattachment))
								@if (isset($mode))
									<a href="/{{ $assocattachment->path }}" download="{{ $assocattachment->path }}">{{ $assocattachment->filename }}</a>								
								@endif
								<input name="assocfile" id="assocfile" type="hidden" value="{{ $assocattachment->filename }}">
								<input name="assocattachid" id="assocattachid" type="hidden" value="{{ $assocattachment->id }}">									
							@else								
								<input name="assocfile" id="assocfile" type="hidden">
								<input name="assocattachid" id="assocattachid" type="hidden">									
							@endif
						@endif
						@if (!isset($mode))
							<div class="flex-container">
								@if (isset($assocattachment))
									<span id="assocfilename" name="assocfilename" onclick="Uploadassocfile(this);return false;" class="form-control">{{ $assocattachment->filename }}</span>
								@else
									<span id="assocfilename" name="assocfilename" onclick="Uploadassocfile(this);return false;" class="form-control">{{ old('assocfile', 'No file attached') }}</span>
								@endif								
								<a href="#" class="attach-icon" onclick="Uploadassocfile(this);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>			
							</div>
							<div><small class='form-control-static'>Use only PDF, JPEG, JPG, PNG files. Maximum file size is 6M.</small></div>
							<progress id="AssocprogressBar" value="0" max="100" style="width:200px;" class="hidden"></progress>
							<input type="file" name="assocattach" id="assocattach" class="assocattach" style="display:none;">
						@endif
						@if ($errors->has('assocfile')) <p class="bg-danger">{{ $errors->first('assocfile') }}</p> @endif
						</div>
					</div><!-- articles of assoc end -->
				</div>

				<div class="col-sm-6">
					<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- employees -->  
						{{ Form::label('employees', 'No. Of Employees', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
						@if (isset($mode))	
							<p class='form-control-static'>{{ $company->employeenumber->name }}</p>
						@else

							{{ Form::select('employees', $employees, Input::old('employees'),array('id' => 'employees','class' => '  select-input form-control ' ,'placeholder' => '','style' => 'width: 100%' )) }}
							@if ($errors->has('employees')) <p class="bg-danger">{{ $errors->first('employees') }}</p> @endif
						@endif
						</div>
					</div> <!-- employees end -->
				</div>
				<div class="col-sm-6">
					<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- operating -->  
						{{ Form::label('operating', 'Industries Operating In', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
						<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
						@if (isset($mode))
							@foreach ($company->industries as $industry)
							<p class='form-control-static'>{{ $industry->name }}</p>
							@endforeach
						@else
							{{ Form::select('industries[]', $industries, Input::old('industries'),array('id' => 'industries', 'class' => 'form-control','placeholder' => '','multiple'))}}									
							@if ($errors->has('industries')) <p class="bg-danger">{{ $errors->first('industries') }}</p> @endif
						@endif
						</div>			
					</div> <!-- operating end --> 
				</div>
				@if (isset($mode) && Gate::any(['cr_ap', 'pt_as']))
					@if (isset($buyerContract))
						@if ($buyerContract->path != '/')
							<div class="col-sm-12">
								<div class="form-group">
									<label class="bm-label col-lg-6 col-sm-9 col-xs-12">Buyer Contract</label>
									<a href="/{{ $buyerContract->path }}" download="{{ $buyerContract->path }}">{{ $buyerContract->filename }}</a>
								</div>						
							</div>
						@endif
					@endif
					@if (isset($supplierContract))
						@if ($supplierContract->path != '/')
							<div class="col-sm-12">
								<div class="form-group">
									<label class="bm-label col-lg-6 col-sm-9 col-xs-12">Supplier Contract</label>
									<a href="/{{ $supplierContract->path }}" download="{{ $supplierContract->path }}">{{ $supplierContract->filename }}</a>
								</div>
							</div>
						@endif
					@endif
				@endif
			</div>					<!-- end col 4 -->
		 <!-- end tab -->
		</div>
		@if (isset($mode))
			@php $activetab = 'AuthorizedSignatory'; @endphp
		@endif
		<div id="authorizedsignatory" class="row {{$activetab == 'AuthorizedSignatory' ? '' : 'hidden' }}">
			<div class="white-box col-xs-12">
				<div class="row">	<!-- row 9 -->
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Authorized Signatory</h4>					
					</div>
					@if (Gate::allows('co_ch') && isset($company))
						<div class="col-sm-6 edit-icon-view d-ib"> <!-- Column 1 -->
							@if (isset($mode) && !$company->confirmed)
								<a href="{{ url("/company/" . $company->id) . '/AuthorizedSignatory' }}"><span class="edit-icon--with-border"></span></a>
							@endif
						</div>
					@endif
				</div>
				<div>	<!-- row 10 -->
					<div class="col-sm-6">  <!-- column 1 -->
						<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory name -->  
							{{ Form::label('signatoryname', 'Name', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->signatoryname }}</p>
							@else	
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('signatoryname', Input::old('signatoryname'), array('id' => 'signatoryname', 'class' => 'form-control')) }}								
									@if ($errors->has('signatoryname')) <p class="bg-danger">{{ $errors->first('signatoryname') }}</p> @endif
								</div>
							@endif
						</div> <!-- signatory name --> 										
					</div>					<!-- end col 1 -->
					<div class="col-sm-6">  <!-- column 1 -->
						<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory designation -->  
							{{ Form::label('signatorydesignation', 'Designation', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->signatorydesignation }}</p>
							@else	
								<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('signatorydesignation', Input::old('signatorydesignation'), array('id' => 'signatorydesignation', 'class' => 'form-control')) }}								
									@if ($errors->has('signatorydesignation')) <p class="bg-danger">{{ $errors->first('signatorydesignation') }}</p> @endif
								</div>
							@endif
						</div> <!-- signatory designation --> 										
					</div>					<!-- end col 1 -->
					<div class="col-sm-6">  <!-- column 2 -->
						<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory email -->  
							{{ Form::label('signatoryemail', 'Email', array('class' => isset($mode) ? 'control-label bm-label col-sm-2':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->signatoryemail }}</p>
							@else	
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('signatoryemail', Input::old('signatoryemail'), array('id' => 'signatoryemail', 'class' => 'form-control')) }}
									<small>Contracts will be sent to this email</small>
									@if ($errors->has('signatoryemail')) <p class="bg-danger">{{ $errors->first('signatoryemail') }}</p> @endif
								</div>
							@endif
						</div> <!-- signatory email --> 										
					</div>					<!-- end col 2 -->
					<div class="col-sm-6">  <!-- column 3 -->
						<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory phone -->  
							{{ Form::label('signatoryphone', 'Phone', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->signatoryphone }}</p>
							@else	
								<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('signatoryphone', Input::old('signatoryphone'), array('id' => 'signatoryphone', 'class' => 'form-control phone')) }}								
									@if ($errors->has('signatoryphone')) <p class="bg-danger">{{ $errors->first('signatoryphone') }}</p> @endif
								</div>
							@endif
						</div> <!-- signatory phone --> 										
					</div>					<!-- end col 3 -->
					<div class="col-sm-6">  <!-- column 4 -->
						@if (isset($mode))
							@if (isset($signidattachment))
								<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory id -->  
									{{ Form::label('signatoryid', 'ID', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
									<a href="/{{ $signidattachment->path }}" download="{{ $signidattachment->path }}">{{ $signidattachment->filename }}</a>								
								</div> <!-- signatory id --> 							
							@endif
							@if (isset($signpptattachment))
								<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory passport -->  
									{{ Form::label('signatoryppt', 'Passport', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
									<a href="/{{ $signpptattachment->path }}" download="{{ $signpptattachment->path }}">{{ $signpptattachment->filename }}</a>								
								</div> <!-- signatory passport --> 							
							@endif
							@if (isset($signvisaattachment))
								<div class="form-group text-input  {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- signatory visa -->  
									{{ Form::label('signatoryvisa', 'Visa', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
									<a href="/{{ $signvisaattachment->path }}" download="{{ $signvisaattachment->path }}">{{ $signvisaattachment->filename }}</a>								
								</div> <!-- signatory visa --> 							
							@endif
						@else
							<div class="form-group text-input  {{ isset($mode) ? ''  : 'required'}}"> <!-- signatory attachment -->  
								{{ Form::label('signattachment_id', 'Attachments', array('class' => 'tb-label  ')) }}
								<div class="col-lg-12 col-sm-12 col-xs-12" style="position: relative;">
									<?php $i = 0 ; 
									$j = 0; ?>
									<table class="attach-table form-table table uploadtable">
									<tr>
										<td rowspan="3" style="vertical-align:top">
											<input type="file" name="attach" id="attach" class="attach" style="display:none;">
											<a href="#" class="attach-icon" onclick="Attachment(this,28);return false;" id="lnkattach" role="button" alt="Select file" title="Select file"></a>
										</td>
										<td>
											<div class="radio">
												<label class="tb-label">
													<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="28">
													<span class="bm-rd-checkmark"></span>
													<span class="bm-sublabel">ID</span>
												</label>
											</div>
										</td>
										<td class="td-uploaded">
											@if (old('signidfile'))
												@php $j = $j + 1; @endphp
												<a href="#" onclick="DeleteAttachment(this, 28);return false;"><span class="cancel-icon" title="Delete"></span></a>
												<span>{{ $signidattachment->filename }}</span>
												<input name="signidfile" id="signidfile" type="hidden" value="{{ old('signidfile') }}">
												<input name="signidattachid" id="signidattachid" type="hidden" value="{{ old('signidattachid') }}">
											@else
												@if (isset($signidattachment)) <!-- ID attachment -->
													@php $j = $j + 1; @endphp
													<a href="#" onclick="DeleteAttachment(this, 28);return false;"><span class="cancel-icon" title="Delete"></span></a>
													<span>{{ $signidattachment->filename }}</span>
													<input name="signidfile" id="signidfile" type="hidden" value="{{ $signidattachment->filename }}">
													<input name="signidattachid" id="signidattachid" type="hidden" value="">
												@else
													<a href="#" onclick="DeleteAttachment(this, 28);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
													<span>No file selected</span>																		
													<input name="signidfile" id="signidfile" type="hidden" value="">
													<input name="signidattachid" id="signidattachid" type="hidden" value="">
												@endif
											@endif																			
											<progress id="progressID" value="0" max="100" style="width:200px;" class="hidden"></progress>																	
										</td>
									</tr>
									<tr>
										<td>
											<div class="radio">
											<label class="tb-label">
												<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="39">
												<span class="bm-rd-checkmark"></span>
												<span class="bm-sublabel">Passport</span>
											</label>
											</div>
										</td>															
										<td class="td-uploaded">
											@if (isset($signpptattachment)) <!-- Passport attachment -->
												@php $j = $j + 1; @endphp
												<a href="#" onclick="DeleteAttachment(this, 39);return false;"><span class="cancel-icon" title="Delete"></span></a>
												<span>{{ $signpptattachment->filename }}</span>
												<input name="signpptfile" id="signpptfile" type="hidden" value="{{ $signpptattachment->filename }}">
												<input name="signpptattachid" id="signpptattachid" type="hidden" value="">
											@else
												<a href="#" onclick="DeleteAttachment(this, 39);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
												<span>No file selected</span>
												<input name="signpptfile" id="signpptfile" type="hidden" value="">
												<input name="signpptattachid" id="signpptattachid" type="hidden" value="">
											@endif
											<progress id="progressPassport" value="0" max="100" style="width:200px;" class="hidden"></progress>
										</td>
									</tr>
									<tr>
										<td>
											<div class="radio">
											<label class="tb-label">
												<input class="bm-radio" name="attachmenttype" id="attachmenttype" type="radio" value="29">
												<span class="bm-rd-checkmark"></span>
												<span class="bm-sublabel">Visa</span>
											</label>
											</div>
										</td>
										<td class="td-uploaded">
											@if (isset($signvisaattachment)) <!-- Visa attachment -->
												<a href="#" onclick="DeleteAttachment(this, 29);return false;"><span class="cancel-icon" title="Delete"></span></a>
												<span>{{ $signvisaattachment->filename }}</span>
												<input name="signvisafile" id="signvisafile" type="hidden" value="{{ $signvisaattachment->filename }}">
												<input name="signvisaattachid" id="signvisaattachid" type="hidden" value="">
											@else
												<a href="#" onclick="DeleteAttachment(this, 29);return false;"><span class="cancel-icon hidden" title="Delete"></span></a>
												<span>No file selected</span>
												<input name="signvisafile" id="signvisafile" type="hidden" value="">
												<input name="signvisaattachid" id="signvisaattachid" type="hidden" value="">
											@endif
											<progress id="progressVisa" value="0" max="100" style="width:200px;" class="hidden"></progress>
										</td>																
									</tr>
									</table>
									<input type="hidden" name="signattach" id="signattach" value="{{ $j }}">
									@if ($errors->has('signattach')) <p class="bg-danger">{{ $errors->first('signattach') }}</p> @endif
								</div>
							</div>
						@endif
						
					</div>					<!-- end col 4 -->
				</div>	
			</div> <!-- end authorized signatory tab -->
		</div>
		@if (isset($mode))
		@if ($company->companytype_id == 1)
			@php $activetab = ''; @endphp
		@else
			@php $activetab = 'BankData'; @endphp
		@endif
		@endif			
		<div id="bankdata" class=" col-md-12 row {{$activetab == 'BankData' ? '' : 'hidden' }}">
			<div class="white-box col-xs-12">

				<div class="row">	<!-- row 9 -->
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Bank Details</h4>
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
						<div class="form-group  col-sm-6 {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- account name -->  
							{{ Form::label('accountname', 'Account Name', array('class' =>  'control-label bm-label col-sm-4' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ isset($company) ? $company->companyname : '' }}</p>
							@else	
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									<p class='form-control-static'>{{ isset($company) ? $company->companyname : '' }}</p>
									
									{{ Form::hidden('accountname',isset($company) ? $company->companyname : '' , array('id' => 'accountname')) }}
									@if ($errors->has('accountname')) <p class="bg-danger">{{ $errors->first('accountname') }}</p> @endif
								</div>
							@endif
						</div> <!-- Bank name -->
						<div class="form-group  col-sm-6  {{ isset($mode) ? ' form-group--view'  : ' text-input required'}}"> <!-- bank name -->  
							{{ Form::label('bankname', 'Bank Name', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->bankname }}</p>
							@else	
								<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('bankname', Input::old('bankname'), array('id' => 'bankname', 'class' => 'form-control')) }}								
									@if ($errors->has('bankname')) <p class="bg-danger">{{ $errors->first('bankname') }}</p> @endif
								</div>
							@endif
						</div> <!-- bank name --> 
						<div class="form-group  col-sm-6  {{ isset($mode) ? ' form-group--view'  : 'text-input required'}}"> <!-- account number -->  
							{{ Form::label('accountnumber', 'Account Number', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->accountnumber }}</p>
							@else
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('accountnumber', Input::old('accountnumber'), array('id' => 'accountnumber', 'class' => 'form-control')) }}								
									@if ($errors->has('accountnumber')) <p class="bg-danger">{{ $errors->first('accountnumber') }}</p> @endif
								</div>
							@endif
						</div> <!-- account number -->  
						<div class="form-group  col-sm-6  {{ isset($mode) ? '  form-group--view'  : 'text-input required'}}"> <!-- iban -->  
							{{ Form::label('iban', 'IBAN', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->iban }}</p>
							@else		
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('iban', Input::old('iban'), array('id' => 'iban', 'class' => 'form-control')) }}								
									@if ($errors->has('iban')) <p class="bg-danger">{{ $errors->first('iban') }}</p> @endif
								</div>
							@endif
						</div> <!-- iban -->  
						<div class="form-group col-sm-6  {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- routing code -->  
							{{ Form::label('routingcode', 'Routing Code', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->routingcode }}</p>
							@else
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('routingcode', Input::old('routingcode'), array('id' => 'routingcode', 'class' => 'form-control')) }}								
									@if ($errors->has('routingcode')) <p class="bg-danger">{{ $errors->first('routingcode') }}</p> @endif
								</div>
							@endif
						</div> <!-- routing code -->  
						<div class="form-group col-sm-6  {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- swift -->  
							{{ Form::label('swift', 'SWIFT Code', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->swift }}</p>
							@else
								<div class=" {{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::text('swift', Input::old('swift'), array('id' => 'swift', 'class' => 'form-control')) }}								
									@if ($errors->has('swift')) <p class="bg-danger">{{ $errors->first('swift') }}</p> @endif
								</div>
							@endif
						</div> <!-- swift -->  
						<div class="form-group col-sm-6  {{ isset($mode) ? 'form-group--view' : 'select-input required'}}"> <!-- currency_id -->  
							{{ Form::label('currency_id', 'Currency', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
							@if (isset($mode))	
								<p class='form-control-static'>{{ $company->currency->name }}</p>
							@else
								<div class="{{isset($mode) ? 'col-lg-8 col-sm-8 ' : 'col-lg-12 col-sm-12'}} col-xs-12 input-container">
									{{ Form::select('currency_id', $currencies, Input::old('currency_id'),array('id' => 'currency_id', 'class' => 'form-control bm-select'))}}		
									@if ($errors->has('currency_id')) <p class="bg-danger">{{ $errors->first('currency_id') }}</p> @endif
								</div>
							@endif
						</div> <!-- currency_id -->  
					</div>					<!-- end col 3 -->
				</div>
			</div>				<!-- end row 10 -->
		</div> <!-- end banks tab -->
		
		@if (isset($mode))
			@if ($company->companytype_id == 2)
				@php $activetab = ''; @endphp
			@else
				@php $activetab = 'Shareholders'; @endphp
			@endif			
		@endif
		<div id="shareholders" class="row {{ $activetab == 'Shareholders' ? '' : 'hidden' }}">
		 <div class="white-box col-xs-12">
				<div class="row">	<!-- row 11 -->
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Shareholders</h4>
					</div>
					
						@if (Gate::allows('co_ch') && isset($company) && isset($mode) && !$company->confirmed)
						<div class="col-sm-8 edit-icon-view d-ib"> <!-- Column 1 -->										
							<a href="{{ url("/company/" . $company->id) . '/Shareholders' }}"><span class="edit-icon--with-border"></span></a>
						</div>
						@endif
						
						@if (Gate::allows('cr_ap') && isset($company) && isset($mode) && $company->confirmed)
						<div class="col-sm-8 edit-icon-view d-ib"> <!-- Column 1 -->										
							<a href="{{ url("/company/edit/" . $company->id) . '/Shareholders' }}"><span class="edit-icon--with-border"></span></a>
						</div>
						@endif

				</div>			
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
										<input type="checkbox" class="bm-checkbox" name="cbsame" id ="cbsame" checked="{{old('sameowner')}}">
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
					<table id="ownertable" class=" row table table-striped table-bordered table-hover table-tight dataTable">
						<thead>
							<tr>
								@if (isset($mode))
									{{-- <th></th> --}}
									<th class="col-md-2">Name</th>
									<th class="col-md-3">Email</th>
									<th class="col-md-2">Mobile</th>
									<th class="col-md-1">Share %</th>
									<th class="col-md-2">Attachment</th>
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
										{{-- <td style="vertical-align:top">
											<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct" title="Delete owner"></a>&nbsp;
											{{ Form::hidden('ownerid[]', old('ownerid')[$i], array('id' => 'owner_id')) }}
											{{ Form::hidden('ownerdel[]', old('ownerdel')[$i], array('id' => 'ownerdel', 'class' => 'form-control')) }}
										</td> --}}
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
											{{-- <td style="text-align: center">
											@if ($owner->audits->where('event', 'updated')->count() > 0)
												<a onclick="toggleCOHistory(this)">
													<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
												</a>
											@endif
											</td>							 --}}
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
																	<label class="tb-label">
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

										<tr class="co-history-wrapper" style="display: none">
											<td colspan="6">
												<table class="table table-striped table-bordered co-history-table" style="text-size: 11px !important">	
													<thead style="background: #fcf8e3;">
														<tr>
															<th>On</th>
															<th>By</th>
															<th>Name</th>
															<th>Email</th>
															<th>Mobile</th>
															<th>Share %</th>
															<th>Attachment</th>						
														</tr>		
													</thead>
													<tbody>
														@foreach($owner->audits as $audit)													
															<tr>
																<td> {{ $audit->created_at }} </td>
																<td> {{ $audit->user->name }} </td>															
																<td>
																	@if (array_key_exists('ownername', $audit->new_values))
																		{{ $audit->new_values['ownername'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('owneremail', $audit->new_values))
																		{{ $audit->new_values['owneremail'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('ownerphone', $audit->new_values))
																		{{ $audit->new_values['ownerphone'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('ownershare', $audit->new_values))
																		{{ $audit->new_values['ownershare'] }}
																	@else
																		No change
																	@endif
																</td>
																<td align="right">
																	@if (array_key_exists('path', $audit->new_values))
																	<a href="/{{ $audit->new_values['path'] }}" download="{{ $audit->new_values['path'] }}">{{ $audit->new_values['path'] }}</a>
																	@else
																		No change
																	@endif
																</td>
															</tr>
														@endforeach	
													</tbody>
												</table>
											</td>
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
						<table id="listtable" class="table table-striped table-bordered table-hover table-tight dataTable">
							<thead>
								<tr>
									@if (isset($mode))
										{{-- <th></th> --}}
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
											{{-- <td>
												<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct" title="Delete beneficial"></a>&nbsp;
												{{ Form::hidden('beneficialid[]', old('beneficialid')[$i], array('id' => 'beneficial_id')) }}
												{{ Form::hidden('beneficialdel[]', old('beneficialdel')[$i], array('id' => 'beneficialdel', 'class' => 'form-control')) }}
											</td> --}}
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
												{{-- <td style="text-align: center">
											@if ($beneficial->audits->where('event', 'updated')->count() > 0)
												<a onclick="toggleBOHistory(this)">
													<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
												</a>
											@endif
											</td>								 --}}
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
											<tr class="bo-history-wrapper" style="display: none">
											<td colspan="6">
												<table class="table table-striped table-bordered bo-history-table" style="text-size: 11px !important">	
													<thead style="background: #fcf8e3;">
														<tr>
															<th>On</th>
															<th>By</th>
															<th>Name</th>
															<th>Email</th>
															<th>Mobile</th>
															<th>Share %</th>
															<th>Attachment</th>						
														</tr>		
													</thead>
													<tbody>
														@foreach($beneficial->audits as $audit)													
															<tr>
																<td> {{ $audit->created_at }} </td>
																<td> {{ $audit->user->name }} </td>															
																<td>
																	@if (array_key_exists('beneficialname', $audit->new_values))
																		{{ $audit->new_values['beneficialname'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('beneficialemail', $audit->new_values))
																		{{ $audit->new_values['beneficialemail'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('beneficialphone', $audit->new_values))
																		{{ $audit->new_values['beneficialphone'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('beneficialshare', $audit->new_values))
																		{{ $audit->new_values['beneficialshare'] }}
																	@else
																		No change
																	@endif
																</td>
																<td align="right">
																	@if (array_key_exists('path', $audit->new_values))
																		<a href="/{{ $audit->new_values['path'] }}" download="{{ $audit->new_values['path'] }}">{{ $audit->new_values['path'] }}</a>
																	@else
																		No change
																	@endif
																</td>
															</tr>
														@endforeach	
													</tbody>
												</table>
											</td>
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
		 </div>

		</div> <!-- end owner tab -->
		
		@if (isset($mode))
			@if ($company->companytype_id == 2)
				@php $activetab = ''; @endphp
			@else
				@php $activetab = 'Directors'; @endphp
			@endif			
		@endif
		<div id="directors" class="row {{$activetab == 'Directors' ? '' : 'hidden' }}">
			<div class="white-box col-xs-12">
				<div class="row">	<!-- row 7 -->
					<div class="col-md-3 col-sm-4 d-ib section-title"> <!-- Column 1 -->
						<h4>Directors</h4>
					</div>
					<div class="edit-icon-view col-sm-3 d-ib"> <!-- Column 1 -->
						@if (Gate::allows('co_ch') && isset($company))
							@if (isset($mode) && !$company->confirmed)
								<a href="{{ url("/company/" . $company->id) . '/Directors' }}"><span class="edit-icon--with-border"></span></a>
							@endif
						@endif
						@if (Gate::allows('cr_ap') && isset($company) && isset($mode) && $company->confirmed)
							<a href="{{ url("/company/edit/" . $company->id) . '/Directors' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					</div>			
				</div>
				<div class="row">	<!-- row 8 --> 
					<div class=" col-sm-12 table-container"> <!-- Column 1 -->					
						<?php $directorcount = 0; ?>
						<table id="directortable" class="row table table-striped table-bordered table-hover table-tight dataTable">
							<thead>
								<tr>
									@if (isset($mode))
									{{-- <th></th> --}}
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
											{{-- <td style="vertical-align: top">
												<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct" title="Delete director"></a>&nbsp;
												{{ Form::hidden('directorid[]', old('directorid')[$i], array('id' => 'director_id')) }}
												{{ Form::hidden('directordel[]', old('directordel')[$i], array('id' => 'directordel', 'class' => 'form-control')) }}
											</td> --}}
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
												{{-- <td style="text-align: center">
												@if ($director->audits->where('event', 'updated')->count() > 0)
													<a onclick="toggleDOHistory(this)">
														<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
													</a>
												@endif
												</td>					 --}}
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
											<tr class="do-history-wrapper" style="display: none">
												<td colspan="6">
													<table class="table table-striped table-bordered do-history-table" style="text-size: 11px !important">	
														<thead style="background: #fcf8e3;">
															<tr>
																<th>On</th>
																<th>By</th>
																<th>Name</th>
																<th>Title</th>
																<th>Email</th>
																<th>Mobile</th>
																<th>Attachment</th>						
															</tr>		
														</thead>
														<tbody>
															@foreach($director->audits as $audit)													
																<tr>
																	<td> {{ $audit->created_at }} </td>
																	<td> {{ $audit->user->name }} </td>															
																	<td>
																		@if (array_key_exists('directorname', $audit->new_values))
																			{{ $audit->new_values['directorname'] }}
																		@else
																			No change
																		@endif
																	</td>
																	<td>
																		@if (array_key_exists('directortitle', $audit->new_values))
																			{{ $audit->new_values['directortitle'] }}
																		@else
																			No change
																		@endif
																	</td>
																	<td>
																		@if (array_key_exists('directoremail', $audit->new_values))
																			{{ $audit->new_values['directoremail'] }}
																		@else
																			No change
																		@endif
																	</td>
																	<td>
																		@if (array_key_exists('directorphone', $audit->new_values))
																			{{ $audit->new_values['directorphone'] }}
																		@else
																			No change
																		@endif
																	</td>
																	<td align="right">
																		@if (array_key_exists('path', $audit->new_values))
																			<a href="/{{ $audit->new_values['path'] }}" download="{{ $audit->new_values['path'] }}">{{ $audit->new_values['path'] }}</a>
																		@else
																			No change
																		@endif
																	</td>
																</tr>
															@endforeach	
														</tbody>
													</table>
												</td>
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
				</div>
			</div>				<!-- end row 8 -->
		</div> <!-- end directors tab -->

		@if (isset($mode))
			@php $activetab = 'Business'; @endphp
		@endif
		<div id="business" class=" row {{$activetab == 'Business' ? '' : 'hidden' }}">
			<div class="white-box col-xs-12">
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
						@if (Gate::allows('cr_ap') && isset($company) && isset($mode) && $company->confirmed)
							<a href="{{ url("/company/edit/" . $company->id) . '/Business' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					</div>			
				</div>
				<div class="row">	<!-- row 9 -->
					<div class="col-sm-12 table-container"> <!-- Column 1 -->					
						@php 
							$topproductcount = 0; 
							$topproductsum = 0; 
						@endphp
						<table id="topproducttable" class="row table table-striped table-bordered table-hover table-tight dataTable">
							<thead>
								<tr>
									@if (isset($mode))
										{{-- <th></th> --}}
										<th width="60%">Brand</th>
										<th>Revenue %</th>
									@else
										<th class="no-sort" width="10%">
											<a href="" id="lnktopproduct" role="button" class="add-icon" title="Add brand"></a>	
										</th>
										<th width="60%">Brand <span class="note red">*</span></th>
										<th>Revenue % <span class="note red">*</span></th>
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
											<div class="form-group"> <!-- brand -->
												<div class="col-sm-12">
													{{ Form::select('topproductname[]',$brands , '',array('id' => "brands",'class' => 'form-control' ,'placeholder' => '','style' => 'width: 100%' )) }}

													@if ($errors->has('topproductname.' . $i)) <p class="bg-danger">{{ $errors->first('topproductname.' . $i) }}</p> @endif
												</div>
											</div> <!-- brand end --> 	
										</td>
										<td>
											<div class="form-group"> <!-- revenue % -->  
												<div class=" col-sm-12">
													{{ Form::text('topproductrevenue[]', old('topproductrevenue')[$i], array('id' => 'topproductrevenue', 'class' => 'form-control')) }}
													@if ($errors->has('topproductrevenue.' . $i)) <p class="bg-danger">{{ $errors->first('topproductrevenue.' . $i) }}</p> @endif
												</div>
											</div> <!-- revenue % end -->
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
												{{-- <td style="text-align: center">
												@if ($topproduct->audits->where('event', 'updated')->count() > 0)
													<a onclick="toggleBrHistory(this)">
														<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
													</a>
												@endif
												</td> --}}
												<td>{{ $topproduct->brand->name }}</td>
												<td >{{ $topproduct->topproductrevenue }}</td>										
											@else
												<td style="vertical-align:top">
													<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelTopproduct"></a>
													{{ Form::hidden('topproductid[]', $topproduct->id, array('id' => 'topproduct_id')) }}
													{{ Form::hidden('topproductdel[]', '', array('id' => 'topproductdel', 'class' => 'form-control')) }}
												</td>
												<td>												
													<div class="form-group"> <!-- brand -->
														@if (isset($mode))	
															<p class='form-control-static'>{{ $topproduct->topproductname }}</p>
														@else
															<div class=" col-sm-12">
																{{ Form::select('topproductname[]', $brandsarr, $topproduct->topproductname,array('id' => 'topproductname brands', 'class' => 'form-control' ,'style' => 'width: 100%' )) }}
																@if ($errors->has('topproductname.' . $i)) <p class="bg-danger">{{ $errors->first('topproductname.' . $i) }}</p> @endif
															</div>
														@endif
													</div> <!-- brand end -->
												</td>
												<td>
													<div class="form-group"> <!-- revenue % -->  
														
														@if (isset($mode))	
															<p class='form-control-static'>{{ $topproduct->topproductrevenue }}</p>
														@else
															<div class=" col-sm-12">
																{{ Form::text('topproductrevenue[]', $topproduct->topproductrevenue, array('id' => 'topproductrevenue', 'class' => 'form-control')) }}
																@if ($errors->has('topproduct.' . $i)) <p class="bg-danger">{{ $errors->first('topproduct.' . $i) }}</p> @endif
															</div>
														@endif
													</div> <!-- Revenue % end --> 																									
												</td>
											@endif
										</tr>
										<tr class="br-history-wrapper" style="display: none">
												<td colspan="3">
													<table class="table table-striped table-bordered br-history-table" style="text-size: 11px !important">	
														<thead style="background: #fcf8e3;">
															<tr>
																<th>On</th>
																<th>By</th>
																<th>Brand</th>
																<th>revenu</th>
															</tr>		
														</thead>
														<tbody>
															@foreach($topproduct->audits as $audit)													
																<tr>
																	<td> {{ $audit->created_at }} </td>
																	<td> {{ $audit->user->name }} </td>															
																	<td>
																		@if (array_key_exists('brand->name', $audit->new_values))
																			{{ $audit->new_values['brand->name'] }}
																		@else
																			No change
																		@endif
																	</td>
																	<td>
																		@if (array_key_exists('topproductrevenue', $audit->new_values))
																			{{ $audit->new_values['topproductrevenue'] }}
																		@else
																			No change
																		@endif
																	</td>
																	
																</tr>
															@endforeach	
														</tbody>
													</table>
												</td>
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
			</div>

			@if (isset($company) && ($company->companytype_id == 2 || $company->companytype_id == 3 || $company->companytype_id == 4))
			<div class="white-box col-xs-12">
				
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
						@if (Gate::allows('cr_ap') && isset($company) && isset($mode) && $company->confirmed)
							<a href="{{ url("/company/edit/" . $company->id) . '/Business' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					</div>
				</div>
				<div class="row">
					<div class=" col-sm-12 table-container"> <!-- Column 2 -->
						<?php $topcustomercount = 0; ?>
						<table id="topcustomertable" class="row table table-striped table-bordered table-hover table-tight dataTable">
							<thead>
								<tr>
									@if (isset($mode))
										{{-- <th></th> --}}
										<th width="45%">Buyer</th>
										<th width="20%">Type</th>
										<th width="35%">Country</th>
									@else									
										<th class="no-sort" width="10%">
											<a href="" id="lnktopcustomer" role="button" class="add-icon" title="Add customer"></a>	
										</th>
										<th width="40%">Buyer<span class="note red">*</span></th>
										<th width="20%">Type<span class="note red">*</span></th>
										<th width="30%">Country<span class="note red">*</span></th>
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
												<div class="form-group"> <!-- customer -->  
													<div class=" col-sm-12">
														{{ Form::text('topcustomername[]', old('topcustomername')[$i], array('id' => 'topcustomername', 'class' => 'form-control')) }}
														@if ($errors->has('topcustomername.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomername.' . $i) }}</p> @endif
													</div>
												</div> <!-- customer end --> 
											</td>
											<td>
												<div class="form-group"> <!-- buyertype -->  
													<div class=" col-sm-12">
														<select name="topcustomertype[]" class="form-control bm-select" id="topcustomertype">
															@foreach ($buyertypes as $buyertype)
																<option value="{{ $buyertype->id }}" <?= (!empty(old('topcustomertype')) && old('topcustomertype')[$i] == $buyertype->id) ? 'selected' : '' ?> >{{ $buyertype->name }}</option>
															@endforeach
														</select>
														@if ($errors->has('topcustomertype.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomertype.' . $i) }}</p> @endif
													</div>
												</div> <!-- buyertype end --> 
											</td>
											<td>
												<div class="form-group"> <!-- country -->  
												
													<div class=" col-sm-12">
														{{ Form::select('topcustomercountry[]', $allcountries->pluck('countryname', 'id'), $buyertype->country_id,array('id' => 'topcustomercountry select_country', 'class' => 'form-control select-input'))}}
														@if ($errors->has('topcustomercountry.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomercountry.' . $i) }}</p> @endif
													</div>
												</div> <!-- country end --> 
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
													{{-- <td style="text-align: center">
													@if ($topcustomer->audits->where('event', 'updated')->count() > 0)
														<a onclick="toggleBuHistory(this)">
															<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
														</a>
													@endif
													</td>						 --}}
													<td>{{ $topcustomer->topcustomername }}</td>
													<td>{{ $topcustomer->buyertype->name }}</td>
													<td>{{ $topcustomer->country->countryname }}</td>
												@else
													<td style="vertical-align:top">
														<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelTopcustomer"></a>
														{{ Form::hidden('topcustomerid[]', $topcustomer->id, array('id' => 'topcustomer_id')) }}
														{{ Form::hidden('topcustomerdel[]', '', array('id' => 'topcustomerdel', 'class' => 'form-control')) }}
													</td>
													<td>
														<div class="form-group"> <!-- customer -->  
															@if (isset($mode))	
																<p class='form-control-static'>{{ $topcustomer->topcustomername }}</p>
															@else
																<div class=" col-sm-12">
																	{{ Form::text('topcustomername[]', $topcustomer->topcustomername, array('id' => 'topcustomername', 'class' => 'form-control')) }}
																	@if ($errors->has('topcustomername.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomername.' . $i) }}</p> @endif
																</div>																
															@endif
														</div> <!-- customer end -->
													</td>
													<td>
														<div class="form-group"> <!-- buyertype -->  
															@if (isset($mode))	
																<p class='form-control-static'>{{ $topcustomer->buyertype->name }}</p>
															@else																
																<div class=" col-sm-12">
																	{{ Form::select('topcustomertype[]', $buyertypes->pluck('name', 'id'), $topcustomer->buyertype_id,array('id' => 'topcustomertype', 'class' => 'form-control bm-select'))}}
																	@if ($errors->has('topcustomertype.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomertype.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- buyertype end -->
													</td>
													<td>
														<div class="form-group"> <!-- country -->  
															@if (isset($mode))	
																<p class='form-control-static'>{{ $topcustomer->country->countryname }}</p>
															@else																
																<div class=" col-sm-12">
																	{{ Form::select('topcustomercountry[]', $allcountries->pluck('countryname', 'id'), $topcustomer->country_id,array('id' => 'topcustomercountry', 'class' => 'form-control bm-select'))}}
																	@if ($errors->has('topcustomercountry.' . $i)) <p class="bg-danger">{{ $errors->first('topcustomercountry.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- country end -->
													</td>
													</td>
												@endif
											</tr>
											<tr class="bu-history-wrapper" style="display: none">
											<td colspan="4">
												<table class="table table-striped table-bordered bu-history-table" style="text-size: 11px !important">	
													<thead style="background: #fcf8e3;">
														<tr>
															<th>On</th>
															<th>By</th>
															<th>Buyer</th>
															<th>Type</th>
															<th>Country</th>
														</tr>		
													</thead>
													<tbody>
														@foreach($topcustomer->audits as $audit)													
															<tr>
																<td> {{ $audit->created_at }} </td>
																<td> {{ $audit->user->name }} </td>															
																<td>
																	@if (array_key_exists('topcustomername', $audit->new_values))
																		{{ $audit->new_values['topcustomername'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('buyertype_id', $audit->new_values))
																		{{ \App\Buyertype::where('id',$audit->new_values['buyertype_id'])->first()->name }} 
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('country_id', $audit->new_values))
																		{{ \App\Country::where('id',$audit->new_values['country_id'])->first()->countryname }} 
																	@else
																		No change
																	@endif
																</td>
															</tr>
														@endforeach	
													</tbody>
												</table>
											</td>
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
				</div>
			</div>				<!-- end row 9 -->
			@endif
			@if (isset($company) && ($company->companytype_id == 1 || $company->companytype_id == 3))
			<div class="white-box col-xs-12">
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
						@if (Gate::allows('cr_ap') && isset($company) && isset($mode) && $company->confirmed)
							<a href="{{ url("/company/edit/" . $company->id) . '/Business' }}"><span class="edit-icon--with-border"></span></a>
						@endif
					</div>
				</div>
				<div class="row">
					<div class=" col-sm-12 table-container"> <!-- Column 2 -->
						<?php $topsuppliercount = 0; ?>
						<table id="topsuppliertable" class="row table table-striped table-bordered table-hover table-tight dataTable">
							<thead>
								<tr>
									@if (isset($mode))
										{{-- <th></th> --}}
										<th width="80%">Supplier</th>
										<th width="20%">Type</th>
									@else									
										<th class="no-sort" width="10%">
											<a href="" id="lnktopsupplier" role="button" class="add-icon" title="Add supplier"></a>	
										</th>
										<th width="70%">Supplier <span class="note red">*</span></th>
										<th width="20%">Type<span class="note red">*</span></th>
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
														<div class=" col-sm-12">
															{{ Form::text('topsuppliername[]', old('topsuppliername')[$i], array('id' => 'topsuppliername', 'class' => 'form-control')) }}
															@if ($errors->has('topsuppliername.' . $i)) <p class="bg-danger">{{ $errors->first('topsuppliername.' . $i) }}</p> @endif
														</div>
													</div> <!-- supplier end --> 
												</div>
											</td>
											<td>
												<div class="form-group"> <!-- suppliertype -->  
													<div class=" col-sm-12">
														<select name="topvendortype[]" class="form-control bm-select" id="topvendortype">
															@foreach ($suppliertypes as $suppliertype)
																<option value="{{ $suppliertype->id }}" <?= (!empty(old('topvendortype')) && old('topvendortype')[$i] == $suppliertype->id) ? 'selected' : '' ?> >{{ $suppliertype->name }}</option>
															@endforeach
														</select>
														@if ($errors->has('topvendortype.' . $i)) <p class="bg-danger">{{ $errors->first('topvendortype.' . $i) }}</p> @endif
													</div>
												</div> <!-- suppliertype end --> 
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
												{{-- <td style="text-align: center">
												@if ($topsupplier->audits->where('event', 'updated')->count() > 0)
													<a onclick="toggleSuHistory(this)">
														<span class="glyphicon glyphicon-plus-sign" style="cursor: pointer;" title="Changes" />
													</a>
												@endif
												</td>							 --}}
													<td>{{ $topsupplier->topsuppliername }}</td>
													<td>{{ $topsupplier->suppliertype->name }}</td>
												@else
													<td style="vertical-align:top">
														<a href="#" role="button" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelTopsupplier"></a>
														{{ Form::hidden('topsupplierid[]', $topsupplier->id, array('id' => 'topsupplier_id')) }}
														{{ Form::hidden('topsupplierdel[]', '', array('id' => 'topsupplierdel', 'class' => 'form-control')) }}
													</td>
													<td>
														<div>
															<div class="form-group"> <!-- supplier -->  
																@if (isset($mode))	
																	<p class='form-control-static'>{{ $topsupplier->topsuppliername }}</p>
																@else
																	<div class="col-sm-12">
																		{{ Form::text('topsuppliername[]', $topsupplier->topsuppliername, array('id' => 'topsuppliername', 'class' => 'form-control')) }}
																		@if ($errors->has('topsuppliername.' . $i)) <p class="bg-danger">{{ $errors->first('topsuppliername.' . $i) }}</p> @endif
																	</div>
																@endif
															</div> <!-- supplier end -->
														</div>
													</td>
													<td>
														<div class="form-group"> <!-- suppliertype -->  
															@if (isset($mode))	
																<p class='form-control-static'>{{ $topsupplier->suppliertype->name }}</p>
															@else																
																<div class=" col-sm-12">
																	{{ Form::select('topvendortype[]', $suppliertypes->pluck('name', 'id'), $topsupplier->suppliertype_id,array('id' => 'topvendortype', 'class' => 'form-control bm-select'))}}
																	@if ($errors->has('topvendortype.' . $i)) <p class="bg-danger">{{ $errors->first('topvendortype.' . $i) }}</p> @endif
																</div>
															@endif
														</div> <!-- suppliertype end -->
													</td>
												@endif
											</tr>
											<tr class="su-history-wrapper" style="display: none">
											<td colspan="3">
												<table class="table table-striped table-bordered su-history-table" style="text-size: 11px !important">	
													<thead style="background: #fcf8e3;">
														<tr>
															<th>On</th>
															<th>By</th>
															<th>Supplier</th>
															<th>Type</th>
														</tr>		
													</thead>
													<tbody>
														@foreach($topsupplier->audits as $audit)													
															<tr>
																<td> {{ $audit->created_at }} </td>
																<td> {{ $audit->user->name }} </td>															
																<td>
																	@if (array_key_exists('topsuppliername', $audit->new_values))
																		{{ $audit->new_values['topsuppliername'] }}
																	@else
																		No change
																	@endif
																</td>
																<td>
																	@if (array_key_exists('suppliertype_id', $audit->new_values))
																		{{ \App\Suppliertype::where('id',$audit->new_values['suppliertype_id'])->first()->name }} 
																	@else
																		No change
																	@endif
																</td>
																
															</tr>
														@endforeach	
													</tbody>
												</table>
											</td>
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
				</div>
			</div>			<!-- end row 9 -->
			@endif
		</div> <!-- end products tab -->
		<div class="row">
			<div class=" col-sm-12 table-container tb-footer-container"> <!-- Column 1 -->
				@if (isset($mode))
					@if (!$company->confirmed && Gate::allows('co_cr') && $company->iscomplete)
						<div class="row" style="display:flex; justify-content : center ;align-items : center ; margin-top  :10px">
						    <div class="col-md-10 col-sm-7">
								<div class="checkbox">
									<label class="checkbox">
										<input class="bm-checkbox" type="checkbox" name="cbconfirm" id ="cbconfirm">
										<span class="checkmark"></span>
										<span class="bm-sublabel">I hereby confirm that the above data and attachments are correct.</span>
									</label>
								</div>
							</div>
							<div class=" col-md-2 col-sm-5"> <!-- Column 1 -->
								{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
								<a href="" class="biz-button colored-default" id="lnkconfirm" type="button" title="Confirm">
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
					
					<ul class="list-inline" style="display:flex;justify-content : space-between">
						@if ($onetab  != 1)
							<li class="hidden-xs"><button id="previous" type="button" class="biz-button blank-bordered prev-step ">Previous</button></li>
						@endif
						@switch($activetab)
							@case('BasicInfo')
								@php $nexttabname = 'AuthorizedSignatory' @endphp
								@break
							@case('AuthorizedSignatory')
								@if (isset($company) && ($company->companytype_id == 2 || $company->companytype_id == 3 || $company->companytype_id == 4))
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
							<li style="width: 100%"><button type="button" class="biz-button colored-default next-step ">Save</button></li>
						@else
							<li><button type="button" class="biz-button colored-default  next-step"><?= $nexttabname != 'Finish' ? 'Next <i class="fa fa-arrow-right"></i>' : '' ?> {{ $nexttabname }}</button></li>
						@endif
					</ul>  
				@endif			
		</div> <!-- Column 1 end -->

	</div> <!--row 10 end -->
	
	{{ Form::close() }}
	@if (isset($mode))
		@if (isset($company) && $company->confirmed && $company->iscomplete && Gate::allows('co_ch', $company->id))
			@if (!$company->confirmed)			
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

@endsection	
@push('scripts')	
	<script type="text/javascript">
	@if(!isset($_GET['profile']))
		$(document).ready(function(){
			
			var phone = document.getElementById("phone");
			var fax = document.getElementById("fax");
			var signatoryphone = document.getElementById("signatoryphone");
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
			if (signatoryphone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(signatoryphone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(signatoryphone);
			}
			if (ownerphone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(ownerphone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(ownerphone);
			}
			if (directorphone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(directorphone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(directorphone);
			}
			
			Updatecity();
			
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
			else if ($("#companytype_forwarder").is(":checked"))
				tabdivs($("#companytype_forwarder").val());
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
				is_forwarder = $("#companytype_forwader").is(":checked");
				if (is_buyer && is_supplier)
					tabdivs('3')
				else if(is_buyer)
					tabdivs($("#companytype_buyer").val());
				else if(is_supplier)
					tabdivs($("#companytype_supplier").val());
				else if(is_forwarder)
					tabdivs($("#companytype_forwarder").val());
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
						if (!$("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked") && !$("#companytype_forwarder").is(":checked")) {
							alert('Please select company type');
							return;
						}
						$("#newtab").val('AuthorizedSignatory');
						break;
					case 'AuthorizedSignatory':
						if (($("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked")) || $("#companytype_forwarder").is(":checked")) 
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
				//alert(activetab);
				switch (activetab) {					
					case 'BasicInfo':
						break;
					case 'AuthorizedSignatory':
						$("#activetab").val('BasicInfo');
						$("#newtab").val('');
						$("#authorizedsignatory").hide();
						$("#tabauthsignatory").removeClass('tab--active').addClass('tab--idle is-circle');
						$("#basicinfo").removeClass('hidden');
						$("#tabbasicinfo").removeClass('tab--done is-circle').addClass('tab--active');
						document.getElementById('tabauthsignatory').innerHTML = ' ';
						document.getElementById('tabbasicinfo').innerHTML = 'Basic Info';
						$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Authorized Signatory');
						$("#previous").hide();
						break;
					case 'Shareholders':
						$("#ownertable").remove();
						$("#activetab").val('AuthorizedSignatory');
						$("#newtab").val('');
						$("#shareholders").hide();
						$("#tabshareholders").removeClass('tab--active').addClass('tab--idle is-circle');
						$("#authorizedsignatory").removeClass('hidden');
						$("#tabauthsignatory").removeClass('tab--done is-circle').addClass('tab--active');
						document.getElementById('tabshareholders').innerHTML = ' ';
						document.getElementById('tabauthsignatory').innerHTML = 'Authorized Signatory';
						$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Shareholders');
						//$("#previous").hide();
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
						if (($("#companytype_supplier").is(":checked") && !$("#companytype_buyer").is(":checked")) || $("#companytype_forwarder").is(":checked")) {
							$("#activetab").val('AuthorizedSignatory');
							$("#newtab").val('');
							$("#bankdata").hide();
							$("#authorizedsignatory").removeClass('hidden');
							$("#tabbanks").addClass('tab--idle is-circle').removeClass('tab--active');
							$("#tabauthsignatory").addClass('tab--active').removeClass('tab--done is-circle');
							document.getElementById('tabbanks').innerHTML = ' ';
							document.getElementById('tabauthsignatory').innerHTML = 'Authorized Signatory';
							//$("#previous").hide();
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
						if ($("#companytype_buyer").is(":checked") && !($("#companytype_supplier").is(":checked") && $("#companytype_forwarder").is(":checked"))) {
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
			@if(isset($company ))
				$('.text-input').addClass('focused');
				$('.select2-selection.select2-selection--single').addClass('focused');
				$('.select2-selection.select2-selection--multiple').addClass('focused');
			@endif
			@if( isset($countries ))
				$('.select-input').addClass('focused');
				$('.select2-selection.select2-selection--single').addClass('focused');
				$('.select2-selection.select2-selection--multiple').addClass('focused');
			@endif	
				var country =  $('#select_country').val();
        var selectedCity =  $('#select_city').val();
            $.ajax({
                url: '/countries/cities',
                method: "POST",
                cache: false,
                data: {
						'country_id':country,
						'_token': $('input[name=_token]').val()
					},
                success: function (response) {
                    $('#select_city').empty();
                    $.each(response, function (index, city) {
                        if(selectedCity == index)
                        {
                            $('#select_city').append($('<option></option>').val(index).html(city).attr("selected", true));
                        }else{
                            $('#select_city').append($('<option></option>').val(index).html(city));
                        }
                    });
                },
            });

        $('#select_country').on('change', function () {
            var country = $(this).val();
			checkCountryForType();

            $.ajax({
                url: '/countries/cities',
                method: "POST",
                cache: false,
                data: {
						'country_id':country,
						'_token': $('input[name=_token]').val()
					},
                success: function (response) {
                    $('#select_city').empty();
                    $.each(response, function (index, city) {
                        var newOption = new Option(city, index, false, false);
                        $('#select_city').append(newOption).trigger('change');
                    });
                },
            });
        });
			// $("#country_id").change(function(){
			// 	Updatecity();
			// }); // $("#country_id").change end
			
			// $("#city_id").change(function(){
			// 	$("#selected_city_id").val($('#city_id option:selected').val());
			// }); // $("#city_id").change end
			
			function checkCountryForType(changed_by_user = true) {
				var allowed = parseInt($('#select_country option:selected').attr("data-allowed"));
				if(changed_by_user) {
					buyer_checked = $("#companytype_buyer").is(":checked");
					supplier_checked = $("#companytype_supplier").is(":checked");
					forwarder_checked = $("#companytype_forwarder").is(":checked");
				}
				if(allowed) {
					$('#cb_buyer').show();
					if (changed_by_user) {
						if(!buyer_checked)
							$('#companytype_buyer').trigger('click');
						if(supplier_checked)
							$('#companytype_supplier').trigger('click');
						if(forwarder_checked)
							$('#companytype_forwader').trigger('click');
					}
					$('#comp_type_wrapper').show();					
					//$('#supplier_only').hide();
					country_changed_to_allowed = true;
				} else {
					$('#cb_buyer').hide();
				}
				if (!allowed && country_changed_to_allowed) {
					if (changed_by_user) {
						if(buyer_checked)
							$('#companytype_buyer').trigger('click');
						if(!supplier_checked)
							$('#companytype_supplier').trigger('click');
						if(!forwarder_checked)
							$('#companytype_forwader').trigger('click');
					}
					//$('#comp_type_wrapper').hide();
					//$('#supplier_only').show();
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
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="ownerphone[]" class="form-control mobile" name="ownerphone[]" value="" type="text", placeholder="">';
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
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="beneficialphone[]" class="form-control mobile" name="beneficialphone[]" value="" type="text", placeholder=""></div>';
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
				row = row + '<div class="col-lg-6 col-sm-9 col-xs-12"><input id="directorphone[]" class="form-control mobile" name="directorphone[]" value="" type="text", placeholder="">';
				//row = row + phones;
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
				row = row + '<td>';
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><select name="topproductname[]" id="brands" class="form-control ">';
				<?php
					if (isset($brands)) {
						foreach ($brands as  $id => $brand) {
							echo "row = row + '<option value=" . $id .">" .$brand . "</option>';";
						}
					}
				?>
				row = row + '</select></div>';
				row = row + '</div></td>';			
				row = row + '<td>';
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><input name="topproductrevenue[]" type="text" class="form-control"></div>';
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
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><input name="topcustomername[]" type="text" class="form-control"></div>';
				row = row + '</div>';		
				row = row + '</div></td>';
				row = row + '<td>';
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><select name="topcustomertype[]" id="topcustomertype select_country" class="select-input form-control">';
				<?php
					if (isset($buyertypes)) {
						foreach ($buyertypes as $buyertype) {
							echo "row = row + '<option value=" . $buyertype->id .">" . str_replace("'", " ", $buyertype->name) . "</option>';";
						}
					}
				?>
				row = row + '</select></div>';
				row = row + '</div></td>';
				row = row + '<td>';
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><select name="topcustomercountry[]" id="topcustomercountry select_country" class="select-input form-control ">';
				<?php
					if (isset($allcountries)) {
						foreach ($allcountries as $country) {
							echo "row = row + '<option value=" . $country->id .">" . str_replace("'", " ", $country->countryname) . "</option>';";
						}
					}
				?>
				row = row + '</select></div>';
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
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><input name="topsuppliername[]" type="text" class="form-control"></div>';
				row = row + '</div>';		
				row = row + '</div></td>';
				
				row = row + '<td>';
				row = row + '<div class="form-group">';
				row = row + '<div class="col-sm-12"><select name="topvendortype[]" id="topvendortype" class="form-control bm-select">';
				<?php
					if (isset($suppliertypes)) {
						foreach ($suppliertypes as $suppliertype) {
							echo "row = row + '<option value=" . $suppliertype->id .">" . str_replace("'", " ", $suppliertype->name) . "</option>';";
						}
					}
				?>
				row = row + '</select></div>';
				row = row + '</div></td>';
				row = row + '<td>';
				
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
				//$("#tradefilename").addClass('hidden');
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
				
				size_is_valid = checkFileSize(file.size, 6291456);
				if(!size_is_valid)
					return false;

				pendingFileUpload();
			
				var formData = new FormData;
				formData.append('attach', file);
				formData.append('_token', $('input[name=_token]').val());
					
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", AssocprogressHandler, false);
				ajax.addEventListener("load", AssoccompleteHandler, false);
				//$("#assocfilename").addClass('hidden');
				$("#AssocprogressBar").removeClass('hidden');
				ajax.open("POST", "/attach");
				ajax.send(formData);
			}); //$('#assocattach').on('change', (event) => {
				
			@if (isset($company) && $topproductcount == 0 && !$errors->has('topproductcount'))
				for (let i = 0; i < 5; i++) {
					$("#lnktopproduct").trigger('click');
					$("#topproductcount").val(5);
					$("#topproductsum").val(0);
				}
			@endif
			@if (isset($company) && isset($topcustomercount) && $topcustomercount == 0 && !$errors->has('topcustomercount'))
				for (let i = 0; i < 5; i++) {
					$("#lnktopcustomer").trigger('click');
					$("#topcustomercount").val(5);
				}
			@endif
			@if (isset($company) && isset($topsuppliercount) && $topsuppliercount == 0 && !$errors->has('topsuppliercount'))
				for (let i = 0; i < 5; i++) {
					$("#lnktopsupplier").trigger('click');
					$("#topsuppliercount").val(5);
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
			//$("#tax_file_name").addClass('hidden');
			$("#tax_progress_bar").removeClass('hidden');
			ajax.open("POST", "/attach");
			ajax.send(formData);
		});

		function checkFileSize(fileSize, maxsize = 2097152) {
			if (fileSize > maxsize) {
				alert('Maximum file size should be ' + parseInt(maxsize / 1000000) + 'M');
				return false;
			}
			return true;
		}

		function checkFileType(fileType) {
			if (fileType == '') {
				var plainType = '';
			} else {
				var plainType = fileType.split('/')[1];
			}			
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
			$('#tradefilename').text($('#tmptradefilename').val());
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
			$('#assocfilename').text($('#tmpassocfilename').val());
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
			$('#tax_file_name').text($('#tmp_tax_file_name').val());
			$('#tax_attach_id').val(event.target.responseText);
			completeFileUpload();
		}
		
		function UploadprogressHandler(event, atttype, table) {
			if (atttype == 1 || atttype == 3 || atttype == 11 || atttype == 28) {
				var span = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("progress")[0];
			} else if (atttype == 9 || atttype == 10 || atttype == 13 || atttype == 39){
				var span = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];				
			}  else if (atttype == 2 || atttype == 4 || atttype == 12 || atttype == 29){
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
			if (atttype == 1 || atttype == 3 || atttype == 11  || atttype == 28) {
				var span = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[1];
				var delspan = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[0];
				var progress = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("progress")[0];
				var thefilename = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("input")[0];
				var fileid = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("input")[1];
				if (fileid.value == '') {
					attachcount.value = parseInt(attachcount.value) + 1;
				}
			} else if (atttype == 9 || atttype == 10 || atttype == 13 || atttype == 39){
				var span = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var delspan = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[0];
				var progress = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
				var thefilename = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("input")[0];
				var fileid = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("input")[1];
				if (fileid.value == '') {
					attachcount.value = parseInt(attachcount.value) + 1;
				}
			}  else if (atttype == 2 || atttype == 4 || atttype == 12  || atttype == 29){
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
						tr.cells[1].getElementsByTagName("input")[2].value='+1';
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
						tr.cells[1].getElementsByTagName("input")[2].value='+1';
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
						tr.cells[1].getElementsByTagName("input")[3].value='+1';
						var tbl = tr.cells[1].getElementsByTagName("table")[0];
						// console.log(tbl.getElementsByTagName("input")[2]);
						tbl.getElementsByTagName("input")[2].value = 'A'
						tbl.getElementsByTagName("input")[7].value = '1'
						$("#directorcount").val(parseInt($("#directorcount").val()) - 1);
					} else if (inputval == 'topproductdel') {
						inputs[j].value  = 1;
						// tr.cells[1].getElementsByTagName("input")[0].value='A';						
						tr.cells[2].getElementsByTagName("input")[0].value='0';
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
			if (atttype == 3 || atttype == 10 || atttype == 11 || atttype == 13 || atttype == 1 || atttype == 9 || atttype == 28 || atttype == 39) {
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
						var found = 0;
						//alert($('#selected_city_id').val());
						$.each(data, function(i, item) {							
							if ($('#selected_city_id').val() == i ) {
								found = i;
							}								
							// console.log(j);
							j = j + 1;
						});
						//alert(found);
						$.each(data, function(i, item) {
							if (i == found || found == 0) {
								$('#city_id').append($("<option></option>").attr("value", i).text(item.city_).attr("selected", true));
								if (found == 0) {
									$('#selected_city_id').val(i);
									found = i;
								}
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
							if (isNumber(row.cells[revenuecell].getElementsByTagName("input")[0].value))  {
								var revenue = parseInt(revenue) + parseInt(row.cells[revenuecell].getElementsByTagName("input")[0].value);
							}
						}
					}		
				}
				$("#topproductsum").val(revenue);
			}
			// buyer count
			var table = document.getElementById('topcustomertable');
			if (table) {
				var rowLength = table.rows.length;
				var customercount = 0;	
				if ($("#topcustomertable tr:visible").length > 1) {
					var row = table.rows[0];
					for (var i = 1; i < rowLength; i += 1){
						var row = table.rows[i];
						if (row.style.display != 'none') {
							if (row.cells[1].getElementsByTagName("input")[0].value.trim() != '')  {
								var customercount = customercount + 1;
							}
						}
					}		
				}
				$("#topcustomercount").val(customercount);
			}
			// buyer count end
			// supplier count
			var table = document.getElementById('topsuppliertable');
			if (table) {
				var rowLength = table.rows.length;
				var suppliercount = 0;	
				if ($("#topsuppliertable tr:visible").length > 1) {
					var row = table.rows[0];
					for (var i = 1; i < rowLength; i += 1){
						var row = table.rows[i];
						if (row.style.display != 'none') {
							if (row.cells[1].getElementsByTagName("input")[0].value.trim() != '')  {
								var suppliercount = suppliercount + 1;
							}
						}
					}		
				}
				$("#topsuppliercount").val(suppliercount);
			}
			// supplier count end
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
			if (atttype == 1 || atttype == 3 || atttype == 11 || atttype == 28) {
				var span = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[0].getElementsByTagName("td")[2].getElementsByTagName("progress")[0];
			} else if (atttype == 9 || atttype == 10 || atttype == 13 || atttype == 39) {
				var span = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("span")[1];
				var progress = table.getElementsByTagName("tr")[1].getElementsByTagName("td")[1].getElementsByTagName("progress")[0];
			} else if (atttype == 2 || atttype == 4 || atttype == 12 || atttype == 29) {
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
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Authorized Signatory')
								break;
							case 'AuthorizedSignatory' :
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
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Authorized Signatory')
								break;
							case 'AuthorizedSignatory' :
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
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Authorized Signatory')
								break;
							case 'AuthorizedSignatory' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Shareholders')
								break;
							case 'Directors' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Banks')
						}						
					}
					break;
				case '4' :
					$("#divshareholders").addClass('hidden');
					$("#divdirectors").addClass('hidden');
					$("#divbanks").removeClass('hidden');
					if ($("#onetab").val() != '1') {
						switch($("#activetab").val()) {
							case 'BasicInfo' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Authorized Signatory')
								break;
							case 'AuthorizedSignatory' :
								$(".next-step").html('Next <i class="fa fa-arrow-right"></i> Banks')
								break;
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
					case 'AuthorizedSignatory':
						SetAsDone("#tabbasicinfo");
						break;
					case 'Shareholders':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabauthsignatory");
						break;
					case 'Directors':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabauthsignatory");
						SetAsDone("#tabshareholders");
						break;
					case 'BankData':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabauthsignatory");
						SetAsDone("#tabshareholders");
						SetAsDone("#tabdirectors");				
						break;
					case 'Business':
						SetAsDone("#tabbasicinfo");
						SetAsDone("#tabauthsignatory");
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
		function toggleCOHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".co-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".co-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
		function toggleBOHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".bo-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".bo-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
		function toggleDOHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".do-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".do-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
		function toggleBrHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".br-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".br-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
		function toggleBuHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".bu-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".bu-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
		function toggleSuHistory(btn) {
			if($(btn).find("span").hasClass("glyphicon-minus-sign")) {
				$(btn).parent().parent().next(".su-history-wrapper").hide()

				$(btn).find("span").removeClass("glyphicon-minus-sign")
				$(btn).find("span").addClass("glyphicon-plus-sign")
			} else {
				$(btn).parent().parent().next(".su-history-wrapper").show()

				$(btn).find("span").removeClass("glyphicon-plus-sign")
				$(btn).find("span").addClass("glyphicon-minus-sign")
			}
		}
		function CompanyTypeSelect(e){
			console.log();
			if($(e).val()!=4){
				if($("#companytype_forwarder").is(':checked')){
					$("#companytype_forwarder").prop('checked', false)
				}
			}
			if($("#companytype_forwarder").is(':checked')){
				$("#companytype_buyer").prop('checked', false)
				$("#companytype_supplier").prop('checked', false)
			}

		}
		@endif
	</script>
@endpush

