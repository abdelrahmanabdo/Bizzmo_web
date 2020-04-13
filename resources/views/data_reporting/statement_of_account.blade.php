@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop

@section('content')
	@include('includes.account-summary-nav')
	@if(isset($title))	
	<div class="">
		{{-- <h3 class="bm-title">{{ $title }}</h3> --}}
		<br/>
	</div>
	@endif

	<div class="row row-fluid">
		<div class="col-md-12">
				<div class="text-center col-md-offset-4 col-md-4">
						<h3 id="error-message" class="bm-msg-header error" style="display: none">
							An error occured, please try again later.
						</h3>
				</div>
		</div>
	</div>
	<div id="loader" style="display: none;text-align: center;margin-top: 40px">
		<i class="fa fa-spinner fa-spin" style="font-size: 50px;color: #3f64a1;position: absolute;margin: auto;top: 0;bottom: 0;height: 40px"></i>
	</div>
	<div id="content" style="display: none"></div>
@stop

@push('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		loadSapData(<?= $company->id ?>)
	})

	function loadSapData(companyId) {
		$.ajax ({
			url: '/data-reporting/statement-of-account/company/' + companyId + '/partial-load/1',
			method: 'GET',
			cache: false,
			beforeSend: function(){
				$("#error-message").hide()
				$("#info-message").hide()
				$("#loader").show()
				$("#content").empty()
			},
			success: function(response){				
				$("#content").append(response);
				$("#content").show()
				$("#loader").hide()
			},
			error: function(err) {
				$("#loader").hide()
				$("#error-message").show()
			}
		})
	}
</script>
@endpush