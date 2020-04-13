@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if (isset($company)) 
		{{ Form::model($company, array('id' => 'frmManage')) }}
		{{ Form::hidden('id', $company->id, array('id' => 'id', 'class' => 'form-control')) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'files' => true)) }}
	@endif
	{{ Form::hidden('period', $start, array('id' => 'period')) }}
	{{ Form::hidden('nextprev', 0, array('id' => 'nextprev')) }}
	<div class="row">	<!-- row 1 -->		
		<div class="col-md-12">  <!-- column 1 -->
			<h4 class="tb-title bottom-space">Select a time slot for the credit visit appointment</h4>
			<table id="ownertable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						@for ($i = $start; $i < $start + 7; $i++)
							@php
								$date=date_create(date('Ymd'));
								date_add($date,date_interval_create_from_date_string($i . " day"));
							@endphp
							@if (date_format($date,"l") != 'Friday' && date_format($date,"l") != 'Saturday')	
								<th class="col-sm-2 tb-item-title"> {{ date_format($date,"j/n/Y") }} </th>
							@endif
						@endfor						
					</tr>		
				</thead>
				<tbody>
					@for ($j = 0; $j < 5; $j++)						
						<tr>
							@for ($i = $start; $i < $start + 7; $i++)
								@php
									$date=date_create(date('Ymd'));
									date_add($date,date_interval_create_from_date_string($i . " day"));
								@endphp
								@if (date_format($date,"l") != 'Friday' && date_format($date,"l") != 'Saturday')
									@php $busy = 0; @endphp
									@foreach ($appointments as $appointment)
										@if ( $appointment->date == date_format($date,'Y-m-d') && $appointment->timeslot_id - 1 == $j )
											@php 
												$busy = 1; 
												$currentappointment = $appointment;
											@endphp
										@endif
									@endforeach									
									@if ($busy == 1)										
										<!--<td class="{{ ($currentappointment->status_id == '1') ? 'bg-warning' : 'bg-success' }} "><small><a href="/calendar/view/{{ $currentappointment->id }}">{{ $currentappointment->description }}</a></small></td>-->
										<td class="{{ ($currentappointment->status_id == '1') ? 'bg-warning' : 'bg-success' }} "><small>Reserved</small></td>
									@else
										<td>
											<div class="radio tb-check">
												<label>
													<input class="bm-radio" type="radio" name="slot" value="{{ $i }}-{{ $j }}">
													<span class="bm-rd-checkmark"></span>
													<span class="bm-sublabel">{{ $timeslots[$j + 1] }}</span>
												</label>
											</div>										
										</td>
									@endif
								@endif
							@endfor
						</tr>						
					@endfor
				</tbody>
			</table>
			@if ($errors->has('slot')) <p class="bg-danger">{{ $errors->first('slot') }}</p> @endif
		</div>					<!-- column 1 end -->				
	</div>				<!-- end row 1 -->

	<div class="row">	<!-- row 8 --> 
		<div class="col-md-12"> <!-- Column 1 -->
		@if (isset($mode))
			@if (Gate::allows('co_cr'))
			<div class="col-xs-3"> <!-- Column 1 -->			
				<a href="{{ url("/companies/create") }}" class="btn btn-primary fixedw_button" role="button" title="Create"><span class="glyphicon glyphicon-plus"></span></a>						
			</div> <!-- Column 1 end -->
			@endif
			@if (Gate::allows('co_sc'))
			<div class="col-xs-3"> <!-- Column 2 -->
				<a href="{{ url("/companies") }}" class="btn btn-info fixedw_button" role="button" title="Search">Search</a>
			</div>
			@endif
			@if (Gate::allows('co_ch', $company->id))
			<div class="col-xs-3"> <!-- Column 3 -->
				<a href="{{ url("/companies/" . $company->id) }}" class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
			</div>@endif
			@if (Gate::allows('co_at', $company->id))
			<div class="col-xs-3"> <!-- Column 4 -->
				<a href="{{ url("/companies/attach/" . $company->id) }}" class="btn btn-success fixedw_button" role="button" title="Attach"><span class="glyphicon glyphicon-paperclip"></span></a>
			</div>@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<div class="col-xs-2"> <!-- Column 1 -->
				@if ($start == '0')
					&nbsp;
				@else
					<a href="" class="btn btn-info bm-btn" id="lnkprev" type="button" title="Prev week">Previous week</a>
				@endif
			</div> <!-- Column 1 end -->
			<div class="col-xs-2"> <!-- Column 1 -->
				@if ($start == '0')
					&nbsp;
				@else
					<a href="" class="btn btn-info bm-btn" id="lnkprevM" type="button" title="Prev month">Previous month</a>
				@endif
			</div> <!-- Column 1 end -->
			<div class="col-xs-2"> <!-- Column 2 -->
			</div>
			<div class="col-xs-2"> <!-- Column 2 -->
				<a href="" class="btn btn-info bm-btn" id="lnknext" type="button" title="Next week">Next week</a>
			</div> <!-- Column 2 end -->
			<div class="col-xs-2"> <!-- Column 2 -->
				<a href="" class="btn btn-info bm-btn" id="lnknextM" type="button" title="Next month">Next month</a>
			</div> <!-- Column 2 end -->
			<div class="col-xs-2"> <!-- Column 3 -->
				<a href="" class="btn btn-primary bm-btn green" id="lnksubmit" type="button" title="Save">
					Save
				</a>
			</div> <!-- Column 3 end -->
		@endif 	
		</div> <!-- Column 1 end -->
	</div> <!--row 8 end -->
	{{ Form::close() }}
	@if (isset($mode))
		@if ($company->active && $company->creditrequests->count() == 0)
			<div class="row">	<!-- row 9 -->
				<div class="col-md-10"> <!-- Column 1 -->
					<div class="alert alert-danger">
						<p class="bg-danger"><strong>Credit alert</strong></p>
						<p class="bg-danger">You did not apply for a credit line yet. Click <a href="/creditrequests/create/{!!$company->id!!}">here</a> to apply.</p>
					</div>
				</div> <!-- Column 1 end -->
			</div> <!--row 9 end -->
		@endif
	@endif
@stop	
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
			e.preventDefault();
			$("#nextprev").val(0);
		   	$("#submit").click();
			});
			
			$("#lnkprev").bind('click', function(e) {
				e.preventDefault();
				if((parseInt($("#period").val()) - 1) <1){
					$("#period").val(0);
				}else{
					$("#period").val(parseInt($("#period").val()) - 1);
				}
				$("#nextprev").val(1);
				$("#submit").click();
			});
			$("#lnknext").bind('click', function(e) {
				e.preventDefault();
				$("#period").val(parseInt($("#period").val()) + 1);
				$("#nextprev").val(1);
				$("#submit").click();
			});

			$("#lnkprevM").bind('click', function(e) {
				e.preventDefault();
				if((parseInt($("#period").val()) - 4) <1){
					$("#period").val(0);
				}else{
					$("#period").val(parseInt($("#period").val()) - 4);
				}
				$("#nextprev").val(1);
				$("#submit").click();
			});
			$("#lnknextM").bind('click', function(e) {
				e.preventDefault();
				$("#period").val(parseInt($("#period").val()) + 4);
				$("#nextprev").val(1);
				$("#submit").click();
			});
		});		
	</script>
@endpush