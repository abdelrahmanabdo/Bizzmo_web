@extends('layouts.app')
@section('title')

@stop
@section('styles')
	<style>

	</style>
@stop
@section('content')	
	{{ Form::open(array('id' => 'frmManage')) }}
		<input type="text" name="searchterm" id="searchterm" placeholder="Type to search users" autocomplete="off" >
		{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button')) }}
	{{ Form::close() }}	
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			
		});
	</script>
@endpush