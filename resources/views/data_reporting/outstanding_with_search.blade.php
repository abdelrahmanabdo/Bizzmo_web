@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@if(isset($title))	
	<div class="row">
		<h2 class="bm-title">{{ $title }}</h2>
		<br/>
	</div>
	@endif
	
	<select class="form-control bm-select" id="search-companies"></select>
	<hr/>
	<div id="info-message">
		Please choose a company to show its outstanding payments.
	</div>
	<div class="row row-fluid">
		<div class="col-md-12">
				<div class="text-center col-md-offset-4 col-md-4">
						<h3 id="error-message" class="bm-msg-header error" style="display: none">
							An error occured, please try again later.
						</h3>
				</div>
		</div>
	</div>

	<div id="loader" style="display: none;text-align: center;margin-top: 40px;">
		<i class="fa fa-spinner fa-spin" style="font-size: 50px;color: #3f64a1;position: absolute;margin: auto;top: 0;bottom: 0;height: 40px"></i>
	</div>
	<div id="content" style="display: none"></div>
@stop

@push('scripts')
<script type="text/javascript">
	var serchCompanies = $("#search-companies");
	@if (isset($companyId))	
		var newOption = new Option("<?= $companyName ?>", "<?= $companyId ?>", true, true);
		$(serchCompanies).append(newOption).trigger('change');
	@endif

	serchCompanies.select2({
		placeholder: "Search for a company",
		ajax: {
			url: '/data-reporting/companies/search/',
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
		loadSapData(companyId)
	});

	function loadSapData(companyId) {
		$.ajax ({
			url: '/data-reporting/outstanding/company/' + companyId + '/partial-load',
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