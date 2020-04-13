@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{!! Form::open(array('id' => 'frmManage')) !!}
	<div class="row">	<!-- row 1 -->
		<div class="col-sm-12"> <!-- column 1 -->
			@if ($notEligible)
			<div class="alert alert-danger">
				<p class="bg-danger"><strong>@lang('messages.eligcomp')</strong></p>
				<p class="bg-danger">@lang('messages.eligcompmsg')</p>				
			</div>
			@endif
		</div>
	</div>
	{{-- @if (isset($companies))
		<div class="row">	<!-- row 1 -->
			<div class="col-sm-12"> <!-- column 1 -->
				@if ($companies->count() == 0 )
					<div class="alert alert-danger">
						<p class="bg-danger"><strong>@lang('messages.eligcomp')</strong></p>
						<p class="bg-danger">@lang('messages.eligcompmsg')</p>				
					</div>
				@else
					<table id="mytable" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="no-sort" width="10%">
									&nbsp;
								</th>
								<th>Name</th>
								<th>Current credit limit</th>
								<th>Country</th>
								<th>Active</th>
							</tr>		
						</thead>
						<tbody>			
							  @foreach ($companies as $company)
								<tr>
									<td>
										@if ($actiontype == 'initial')
											@if ($company->active && $company->confirmed)
												@if (Gate::allows('cr_cr', $company->id) && $company->canrequestcredit)
													<a href="{!! url("/creditrequests/create/" . $company->id) !!}" role="button"><span class="align-tb-add add-icon" title="Create"></span></a>	
													&nbsp;
												@else
													@if (Gate::allows('cr_cr', $company->id) && $company->creditrequests->where('creditstatus_id', 2)->count() > 0)
														<a href="/creditrequests/view/{{ $company->creditrequests->where("creditstatus_id", 2)->first()->id }}"  role="button"><span class="view-icon" title="View"></span></a>	
													@else
														&nbsp;
													@endif
												@endif
											@endif
										@else
											@if ($company->active && $company->confirmed)
												@if (Gate::allows('cr_cr', $company->id) && $company->creditrequests->count() == 0)
													&nbsp;a
													&nbsp;
												@else
													@if (Gate::allows('cr_cr', $company->id) && $company->creditrequests->where('creditstatus_id', 2)->count() > 0)
														<a href="/creditrequests/view/{{ $company->creditrequests->where("creditstatus_id", 2)->first()->id }}"  role="button"><span class="view-icon" title="View"></span></a>	
													@else
														<a href="{!! url("/creditrequests/increase/" . $company->id) !!}" role="button"><span class="glyphicon glyphicon-arrow-up" title="Increase"></span></a>
													@endif
												@endif
											@endif
										@endif
									</td>
									<td> {!! $company->companyname !!} </td>
									<td align="right"> {!! number_format($company->creditlimit, 2, '.', ',') !!} </td>
									<td> {!! $company->country->countryname !!} </td>
									<td> @if ($company->active == 1) Yes @else No @endif </td>
								</tr>	
							  @endforeach			
						</tbody>
					</table>
				@endif
			</div> <!-- column 1 end -->	
		</div>				<!-- end row 1 -->
	@endif	--}}
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