@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if ($showconditions)
	{{ Form::open(array('id' => 'frmManage')) }}
	@if (!$upcoming && !$pendingcredit)
	<div class="row">
		@if(isset($title))	
			<h2 class="bm-pg-title">{{ $title }}</h2>
			<br/>
		@endif
		<div class="col-md-3">  <!-- column 1 -->
			<div class="form-group"> <!-- from date -->  
				{{ Form::label('fromdate', 'From') }}
				{{ Form::text('fromdate', Input::get('fromdate'), array('id' => 'fromdate', 'class' => 'form-control')) }}			
				@if ($errors->has('fromdate')) <p class="bg-danger">{{ $errors->first('fromdate') }}</p> @endif
			</div> <!-- from date end -->  
		</div>					<!-- column 1 end -->
		<div class="col-md-3">  <!-- column 2 -->
			<div class="form-group"> <!-- to date -->  
				{{ Form::label('todate', 'To') }}
				{{ Form::text('todate', Input::get('todate'), array('id' => 'todate', 'class' => 'form-control')) }}			
				@if ($errors->has('todate')) <p class="bg-danger">{{ $errors->first('todate') }}</p> @endif
			</div> <!-- to date end -->  
		</div>					<!-- column 2 end -->
		<div class="col-md-3">  <!-- column 3 -->
			<div class="form-group"> <!-- company -->  
				{{ Form::label('company_id', 'Company') }}
				{{ Form::select('company_id', $companies, Input::get('company_id'),array('id' => 'company_id', 'class' => 'form-control bm-select'))}}		
				@if ($errors->has('company_id')) <p class="bg-danger">{{ $errors->first('company_id') }}</p> @endif
			</div> <!-- company end -->  
		</div>					<!-- column 3 end -->
		<div class="col-md-3">  <!-- column 3 -->
			<div class="form-group"> <!-- status -->  
				{{ Form::label('status_id', 'Status') }}
				{{ Form::select('status_id', $statuses, Input::get('status_id'),array('id' => 'status_id', 'class' => 'form-control bm-select'))}}		
				@if ($errors->has('status_id')) <p class="bg-danger">{{ $errors->first('status_id') }}</p> @endif
			</div> <!-- company end -->  
		</div>
	</div>					<!-- column 3 end -->
		<div class="row-fluid row">	<!-- row 3 --> 
			<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
				@if (isset($mode))
				@else
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden bm-btn')) }}
					<a href="" class="btn bm-btn" id="lnksubmit">
						Search
					</a>
				@endif    
				
			</div> <!-- Column 1 end -->
		</div> <!--row 3 end -->
	@endif
	{{ Form::close() }}
	@endif
	@if (isset($appointments))
		<div class="">	<!-- row 5 -->
		@include('calendar.table', ['appointments' => $appointments, 'title' => ''])
		</div>				<!-- end row 5 -->
	@endif
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});			
			$( "#fromdate" ).datepicker({ 
				format: "d/m/yyyy",
				startDate: "0d",
				autoclose: true,
			});
			$( "#todate" ).datepicker({ 
				format: "d/m/yyyy",
				startDate: "0d",
				autoclose: true,
			});
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
						$('#city_id').append($("<option></option>").attr("value", 0).text('All'));
						$.each(data, function(i, item) {
							$('#city_id').append($("<option></option>").attr("value", i).text(item));
						});
					}, // End of success function of ajax form
					error: function(output_string){				
						alert(jxhr.responseText);
					}
				}); //ajax call end
			}); // $("#country_id").change end
		});
	</script>
@endpush 