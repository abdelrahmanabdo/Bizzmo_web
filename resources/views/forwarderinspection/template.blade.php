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
		<h2 class="bm-pg-title">Inspections</h2>
		<div class="col-md-4">
			<a href="/forwarder/inspection/create/{{$company_id}}" id="lnksubmit" class="btn btn-info bm-btn"><span title="New Address">Create A New Inspection</span></a>
		</div>
	</div>
	<div class="row">
	<table id="listtable" class="table table-striped table-bordered table-hover table-condensed">
		<thead>
			<tr>
				<th>Name</th>
				<th class="no-sort" width="10%">
					&nbsp;
				</th>
			</tr>		
		</thead>
		<tbody>			
		@foreach ($templates as $template)
        <tr>
            <td>{{$template->name}}</td>
			<td>
                <a href="/forwarder/inspection/edit/{{$template->id}}" class="edit-icon" role="button" title="Edit"></a>							
            </td>	
        </tr>	
        @endforeach			
		</tbody>
	</table>
	</div>
@stop	
@push('scripts')	
	
@endpush