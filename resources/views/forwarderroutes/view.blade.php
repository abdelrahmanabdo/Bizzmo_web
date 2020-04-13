@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
@stop
@section('content')	
{{ Form::open(array('id' => 'frmManage', 'class' => 'shipping-add-form')) }}	
<div class="row bm-pg-header">
		<h2 class="bm-title">Routes</h2>
		<div class="col-md-4">
			<a href="/forwarder/route/create" id="lnksubmit" class="btn btn-info bm-btn"><span title="New Address">Create A New Route</span></a>
		</div>
	</div>
	<div class="row">
		<table id="listtable" class="table table-striped table-bordered table-hover table-condensed">
		<thead>
			<tr>
				<th>Start Country</th>
				<th>Start Port</th>
				<th>End Country</th>
				<th>End Port</th>
				<th class="no-sort" width="10%">
					&nbsp;
				</th>
			</tr>		
		</thead>
		<tbody>			
		@foreach ($routes as $route)
        <tr>
            <td>{{$route->startcode->country->countryname}}</td>
			<td>{{$route->startcode->PointName}}</td>
			<td>{{$route->endcode->country->countryname}}</td>
			<td>{{$route->endcode->PointName}}</td>
			<td>
                <a href="/forwarder/route/edit/{{$route->id}}" class="edit-icon" role="button" title="Edit"></a>							
            </td>	
        </tr>	
        @endforeach			
		</tbody>
	</table>
	</div>
@stop	
@push('scripts')	
	
@endpush