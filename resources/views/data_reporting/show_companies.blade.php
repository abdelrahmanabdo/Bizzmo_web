@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if(isset($title))	
	<div class="row">
		<h2 class="bm-title">{{ $title }}</h2>
		<br/>
	</div>
	@endif
	@if (!isset($companies))
		<p>You don't have companies yet</p>
	@else
		<div class="row-fluid">
		<table id="mytable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Country</th>
				<th>Credit limit</th>
				@if (Gate::allows('pt_as'))
					<th>Payment terms</th>
				@endif
				<th>Active</th>
				<th class="no-sort" width="17%">
				</th>
			</tr>		
		</thead>
		<tbody>			
			  @foreach ($companies as $company)
				<tr>
					<td> {{ $company->companyname }} </td>
					<td> {{ $company->companytype->name }} </td>					
					<td> {{ $company->country->countryname }} </td>
					<td align="right"> {{ number_format($company->creditlimit, 2, '.', ',') }} </td>
					@if (Gate::allows('pt_as'))
						<td>
							@if ($company->paymentterms->count() == 0)
								&nbsp;
							@else
								@foreach ($company->paymentterms as $paymentterm)
								{{ $paymentterm->name }}<br>
								@endforeach
							@endif
						</td>
					@endif
					<td> @if ($company->active) Yes @else No @endif </td>
					<td>
						<a href="{{ url('/data-reporting/outstanding/company/' . $company->id) }}" role="button"><span class="view-icon" title="Show report"></span></a>
					</td>
				</tr>	
			  @endforeach			
		</tbody>
		</table>
		</div>			
	@endif
@stop
