@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{!! Form::open(array('id' => 'frmManage')) !!}	
	@if (isset($companies))
		<div class="row-fluid">	<!-- row 1 -->
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
										@if ($company->sapnumber == '')
											&nbsp;
										@else
											<a href="/credit/company/{{ $company->id }}"  role="button"><span class="glyphicon glyphicon-search" title="Credit status details"></span></a>	
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