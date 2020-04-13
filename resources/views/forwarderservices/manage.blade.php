@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
	<style>
		.po-history-table th, .po-history-table td {
			font-size: 11px;
		}
	</style>
@stop
@section('content')	
	<div>
	<div class="row bm-pg-header">
		<h2 class="bm-title">Services</h2>
	</div>
		@if (isset($mode))
			{{ Form::open(array('id' => 'frmManage', 'class' => 'form-horizontal co-form', 'files' => true)) }}
			@foreach ($services as $service)
				@php
					$selected = false;
				@endphp
				@foreach ($companyservices as $cservice)
					@if ($cservice->service_id == $service->id)
						@php
							$selected = true;
						@endphp
					@endif
				@endforeach
				<div class="form-group {{ isset($mode) ? 'form-group--view'  : 'required'}}"> <!-- companytype -->
					<div class="radio">
						<label class="checkbox">
							<input class="bm-checkbox" type="checkbox" name="service_id[]" id="service_id[]" value="{{$service->id}}" @if($selected) checked @endif >
							<span class="checkmark"></span>
							<span class="bm-sublabel">{{$service->name}}</span>
						</label>					
					</div>
				</div>
			@endforeach
			{{ Form::hidden('id', $company_id, array('id' => 'id', 'class' => 'form-control')) }}
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-default prev-step bm-btn')) }}
			{{ Form::close() }}
		@else
			@if (isset($companyservices))
				@foreach ($services as $service)
					@foreach ($companyservices as $cservice)
						@if ($cservice->service_id == $service->id)
							<p>{{$service->name}}</p>
						@endif
					@endforeach
				@endforeach
			@endif
		@endif
	</div>
@stop	
@push('scripts')	
	
@endpush