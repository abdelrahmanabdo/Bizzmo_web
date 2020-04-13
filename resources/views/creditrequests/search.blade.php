@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
@if(Gate::denies('co_cr'))
	{{ Form::open(array('id' => 'frmManage')) }}
	@if ($title != 'Pending credit requests' && $title != 'Credit requests pending customer')
		<div class="row">	<!-- row 1 -->	
			@if(isset($title))	
				<h2 class="bm-pg-title">{{ $title }}</h2>
				<br/>
			@endif
			<div class="col-md-4">  <!-- Column 3 -->
				<div class="form-group"> <!-- status -->  
					{{ Form::label('creditstatus_id', 'Request status') }}
					@if($showAllPending)
						{{ Form::select('creditstatus_id', $creditstatuses, "-1", array('id' => 'creditstatus_id', 'class' => 'form-control bm-select'))}}		
					@else
						{{ Form::select('creditstatus_id', $creditstatuses, Input::get('creditstatus_id'),array('id' => 'creditstatus_id', 'class' => 'form-control bm-select'))}}		
					@endif
				</div> <!-- status end -->  
			</div>					<!-- end col 3 -->
			<div class="col-md-4">  <!-- Column 1 -->
				<div class="form-group"> <!-- Company name -->  
					{{ Form::label('companyname', 'Company') }}
					{{ Form::select('company_id', $companies, Input::get('company_id'),array('id' => 'company_id', 'class' => 'form-control bm-select'))}}		
					{{ Form::hidden('id', Input::old('id'), array('id' => 'id')) }}
					@if ($errors->has('company_id')) <p class="bg-danger">{{ $errors->first('company_id') }}</p> @endif
				</div> <!-- Company name -->  
			</div>					<!-- end col 1 -->
			<div class="col-md-4">  <!-- Column 2 -->
				<div class="form-group"> <!-- country -->  
					{{ Form::label('country_id', 'Country') }}
					{{ Form::select('country_id', $countries, Input::get('country_id'),array('id' => 'country_id', 'class' => 'form-control bm-select'))}}		
					@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
				</div> <!-- country end -->  
			</div>					<!-- end col 2 -->
		</div>				<!-- end row 1 -->
		<div class="row-fluid row">	<!-- row 3 --> 
		<div class="col-md-12"> <!-- Column 1 -->
			@if (isset($mode))
			@else
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
				<a href="" class="btn btn-info bm-btn" id="lnksubmit">
					Search
				</a>
			@endif    
			
		</div> <!-- Column 1 end -->
		</div> <!--row 3 end -->
		{{ Form::close() }}
	@endif
@endif
	@if (Gate::allows('co_cr'))
		<?php 
			$hasCreditRequests = (count($pendingCreditRequests) + count($previousApprovedCreditRequests) + count($previousRejectedCreditRequests)) > 0;
			$hasNonRejectedCreditRequests = (count($pendingCreditRequests) + count($previousApprovedCreditRequests)) > 0;
			$hasOnlyRejectedCreditRequests = $hasNonRejectedCreditRequests ? 0 : count($previousRejectedCreditRequests);		
			$previousCreditRequests = $previousApprovedCreditRequests->merge($previousRejectedCreditRequests);
			$buyerCompany = Auth::user()->getBuyerCompany();
			if(!$hasCreditRequests || $hasOnlyRejectedCreditRequests) {
				$result = $buyerCompany->canCreateCreditRequest();
				$canCreate = $result["canCreate"];
				$error = $result["error"];
			} else {
				$result = $buyerCompany->canIncreaseCreditRequest();
				$canIncrease = $result["canIncrease"];
				$error = $result["error"];
			}
		?>
		@if (isset($hasCreditRequests))
			@if(!$hasCreditRequests)
				<div class="row row-fluid">	<!-- row 1 -->
					<div class="col-md-12">
						<div class="text-center col-md-offset-2 col-md-8">
							<h3 class="bm-msg-header info" style="margin-bottom: 45px;">Create Credit Request</h3>
							<span class="bm-circle"></span>
							<p class="bm-msg-details info">To apply for a credit request, please prepare a PDF, JPG or PNG files of your bank statement and your
						financials to upload. You will also need financial data for the last three years, and at least three business references.</h3>
							<br>
							<a 
								href="<?= $canCreate ? url('/creditrequests/create') : '#' ?>" 
								class="btn bm-btn bm-top-space green" 
								role="button" 
								style="margin: auto;height: 40px"
								title="<?= $canCreate ? 'Create credit request' : $error ?>"
								<?= !$canCreate ? "disabled" : "" ?>
								>
								<strong><span class="glyphicon glyphicon-plus"></span> Create credit request</strong>
							</a>
							<br>&nbsp;<br>
						</div>
					</div>
				</div>
			@else	
				<div class="text-center" style="margin-bottom: 40px">
				@if($hasOnlyRejectedCreditRequests)
				<a 
					href="<?= $canCreate ? url('/creditrequests/create') : '#' ?>" 
					class="btn bm-btn bm-top-space green" 
					role="button" 
					style="margin: auto;height: 40px"
					title="<?= $canCreate ? 'Create credit request' : $error ?>"
					<?= !$canCreate ? "disabled" : "" ?>
					>
					<strong><span class="glyphicon glyphicon-plus"></span> Create credit request</strong>
					</a>
				@else
				<a 
					href="<?= $canIncrease ? url('/creditrequests/raise/') : '#' ?>" 
					class="btn bm-btn green" 
					role="button" 
					style="margin-bottom: 10px"
					title="<?= $canIncrease ? 'Increase credit request' : $error ?>"
					<?= !$canIncrease ? "disabled" : "" ?>
					>
					<strong><span class="glyphicon glyphicon-plus"></span> Increase credit limit</strong>
				</a>
				@endif
				</div>
				<h4>Pending requests</h4>
				@if(count($pendingCreditRequests) > 0)
					@include('creditrequests.table', ['creditrequests' => $pendingCreditRequests, 'title' => ''])
				@else
					<div>No pending requests</div>
				@endif

				<!-- Previous requests -->
				@if(count($previousCreditRequests) > 0)
					<br/>
					<h4>Previous requests</h4>
					@include('creditrequests.table', ['creditrequests' => $previousCreditRequests, 'title' => ''])
				@endif
			@endif
		@endif
	@else
		@if (isset($creditrequests))
			@include('creditrequests.table', ['creditrequests' => $creditrequests, 'title' => ''])
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
		});
	</script>
@endpush 