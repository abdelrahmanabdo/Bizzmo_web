@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	<link href="{{ asset('css/mine.css') }}" rel="stylesheet">
	@foreach ($companies as $company)
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">  <!-- Column 1 -->
				<div class="card" style="margin-top:20;">
					<div class="row">
					<div class="col-sm-6">
						<h3>{{ $company->companyname }}</h3><br>
					</div>					
					<div class="col-sm-6">
						<br>
						<a href="" class="badge-primary pull-right" id="lnksubmit">
							<span class="glyphicon glyphicon-eye-open" title="View">&nbsp</span>
						</a>
						&nbsp;
						<a href="" class="badge-primary pull-right" id="lnksubmit">
							<span class="glyphicon glyphicon-pencil orange" title="View">&nbsp</span>
						</a>
					</div>
					</div>
					<div class="row">
					<div class="col-sm-6">
						Address: {{ $company->address }}<br>
						City: {{ $company->address }}<br>
						Country: {{ $company->address }}<br>
					</div>
					<div class="col-sm-6">
						Address: {{ $company->address }}<br>
						City: {{ $company->address }}<br>
						Country: {{ $company->address }}<br>
					</div>
					</div>
				</div>
			</div>					<!-- Column 1 end -->
		</div>
	@endforeach
@stop	
