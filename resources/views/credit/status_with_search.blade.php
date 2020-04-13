@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if(isset($title))	
	<div class="row">
		<h2 class="bm-pg-title">{{ $title }}</h2>
		<br/>
	</div>
	@endif
	{{ Form::open(array('id' => 'frmManage', 'class' => 'po-form')) }}
	<select class="form-control bm-select" id="search-companies"></select>
	<hr/>
	<div id="info-message">
		Please choose a company to show its credit status.
	</div>
	<div id="error-message" class="red" style="display: none">
	An error occured, please try again later.
	</div>
	<div id="loader" style="display: none;text-align: center;margin-top: 40px">
		<i class="fa fa-spinner fa-spin" style="font-size: 50px;color: #3f64a1;position: absolute;margin: auto;top: 0;bottom: 0;height: 40px"></i>
	</div>
	<div id="content" style="display: none"></div>
	<div id="changelimit" style="display: none">
		@if (Gate::allows('cr_ap'))
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="{{ url('/credit/check') }}" id="lnksubmit" title="Change Limit" role="button" class="btn bm-btn green">
				<span>Change limit</span>
			</a>
		@endif
	</div>
	{{ Form::hidden('company_id', old('company_id'), array('id' => 'company_id')) }}
	{{ Form::close() }}
@stop

@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#lnksubmit").bind('click', function(e) {
			e.preventDefault();
			$("#submit").click();
		});	
	});
	var serchCompanies = $("#search-companies");
	@if (isset($company))	
		var newOption = new Option("<?= $company->companyname ?>", "<?= $company->id ?>", true, true);
		$(serchCompanies).append(newOption).trigger('change');
	@endif

	serchCompanies.select2({
		placeholder: "Search for a company",
		ajax: {
			url: '/credit/companies/search/',
			dataType: 'json',
			processResults: function (data) {
				return {
					results:  $.map(data, function (item) {
						return {
							id: item.id,
							text: item.companyname
						}
					})
				}
			},
			cache: true
		}
	});

	// Redirect when option is clicked
	serchCompanies.on('select2:select', function (e) {
		var data = e.params.data;
		var companyId = data.id;
		$("#company_id").val(companyId);
		loadSapData(companyId);
	});

	function loadSapData(companyId) {
		$.ajax ({
			url: '/credit/company/' + companyId + '/partial-load',
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
				$("#changelimit").show()
			},
			error: function(err) {
				$("#loader").hide()
				$("#error-message").show()
			}
		})
	}
</script>
@endpush