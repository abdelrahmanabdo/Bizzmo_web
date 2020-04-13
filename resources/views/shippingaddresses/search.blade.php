@extends('layouts.app')
@section('title')
	@if (isset($title))
		{{ $title }}
	@endif
@stop
@section('content')
	<div class="col-md-12">
		@include('includes.company-profile-head')
	</div>

	{{ Form::open(array('id' => 'frmManage', 'class' => 'shipping-add-form col-md-12')) }}	
	@if (isset($companies))
		<div class="header-container" style="">	<!-- row 1 -->
			<h3 class="title"></h3>
			<div class="buttons">  <!-- column 2 -->
				<a href="/shippingaddresses/create" class="biz-button colored-default" id="lnksubmit">
					<img src="{{asset('images/add-background.svg')}}" />	Create A New Address
				</a>
			</div>
		</div>				<!-- end row 1 -->
		<div class="row-fluid">	<!-- row 2 -->
			<table id="listtable" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Ship To Party Name</th>				
					<th>Address</th>
					<th>City</th>
					<th>Country</th>
					<th>VAT</th>
					<th>Default address</th>
					<th class="no-sort">&nbsp;</th>
				</tr>		
			</thead>
			<tbody>			
				  @foreach ($companies as $company)
					@foreach ($company->shippingaddresses as $shippingaddress)
					<tr>
						<td> {{ $company->companyname }} </td>					
						<td> {{ $shippingaddress->partyname }} </td>
						<td> {{ $shippingaddress->address }} </td>
						<td>
							@if ($shippingaddress->city_id == 0)
								{{ $shippingaddress->city_name }} 
							@else
								{{ $shippingaddress->city->cityname }} 
							@endif
						</td>
						<td>
							@if ($shippingaddress->city_id == 0)
								{{ $shippingaddress->country_name }} 
							@else
								{{ $shippingaddress->city->country->countryname }} 
							@endif						
						</td>
						<td>
							@if ($shippingaddress->vatexempt)
								Pending VAT exempt approval
							@else
								@if ($shippingaddress->vat)
									Yes
								@else
									No
								@endif
							@endif	
						</td>
						<td>
							@if ($shippingaddress->default)
								Yes
							@else
								No
							@endif
						</td>
						<td>
							@if (Gate::allows('co_ch', $shippingaddress->company_id))
								<a href="{{ url("/shippingaddresses/" . $shippingaddress->id) }}" role="button"><span class="edit-icon" title="Edit"></span></a>
							@endif
						</td>
					</tr>	
					@endforeach			
				  @endforeach			
			</tbody>
			</table>
		</div>				<!-- end row 2 -->
	@endif
@stop
<style>
.bm-heade {
	display :flex ;
	flex-direction : row;
	align-items : center ;
	justify_Content : space-between
}
</style>
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			//$("#submit").hide();			
		});
	</script>
@endpush 