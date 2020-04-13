@extends('layouts.app')
@section('content')	
	{{ Form::open(array('id' => 'frmManage')) }}
		<div class="row">	<!-- row 1 -->

			{{ Form::submit('Search', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button')) }}
			
			
			<select class="js-data-example-ajax form-control" name="searchname">

			</select>

			
		</div>
		@if (isset($companies))
			@php
				$row = 0;
				$col = 0;
			@endphp
			@foreach ($companies as $company)
				
				@if ($col == 0)
					<div class="row">
				@endif
				
				
				<div class="col-md-3">  <!-- column 1 -->
					@if ($col == 3)
						<div style="height:100px;text-align:center;background-color:lightgrey">
							
						</div>
					@else
						<div style="max-height:100px;text-align:center;">
							<img src="images/logo.png"  class="img-responsive center-block" style="max-height:inherit;">
						</div>
					@endif
					<div style="height:80px;overflow:hidden;padding-top:10px;text-align:center;">
						{{ $company->companyname }}
					</div>
				</div>
				
				@if ($col == 3)
					</div>
					@php
						$col = 0;
					@endphp
				@else
					@php
						$col = $col + 1;				
					@endphp
				@endif
				
			@endforeach
			
			</div>
			
			<div class="row">	<!-- row 1 -->
			{{ Form::text('page', old('page', $companies->currentPage()), array('id' => 'page', 'class' => 'form-control', 'class' => 'form-control hidden')) }}
			@if ($companies->currentPage() != 1)
				<a href="" class="btn btn-primary bm-btn green" id="lnkPrev" type="button" title="Confirm">Prev</a>
			@endif
			@if ($companies->currentPage() >= $companies->lastItem())
				<a href="" class="btn btn-primary bm-btn green" id="lnkNext" type="button" title="Confirm">Next</a>
			@endif
			</div>
		@endif
			
	{{ Form::close() }}
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
						 
			
			$(".js-data-example-ajax").select2({
				  ajax: { 
				   url: "/companies/suppliers",
				   type: "post",
				   dataType: 'json',
				   delay: 250,
				   data: function (params) {
					return {
					  searchTerm:  params.term,
					  _token: $('input[name=_token]').val()
					};
				   },
				   processResults: function (data) {
						return {
							results:  $.map(data, function (item) {
								return {
									text: item.companyname,
									id: item.id
								}
							})
						};
						},
				   cache: true
				  }
				 });
			
			$("#lnkPrev").bind('click', function(e) {
				e.preventDefault();
				$("#page").val($("#page").val() - 1);
				$("#submit").click();
			});
			$("#lnkNext").bind('click', function(e) {
				e.preventDefault();
				$("#page").val(parseInt($("#page").val()) + 1);
				$("#submit").click();
			});
		});
	</script>
@endpush