@extends('layouts.app')
@section('title')
	@if (isset($title))
		{{ $title }}
	@endif
@stop
@section('content')
	@include('includes.company-profile-head')
	{{ Form::open(array('id' => 'frmManage', 'class' => 'shipping-add-form col-md-12')) }}	
	@if (isset($companies))
		<div class="header-container">	<!-- row 1 -->
			<div class="title"></div>
			<div class="buttons">  <!-- column 2 -->
				<a href="/pickupaddresses/create" class="biz-button colored-default" id="lnksubmit">
					<img src="{{asset('images/add-background.svg')}}" /> Create A New Address
				</a>
			</div>
		</div>				<!-- end row 1 -->
		<div class="row-fluid">	<!-- row 2 -->
			<table id="listtable" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Pickup Party Name</th>				
					<th>Address</th>
					<th>City</th>
					<th>Country</th>
					<th>Default address</th>
					<th class="no-sort">&nbsp;</th>
				</tr>		
			</thead>
			<tbody>			
				  @foreach ($companies as $company)
					@foreach ($company->pickupaddresses as $pickupaddress)
					<tr>
						<td> {{ $company->companyname }} </td>					
						<td> {{ $pickupaddress->partyname }} </td>
						<td> {{ $pickupaddress->address }} </td>
						<td>
							@if ($pickupaddress->city_id == 0)
								{{ $pickupaddress->city_name }} 
							@else
								{{ $pickupaddress->city->cityname }} 
							@endif
						</td>
						<td>
							@if ($pickupaddress->city_id == 0)
								{{ $pickupaddress->country_name }} 
							@else
								{{ $pickupaddress->city->country->countryname }} 
							@endif						
						</td>
						<td>
							@if ($pickupaddress->default)
								Yes
							@else
								No
							@endif
						</td>
						<td>
							@if (Gate::allows('co_ch', $pickupaddress->company_id))
								<a href="{{ url("/pickupaddresses/" . $pickupaddress->id) }}" role="button"><span class="edit-icon" title="Edit"></span></a>
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
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			//$("#submit").hide();			
		});
	</script>
@endpush 