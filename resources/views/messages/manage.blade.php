@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')	
	
	
		<div class="row">	<!-- row 1 -->		
			<chat-box></chat-box>
		</div>				<!-- end row 1 -->
		
	
@stop	
@push('scripts')	
	
@endpush