@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{{ Form::open(array('id' => 'frmManage')) }}
	<div class="row select-btns">
		{{-- <div class="col-sm-offset-2 col-sm-4">
			<a class="box text-center" href="/companies/create">
				<i class="fa fa-user fa-2x"></i>
				<h4>Buyer</h4>
			</a>
		</div>
		<div class="col-sm-4">
			<a class="box text-center" href="/vendors/create">
				<i class="fa fa-user-circle fa-2x"></i>
				<h4>Supplier</h4>
			</a>
		</div> --}}
		<div class="col-sm-offset-2 col-sm-4">
			<span class="button-checkbox">
		        <button type="button" class="btn btn-lg btn-block" data-color="primary">Buyer<i class="fa fa-user fa-1x btnChk-icon"></i></button>
		        <input type="checkbox" class="hidden" name="buyer" id="buyer" />
		    </span>
		</div>
		<div class="col-sm-4">
			<span class="button-checkbox">
		        <button type="button" class="btn btn-lg btn-block" data-color="primary">Supplier<i class="fa fa-user-circle fa-1x btnChk-icon"></i></button>
		        <input type="checkbox" class="hidden" name="supplier" id="supplier" />
		    </span>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn btn-success btn-block btn-lg disabled" id="lnksubmit">Go <i class="fa fa-arrow-right"></i></a>
		</div
	</div>
	{{ Form::close() }}
@stop
@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});
			
			$('#buyer').change(function() {
				if ($('#buyer').is(':checked') || $('#supplier').is(':checked')) {
					$('#lnksubmit').removeClass('disabled');
				} else {
					$('#lnksubmit').addClass('disabled');
				}
			});
			
		});
	</script>
@endpush 