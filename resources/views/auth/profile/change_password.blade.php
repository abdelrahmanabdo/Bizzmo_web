@extends('layouts.app')
<style type="text/css" media="all">
.table > tbody > tr > th, .table > tbody > tr > td {
	border-top: none !important;
}
.table > tbody > tr > th > h4, .table > tbody > tr > td {
	font-size: 14px;
}
</style>
@section('content')
{{ Form::open(array('id' => 'change-password', 'class' => 'profile-form')) }}
<div class="row">
	<div class="col-xs-12" style="margin-bottom: 20px">
		<h2 class="bm-pg-title" style="margin-left:0">Change Password</h2>
	</div>
	<div>
		<div class="form-group col-xs-12">
			{{ Form::label('oldPassword', 'Old password' ,['class' => 'control-label bm-label col-sm-3 col-xs-12']) }}
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				{{ Form::password('oldPassword', ['id' => 'oldPassword', 'class' => 'form-control']) }}
				@if ($errors->has('oldPassword')) <p class="bg-danger">{{ $errors->first('oldPassword') }}</p> @endif
			</div>
		</div>
	
		<div class="form-group col-xs-12">
			{{ Form::label('newPassword', 'New password' ,['class' => 'control-label bm-label col-sm-3 col-xs-12']) }}
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				{{ Form::password('newPassword', ['id' => 'newPassword', 'class' => 'form-control']) }}
				@if ($errors->has('newPassword')) <p class="bg-danger">{{ $errors->first('newPassword') }}</p> @endif
			</div>
		</div>
	
		<div class="form-group col-xs-12">
			{{ Form::label('newPassword_confirmation', 'Confirm password' ,['class' => 'control-label bm-label col-sm-3 col-xs-12']) }}
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				{{ Form::password('newPassword_confirmation', ['id' => 'newPassword_confirmation', 'class' => 'form-control']) }}
				@if ($errors->has('newPassword_confirmation')) <p class="bg-danger">{{ $errors->first('newPassword_confirmation') }}</p> @endif
			</div>
		</div>
	</div>
	<div style="margin-bottom: 20px">
		<div class="col-xs-12">
			<button type="submit" class="btn bm-btn green fixedw_button" role="button">
				<span class="glyphicon glyphicon-ok hidden-xs"></span>
				<span class="visible-xs">Save</span>				
			</button>
		</div>

		<div class="col-xs-12">
			@if(isset($message))
			<p class="green">{{ $message }}</p>
			@endif
		</div>
	</div>
</div>
{{ Form::close() }}	
@stop
