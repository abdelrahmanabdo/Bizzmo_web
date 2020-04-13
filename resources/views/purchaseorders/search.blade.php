@extends('layouts.app', ['hideRightMenu' => true , 'hideRightMenuAndExtend' => true])
@section('title')
	@if (isset($title))
		{{ $title }}		
	@endif
@stop
@section('content')
	@if (env('newDesign'))
		@include('includes.transactions-nav')
	@endif
	@if (!isset($hideconditions))
	{{ Form::open(array('id' => 'frmManage')) }}
	<div class="row-fluid">	<!-- row 1 -->		
		@if(isset($title))	
		<h3 class="bm-title">{{ $title }}</h3>
		<br/>
		@endif
		<div class="col-md-4">  <!-- column 4 -->
			<div class="form-group text-input"> <!-- vendor -->  
				{{ Form::label('po_status', 'Status',array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				{{ Form::select('po_status', $poStatuses, Input::get('po_status'),array('id' => 'po_status', 'class' => 'form-control bm-select' , 'placeholder' => ''))}}
				@if ($errors->has('po_status')) <p class="bg-danger">{{ $errors->first('po_status') }}</p> @endif
			</div> <!-- vendor end -->  
		</div>	
		<div class="col-md-4">  <!-- column 1 -->
			<div class="form-group text-input"> <!-- from date -->  
				{{ Form::label('fromdate', 'From',array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				{{ Form::text('fromdate', Input::get('fromdate'), array('id' => 'fromdate', 'class' => 'form-control')) }}			
				@if ($errors->has('fromdate')) <p class="bg-danger">{{ $errors->first('fromdate') }}</p> @endif
			</div> <!-- from date end -->  
		</div>					<!-- column 1 end -->
		<div class="col-md-4">  <!-- column 2 -->
			<div class="form-group text-input"> <!-- to date -->  
				{{ Form::label('todate', 'To',array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				{{ Form::text('todate', Input::get('todate'), array('id' => 'todate', 'class' => 'form-control')) }}			
				@if ($errors->has('todate')) <p class="bg-danger">{{ $errors->first('todate') }}</p> @endif
			</div> <!-- to date end -->  
		</div>					<!-- column 2 end -->		
		<div class="col-md-12">  <!-- column 4 -->
			<div class="form-group text-input"> <!-- vendor -->  
				{{ Form::label('search', 'Search buyer or supplier',array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				{{ Form::text('search', Input::get('search'),array('id' => 'search', 'class' => 'form-control'))}}
				@if ($errors->has('search')) <p class="bg-danger">{{ $errors->first('search') }}</p> @endif
			</div> <!-- vendor end -->  
		</div>				<!-- column 4 end -->
	</div>				<!-- end row 1 -->
	<div class="row-fluid">	<!-- row 3 --> 
		<div class="col-md-12"> <!-- Column 1 -->
			@if (isset($mode))
			@else
				{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
				<a href="" class="biz-button colored-default" style="float:right" id="lnksubmit">
					Search
				</a>
			@endif    
			
		</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
	{{ Form::close() }}
	@endif

	
	@if (isset($purchaseorders))
		@include('purchaseorders.table', ['purchaseorders' => $purchaseorders, 'title' => isset($pendingPosTitle) ? $pendingPosTitle : ''])
	@endif
	<br/>
	@if (isset($quotations))
		@include('quotations.table', ['quotations' => $quotations, 'title' => isset($pendingQuotationsTitle) ? $pendingQuotationsTitle : ''])
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
			$( "#fromdate" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
			$( "#todate" ).datepicker({ 
				format: "d/m/yyyy",
				endDate: "0d",
				autoclose: true,
			});
				
		});
	</script>
@endpush 
