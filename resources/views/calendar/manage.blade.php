@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
	<style>
		.bigbottommargin {
			margin-bottom: 30px;
		}
	</style>
@stop
@section('content')
	@if (isset($appointment))
		{{ Form::model($appointment, array('id' => 'frmManage', 'url' => 'calendar/completec/' . $appointment->id, 'class' => 'appt-form')) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'files' => true, 'class' => 'appt-form')) }}
	@endif

@if (isset($mode))
	@if ((($mode == 'l' || $mode == 'm') && $appointment->status_id == 8) || ($appointment->status_id == 3 && \Gate::allows('cr_ap')))
	<ul class="nav nav-tabs pointer">
		<li class="active pointer-shape--without-left">
			<a style="left: 5px" href="#creditAssessment">
				Credit Assessment
				@if ($errors->has('prepared_by') || $errors->has('approved_by') ||$errors->has('date_of_assessment'))
					&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span>
				@endif
			</a>
		</li>
		<li class="pointer-shape">
			<a style="left: 12px" href="#excutiveSummary">
				Executive Summary
				@if ($errors->has('company_background') || $errors->has('companyName*') || $errors->has('key_financials_developments') ||$errors->has('key_risks') || $errors->has('mitigating_factors') || $errors->has('companies_count') || $errors->has('company.*'))
					&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span>
				@endif
			</a>
		</li>
		<li class="pointer-shape--without-right">
			<a style="left: 10px" href="#tradingHistory">
				Trading History
				@if ($errors->has('highest_outstanding_blance') || $errors->has('sales_*') || $errors->has('payments_*') || $errors->has('scores_*'))
					&nbsp;<span class="red glyphicon glyphicon-exclamation-sign"></span>
				@endif
			</a>
		</li>
	</ul>
	@else
		@if ($appointment->company_id != 0)
		<div class="row">	<!-- row 1 -->
			<div class="col-md-4">  <!-- Cclumn 1 -->
				<div class="form-group"> <!-- Company name -->
					{{ Form::label('companyname', 'Company name' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->company->companyname }}</p>
				</div> <!-- Company name -->
			</div>					<!-- column 1 end -->
			<div class="col-md-4">  <!-- column 2 -->
				<div class="form-group"> <!-- address -->
					{{ Form::label('address', 'Address' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->company->address }}</p>
				</div> <!-- address end -->
			</div>					<!-- column 2 end -->
			<div class="col-md-4">  <!-- column 3 -->
				<div class="form-group"> <!-- country -->
					{{ Form::label('country', 'Country' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->company->country->countryname }}</p>
				</div> <!-- country end -->
			</div>					<!-- column 3 end -->
		</div>				<!-- end row 1 -->
		@endif

		<div class="row">	<!-- row 2 -->
			<div class="col-md-4">  <!-- Cclumn 1 -->
				<div class="form-group"> <!-- date -->
					{{ Form::label('date', 'Date' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->date }}</p>
				</div> <!-- Company name -->
			</div>					<!-- column 1 end -->
			<div class="col-md-4">  <!-- column 2 -->
				<div class="form-group"> <!-- timeslot -->
					{{ Form::label('timeslot', 'Time' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->timeslot->name }}</p>
				</div> <!-- timeslot end -->
			</div>					<!-- column 2 end -->
			<div class="col-md-4">  <!-- column 3 -->
				<div class="form-group"> <!-- status -->
					{{ Form::label('status', 'Status' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->status->name }}</p>
				</div> <!-- status end -->
			</div>					<!-- column 3 end -->
		</div>				<!-- end row 2 -->
		<div class="row">	<!-- row 3 -->
			<div class="col-md-12">  <!-- column 3 -->
				<div class="form-group"> <!-- description -->
					{{ Form::label('description', 'Description' ,['class' => 'label-view']) }}
					<p class='form-control-static'>{{ $appointment->description }}</p>
				</div> <!-- description end -->
			</div>					<!-- column 3 end -->
		</div>				<!-- end row 3 -->
		<hr/>
	@endif
@endif
@if (isset($mode))
	@if ((($mode == 'l' || $mode == 'm') && $appointment->status_id == 8) || ($appointment->status_id == 3 && \Gate::allows('cr_ap')))
	<div class="tab-content pointer">
		<div id="creditAssessment" class="tab-pane fade in active">
			@if ($appointment->company_id != 0)
			<div class="row">	<!-- row 1 -->
				<div class="col-md-5">  <!-- Cclumn 1 -->
					<div class="form-group"> <!-- Company name -->
						{{ Form::label('companyname', 'Company name' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->company->companyname }}</p>
					</div> <!-- Company name -->
				</div>					<!-- column 1 end -->
				<div class="col-md-5">  <!-- column 2 -->
					<div class="form-group"> <!-- address -->
						{{ Form::label('address', 'Address' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->company->address }}</p>
					</div> <!-- address end -->
				</div>					<!-- column 2 end -->
				<div class="col-md-2">  <!-- column 3 -->
					<div class="form-group"> <!-- country -->
						{{ Form::label('country', 'Country' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->company->country->countryname }}</p>
					</div> <!-- country end -->
				</div>					<!-- column 3 end -->
			</div>				<!-- end row 1 -->
			@endif

			<div class="row">	<!-- row 2 -->
				<div class="col-md-4">  <!-- Cclumn 1 -->
					<div class="form-group"> <!-- date -->
						{{ Form::label('date', 'Date' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->date }}</p>
					</div> <!-- Company name -->
				</div>					<!-- column 1 end -->
				<div class="col-md-4">  <!-- column 2 -->
					<div class="form-group"> <!-- timeslot -->
						{{ Form::label('timeslot', 'Time' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->timeslot->name }}</p>
					</div> <!-- timeslot end -->
				</div>					<!-- column 2 end -->
				<div class="col-md-4">  <!-- column 3 -->
					<div class="form-group"> <!-- status -->
						{{ Form::label('status', 'Status' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->status->name }}</p>
					</div> <!-- status end -->
				</div>					<!-- column 3 end -->
			</div>				<!-- end row 2 -->
			<div class="row">	<!-- row 3 -->
				<div class="col-md-12">  <!-- column 3 -->
					<div class="form-group"> <!-- description -->
						{{ Form::label('description', 'Description' ,['class' => 'label-view']) }}
						<p class='form-control-static'>{{ $appointment->description }}</p>
					</div> <!-- description end -->
				</div>					<!-- column 3 end -->
			</div>				<!-- end row 3 -->
			<hr/>

			<!-- Credit Assessment/Site Visit -->
			<div class="row">
				<div class="col-md-12">
					<h4 class="bm-title">Credit Assessment/Site Visit</h4>
				</div>
				<div class="col-md-12">  <!-- Cclumn 1 -->
					<div class="col-md-3">
						{{ Form::label('prepared_by', 'Prepared By') }}
					</div>
					<div class="col-md-6">
						<div class="form-group"> <!-- date -->
						@if($mode == 'v' && isset($creditAssessment))
							<p class='form-control-static'>{{ $creditAssessment->prepared_by }}</p>
						@else
							{{ Form::text('prepared_by', Input::old('prepared_by'), array('id' => 'prepared_by', 'class' => 'form-control')) }}
							@if ($errors->has('prepared_by'))
							<p class="bg-danger">{{ $errors->first('prepared_by') }}</p>
							@endif
						@endif
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-3">
						{{ Form::label('approved_by', 'Approved By') }}
					</div>
					<div class="col-md-6">
						<div class="form-group">
						@if($mode == 'v' && isset($creditAssessment))
						<p class='form-control-static'>{{ $creditAssessment->approved_by }}</p>
						@else
							{{ Form::text('approved_by', Input::old('approved_by'), array('id' => 'approved_by', 'class' => 'form-control')) }}
							@if ($errors->has('approved_by'))
							<p class="bg-danger">{{ $errors->first('approved_by') }}</p>
							@endif
						@endif
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-3">
						{{ Form::label('date_of_assessment', 'Date Of Assessment') }}
					</div>
					<div class="col-md-6">
						<div class="inp-container flex-container">
							@if($mode == 'v' && isset($creditAssessment))
							<p class='form-control-static'>{{ $creditAssessment->date_of_assessment }}</p>
							@else
								{{ Form::text('date_of_assessment', Input::old('date_of_assessment'), array('id' => 'date_of_assessment', 'class' => 'form-control input-with-icon')) }}
								<span class="cal-icon" alt="cal icon"></span>
							@endif
						</div>
							@if ($errors->has('date_of_assessment'))
							<p class="bg-danger">{{ $errors->first('date_of_assessment') }}</p>
							@endif
					</div>
				</div>
			</div>
		</div>

		<div id="excutiveSummary" class="tab-pane fade">
			<!-- Executive Summary -->
			<div class="row">
				<div class="col-md-12">
					<h3 class="bm-heading">Executive Summary</h3>
				</div>
				<div class="col-md-12">  <!-- Column 1 -->
					<div class="col-md-3">
						{{ Form::label('company_background', 'Company Background') }}
					</div>
					<div class="col-md-6">
						<div class="bigbottommargin">
							@if($mode == 'v' && isset($creditAssessment))
							<div class='form-control-static'>
								{!! html_entity_decode($creditAssessment->company_background) !!}
							</div>
							@else
							<div id="pell_company_background"></div>
								{{ Form::textarea('company_background', Input::old('company_background'), array('id' => 'company_background', 'class' => 'hidden')) }}
								@if ($errors->has('company_background'))
								<p class="bg-danger">{{ $errors->first('company_background') }}</p>
								@endif
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-3">
						{{ Form::label('key_financials_developments', 'Key Financials Developments') }}
					</div>
					<div class="col-md-6">
						<div class="bigbottommargin">
							@if($mode == 'v' && isset($creditAssessment))
							<div class='form-control-static'>
								{!! html_entity_decode($creditAssessment->key_financials_developments) !!}
							</div>
							@else
								<div id="pell_key_financials_developments"></div>
								{{ Form::textarea('key_financials_developments', Input::old('key_financials_developments'), array('id' => 'key_financials_developments', 'class' => 'hidden')) }}
								@if ($errors->has('key_financials_developments'))
								<p class="bg-danger">{{ $errors->first('key_financials_developments') }}</p> @endif
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-3">
						{{ Form::label('key_risks', 'Key Risks') }}
					</div>
					<div class="col-md-6">
						<div class="bigbottommargin">
							@if($mode == 'v' && isset($creditAssessment))
							<div class='form-control-static'>
								{!! html_entity_decode($creditAssessment->key_risks) !!}
							</div>
							@else
								<div id="pell_key_risks"></div>
								{{ Form::textarea('key_risks', Input::old('key_risks'), array('id' => 'key_risks', 'class' => 'hidden')) }}
								@if ($errors->has('key_risks'))
								<p class="bg-danger">{{ $errors->first('key_risks') }}</p>
								@endif
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-3">
						{{ Form::label('mitigating_factors', 'Mitigating Factors') }}
					</div>
					<div class="col-md-6">
						<div class="bigbottommargin">
							@if($mode == 'v' && isset($creditAssessment))
							<div class='form-control-static'>
								{!! html_entity_decode($creditAssessment->mitigating_factors) !!}
							</div>
							@else
								<div id="pell_mitigating_factors"></div>
								{{ Form::textarea('mitigating_factors', Input::old('mitigating_factors'), array('id' => 'mitigating_factors', 'class' => 'hidden')) }}
								@if ($errors->has('mitigating_factors'))
								<p class="bg-danger">{{ $errors->first('mitigating_factors') }}</p>
								@endif
							@endif
						</div>
					</div>
				</div>
			</div>
			<!-- Affiliated Entities -->
			<div class="row  {{ ($mode == 'v' && isset($creditAssessmentCompanies)) ? '' : ''}}" >
				<div class="col-md-12">
					<h4 class="bm-title">Affiliated Entities</h4>
				</div>
				<div class="col-md-12">
					<table id="companies-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								@if($mode != 'v')
								<th class="col-md-1 no-sort" width="10%">
									<a href="" id="lnkAddCompany" role="button" class="add-icon" title="Add company"></a>
								</th>
								@endif
								<th class="col-md-5 bm-title no-sort">Company name</th>
								<th class="col-md-2 bm-title no-sort">Type</th>
							</tr>
						</thead>
						<tbody>
							@php
								$companiesCount = 0;
							@endphp
							@if (old('companyName'))
								@php
									$i = 0;
								@endphp
								@foreach (old('companyName') as $name)
									<tr>
										<td>
											<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDel"></a>
										</td>
										<td>
											{{ Form::text('companyName[]', $name, array('id' => 'companyName[]', 'class' => 'form-control')) }}
											@if ($errors->has('companyName.' . $i)) <p class="bg-danger">{{ $errors->first('companyName.' . $i) }}</p> @endif
										</td>
										<td>
											{{ Form::select('companyType[]', $companyRelationTypes, Input::old('country_id')[$i], array('id' => 'companyType[]', 'class' => 'form-control')) }}
											@if ($errors->has('companyType.' . $i)) <p class="bg-danger">{{ $errors->first('companyType.' . $i) }}</p> @endif
										</td>
									</tr>
									@php
									$i++;$companiesCount++;
									@endphp
								@endforeach
							@endif
							@if($mode == 'v' && isset($creditAssessmentComapnies))
								@foreach ($creditAssessmentComapnies as $company)
								<tr>
									<td>{{ $company->company_name }}</td>
									<td>{{ $company->companyrelationtype->name }}</td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>
					@if($mode != 'v')
						<input type="hidden" name="companies_count" id="companies_count" value="{{$companiesCount}}">
						@if ($errors->has('companies_count')) <p class="bg-danger">{{ $errors->first('companies_count') }}</p> @endif
					@endif
				</div>
			</div>
		</div>
		<div id="tradingHistory" class="tab-pane fade">
			<!-- Trading History with Bizzmo -->
			<div class="row">
				<div class="col-md-12">
					<h3 class="bm-heading bottom-space">Trading History with Bizzmo</h3>
				</div>

				<div class="col-md-12">
					<table width="100%" id="trading_history_tpl" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="bm-title" width="33%">Last 8 Quarters</th>
								<th class="bm-title" width="33%">Sales</th>
								<th class="bm-title" width="33%">Payments</th>
							</tr>
						</thead>
						<tbody>
							@if($mode != 'v')
								@for ($i = 0; $i < 8; $i++)
								<tr>
									<td>
										{{ $quarters[$i] }}
										<input type="hidden" name="quarter[]" id="quarter" value="{{ $quarters[$i] }}">
									</td>
									<td>
										{{ Form::text('sales_' .$i, old('sales_' . $i), array('id' => 'sales_' .$i, 'class' => 'form-control')) }}
										@if ($errors->has('sales_' . $i)) <p class="bg-danger">{{ $errors->first('sales_' . $i) }}</p> @endif
									</td>
									<td>
										{{ Form::text('payments_' .$i, old('payments_' . $i), array('id' => 'payments_' .$i, 'class' => 'form-control')) }}
										@if ($errors->has('payments_' . $i)) <p class="bg-danger">{{ $errors->first('payments_' . $i) }}</p> @endif
									</td>
								</tr>
								@endfor
							@endif
							@if($mode == 'v' && isset($tradingHistoryRecords))
								@foreach ($tradingHistoryRecords as $record)
								<tr>
									<td>{{ $record->quarter }}</td>
									<td>{{ $record->sales }}</td>
									<td>{{ $record->payments }}</td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>
					@if($mode != 'v')
						<input type="hidden" name="sales" id="sales">
						@if ($errors->has('sales')) <p class="bg-danger">{{ $errors->first('sales') }}</p> @endif

						<input type="hidden" name="payments" id="payments">
						@if ($errors->has('payments')) <p class="bg-danger">{{ $errors->first('payments') }}</p> @endif
					@endif
				</div>
			</div><br/>

			<!-- Highest Outstanding Balance in Last 12 Months -->
			<div class="row">
				<div class="col-md-12">  <!-- Cclumn 1 -->
					<div class="col-md-3">
						{{ Form::label('highest_outstanding_blance', 'Highest Outstanding Balance in Last 12 Months') }}
					</div>
					<div class="col-md-6">
						@if($mode == 'v' && isset($creditAssessment))
							<p class='form-control-static'>{{ $creditAssessment->heighest_balance }}</p>
						@endif
						@if($mode != 'v')
						<div class="form-group"> <!-- date -->
							{{ Form::text('highest_outstanding_blance', Input::old('highest_outstanding_blance'), array('id' => 'highest_outstanding_blance', 'class' => 'form-control')) }}
							@if ($errors->has('highest_outstanding_blance')) <p class="bg-danger">{{ $errors->first('highest_outstanding_blance') }}</p> @endif
						</div>
						@endif
					</div>
				</div>
			</div>

			<!-- Score Card -->
			<div class="row">
				<div class="col-md-12">
					<h4 class="bm-heading bottom-space">Score Card</h4>
				</div>

				<div class="col-md-12">
					<table width="100%" id="trading_history_tpl" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="bm-title" width="70%">Information</th>
								<th class="bm-title" width="10%">Weight</th>
								<th class="bm-title" width="10%">Score</th>
								<th class="bm-title" width="10%">Results</th>
							</tr>
						</thead>
						<tbody>
							@php
								$i = 0;
							@endphp
							@if($mode != 'v')
								@foreach ($scoreFactors as $scoreFactor)
								<tr>
									<td>{{ $scoreFactor->name }}</td>
									<td>{{ $scoreFactor->weight }}</td>
									<td>
										{{ Form::hidden('factor_weight_' .$i, $scoreFactor->weight, array('id' => 'factor_weight_' .$i, 'class' => 'form-control')) }}
										{{ Form::hidden('score_factor_' .$i, $scoreFactor->id, array('id' => 'score_factor_' .$i, 'class' => 'form-control')) }}
										{{ Form::select('scores_' .$i, $scores, old('scores_' . $i), array('id' => 'scores_' .$i, 'class' => 'form-control bm-select')) }}
										@if ($errors->has('scores_' . $i)) <p class="bg-danger">{{ $errors->first('scores_' . $i) }}</p> @endif
									</td>
									<td><span id="results_{{$i}}"></span></td>
								</tr>
								@php
									$i++;
								@endphp
								@endforeach
							@endif
							@if($mode == 'v' && isset($scores))
							@php $accResult = 0; @endphp
								@foreach($scores as $score_card)
								<tr>
									@php
										$result = round($score_card->weight * $score_card->score->value, 2);
										$accResult += $result;
									@endphp
									<td>{{ $score_card->factor->name }}</td>
									<td>{{ $score_card->weight }}</td>
									<td>{{ $score_card->score->value }}</td>
									<td>{{ $result }}</td>
								</tr>
								@endforeach
							@php $accResult = round($accResult, 1) @endphp
							@endif
						</tbody>
						<footer>
							<tr>
								<td colspan="3" class="text-center bm-title">Score</td>
								<td>
									@if($mode == 'v' && isset($accResult))
										{{ $accResult }}
									@elseif($mode != 'v')
									<span id="total_score"></span>
									@endif
								</td>
							</tr>
						</footer>
					</table>
				</div>
			</div><br/>
		</div>
			@endif
	@endif
	<div class="row">	<!-- row 4 -->
		<div class="col-md-12 text-center btns-container"> <!-- Column 1 -->
		@if (isset($mode))
			@if (Gate::allows('cr_ap') || Gate::allows('cr_of'))
				@if ($appointment->status_id == 1)
						<a href="{{ url('/calendar/acceptc') . '/' . $appointment->id }}" class="btn btn-success fixedw_button bm-btn green" role="button" title="Accept">Accept</a>
						<a href="{{ url('/calendar/cancelc') . '/' . $appointment->id }}" class="btn btn-danger fixedw_button bm-btn red" role="button" title="Cancel">Cancel</a>
					@elseif (($mode == 'l' || $mode == 'm') && $appointment->status_id == 8)
						<a href="#" class="btn btn-info fixedw_button bm-btn hidden" id="lnkprev" type="button" title="Prev">Previous</a>
						<a href="#" class="btn btn-info fixedw_button bm-btn hidden" id="lnknext" type="button" title="Next">Next</a>
						<a href="{{ url('/calendar/cancelc') . '/' . $appointment->id }}" class="btn btn-danger fixedw_button bm-btn red" id="lnkcancel" role="button" title="Cancel Appointment">Cancel</a>
						<a href="" class="btn btn-success fixedw_button bm-btn green" id="lnkcomplete" role="button" title="Complete">Complete</a>
						{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden bm-btn green')) }}
					@elseif ($appointment->status_id == 17)
						<a href="{{ url('/calendar/unblockc') . '/' . $appointment->id }}" class="btn btn-danger fixedw_button bm-btn red" role="button" title="Unblock">Unblock</a>
					@endif
			@else
				@if (Gate::allows('cr_sc'))
					@if ($appointment->status_id == 1 || $appointment->status_id == 8)
					<a href="{{ url('/calendar/rejectc') . '/' . $appointment->id }}" class="btn btn-danger fixedw_button bm-btn red" role="button" title="Reject">
						Reject
					</a>
					@endif
				@endif
			@endif
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden bm-btn green')) }}
			<div class="col-xs-3"> <!-- Column 1 -->
				@if ($start == '0')
					&nbsp;
				@else
					<a href="" class="btn btn-info bm-btn fixedw_button" id="btnprev" type="button" title="Prev week">Previous week</a>
				@endif
			</div> <!-- Column 1 end -->
			<div class="col-xs-3"> <!-- Column 2 -->
				<a href="" class="btn btn-info bm-btn" id="btnnext" type="button" title="Next week">Next week</a>
			</div> <!-- Column 2 end -->
			<div class="col-xs-3"> <!-- Column 3 -->
				<a href="" class="btn btn-primary bm-btn green fixedw_button" id="lnksubmit" type="button" title="Save">
					Save
				</a>
			</div> <!-- Column 3 end -->
		@endif
		</div> <!-- Column 1 end -->
	</div> <!--row 4 end -->
	{{ Form::close() }}
@stop
@push('scripts')
  <script src="https://unpkg.com/pell@1.0.4/dist/pell.min.js"></script>
	<script type="text/javascript">
		var pellActions = ['bold', 'underline', 'italic', 'strikethrough', 'heading1',
					'heading2', 'paragraph', 'quote', 'olist', 'ulist'];
		$(document).ready(function() {
			// text editor (rtf)
			initPell('company_background');
			initPell('key_financials_developments');
			initPell('key_risks');
			initPell('mitigating_factors');

			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#nextprev").val(0);
				$("#submit").click();
			});

			$("#lnkcomplete").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});

			$("#btnprev").bind('click', function(e) {
				e.preventDefault();
				$("#period").val(parseInt($("#period").val()) - 1);
				$("#nextprev").val(1);
				$("#submit").click();
			});
			$("#btnnext").bind('click', function(e) {
				e.preventDefault();
				$("#period").val(parseInt($("#period").val()) + 1);
				$("#nextprev").val(1);
				$("#submit").click();
			});

			$(".cal-icon").on('click', function () {
				$("#date_of_assessment").focus()
			})

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

			nextprev();
			$('#lnknext').click(function(){
			  $('.nav-tabs > .active').next('li').find('a').trigger('click');
			});

			  $('#lnkprev').click(function(){
			  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
			});

			@if (isset($companyRelationTypes))
				var selectCompanyRelation = `<?php echo Form::select('companyType[]', $companyRelationTypes, $companyRelationTypes[1], array('class' => 'form-control bm-select')) ?>`;
			@endif


			$("#lnkAddCompany").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('companies-table');
				var rowLength = table.rows.length;
				var row = '<tr>';
				row = row + '<td>';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelOwner" type="button"></a>';
				row = row + '</td>';
				row = row + '<td><input name="companyName[]" type="text" class="form-control"></td>';
				row = row + '<td>';
				row = row + selectCompanyRelation;
				row = row + '</td>';
				@php
					if (isset($busreflengths)) {
						foreach ($busreflengths as $busreflength) {
							echo "row = row + '<option value=" . $busreflength->id . ">" . $busreflength->name . "</option>';";
						}
					}
				@endphp
				row = row + '</td>';
				row = row + '</tr>';
				$('#companies-table').append(row);
				$("#companies_count").val(parseInt($("#companies_count").val()) + 1);
			});
		});

		function DelRow(lnk) {
			var tr = lnk.parentNode.parentNode;
			tr.remove();
			$("#companies_count").val(parseInt($("#companies_count").val()) - 1);
		}

		function initPell(target) {
			var pellTarget = $('#pell_' + target);

			if(pellTarget.length > 0) {
				var targetTextArea = $('#' + target);
				pell.init({
				  element: document.getElementById('pell_' + target),
				  onChange: function(html) {
				  	targetTextArea.html(html)
				  },
				  actions: pellActions,
				});
				// populate text editor with text area html (if validation errs happen, user can see old value)
				pellTarget.find('.pell-content').html(targetTextArea.text());
			}
		}
		// Updated calculated coulmn
		var scores = $('[id^=scores_]')
		var weights = $('[id^=factor_weight_]')
		var results = $('[id^=results_]')
		scores.on("change", function () {
			var total = 0;
			results.each(function(index, item) {
				var score = parseFloat($(scores.get(index)).children("option:selected").text());
				var weight = parseFloat($(weights.get(index)).val());
				var result = score * weight;
				$(this).text(result.toPrecision(2))
				total += parseFloat(result.toPrecision(2));//Update total score
			})

			$("#total_score").text(parseFloat(total.toPrecision(2)))
		})

		$( "#date_of_assessment" ).datepicker({
			format: "d/m/yyyy",
			autoclose: true
		});
		function nextprev () {
			mytabs = $(".pointer-shape, .pointer-shape--without-left, .pointer-shape--without-right");
			$("#lnkcomplete").addClass('hidden');
			$("#lnkcancel").addClass('hidden');
			mytabs.each(function (index,item) {
				if ($(item).is('.active')) {
					//console.log(item);
					console.log(index);
					if (index == 0) {
						$("#lnkprev").addClass('hidden');
						$("#lnknext").removeClass('hidden');
						$("#lnkcomplete").addClass('hidden');
						$("#lnkcancel").addClass('hidden');
					} else if (index == mytabs.length - 1) {
						$("#lnkprev").removeClass('hidden');
						$("#lnknext").addClass('hidden');
						$("#lnkcomplete").removeClass('hidden');					
						$("#lnkcancel").removeClass('hidden');
					} else {
						$("#lnkprev").removeClass('hidden');
						$("#lnknext").removeClass('hidden');
						$("#lnkcomplete").addClass('hidden');
						$("#lnkcancel").addClass('hidden');
					}
				}
			});
			//console.log(mytabs.length);
		}
	</script>
@endpush