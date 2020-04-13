@extends('layouts.app',['hideRightMenuAndExtend' => true])
<style type="text/css" media="all">
.table > tbody > tr > th, .table > tbody > tr > td {
	border-top: none !important;
}
.table > tbody > tr > th > h4, .table > tbody > tr > td {
	font-size: 14px;
}
</style>
@section('content')
{{ Form::open(array('id' => 'edit-account', 'class' => 'profile-form')) }}
<div class="row">
	<div class="col-xs-12">
		<h3 class="bm-title" style="margin-left:0">Edit Account</h3>
	</div>
	<div class="white-box col-sm-12">
		<div class="form-group col-xs-12">
			{{ Form::label('name', 'Name' ,['class' => 'control-label bm-label col-sm-3 col-xs-12']) }}
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				{{ Form::text('name', $user->name, ['id' => 'name', 'class' => 'form-control']) }}
				@if ($errors->has('name')) <p class="bg-danger">{{ $errors->first('name') }}</p> @endif
			</div>
		</div>
	
		<div class="form-group col-xs-12">
			{{ Form::label('title', 'Job title' ,['class' => 'control-label bm-label col-sm-3 col-xs-12']) }}
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				{{ Form::text('title', $user->title, ['id' => 'title', 'class' => 'form-control']) }}
				@if ($errors->has('title')) <p class="bg-danger">{{ $errors->first('title') }}</p> @endif
			</div>
		</div>
	
		<div class="form-group col-xs-12">
			{{ Form::label('phone', 'Mobile' ,['class' => 'control-label bm-label col-sm-3 col-xs-12']) }}
			<div class="col-lg-6 col-sm-9 col-xs-12 inp-container">
				{{ Form::text('phone', $user->phone() ? $user->phone()->phone : "", ['id' => 'phone', 'class' => 'form-control', 'placeholder' => '+00000000000000']) }}
				@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
			</div>
		</div>
	<div style="margin-bottom: 20px">
		<div class="col-xs-12">
			<button type="submit" class="biz-button colored-green" style="margin : 0px" role="button">
				{{-- <span class="glyphicon glyphicon-ok hidden-xs"></span> --}}
				<span class="xs">Save</span>				
			</button>
		</div>

		<div class="col-xs-12">
			@if(isset($message))
			<p class="green">{{ $message }}</p>
			@endif
		</div>
	</div>
</div>

</div>
{{ Form::close() }}	
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			var phone = document.getElementById("phone");
			if (phone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(phone);
				Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(phone);
			}
        });
    </script>
@endpush
