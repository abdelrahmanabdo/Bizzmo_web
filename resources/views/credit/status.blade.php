@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
<div class="row">
	<div class="col-sm-12">
		<h4>Credit status for {{ $company->companyname}}</h4>
	</div>
</div>
<div id="error-message" class="red" style="display: none">
	An error occured, please try again later.
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
			url: '/credit/company/' + companyId + '/partial-load/1',
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