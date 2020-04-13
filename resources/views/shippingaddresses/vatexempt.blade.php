@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{{ Form::open(array('id' => 'frmManage')) }}	
	@if (isset($shippingaddresses))
		<div class="row-fluid">	<!-- row 1 -->
			<div class="col-md-12">  <!-- column 1 -->
			<h2 class="bm-pg-title">{{ $title }}</h2>
			<br>
			</div>
		</div>				<!-- end row 1 -->
		<div class="row-fluid">	<!-- row 2 -->
			<div class="col-md-4">  <!-- column 1 -->
				<div class="form-group"> <!-- vendor -->  
					{{ Form::label('status', 'Status') }}
					{{ Form::select('status', ['0' => 'Pending', '1' => 'Approved', '2' => 'Rejected'], Input::get('status'),array('id' => 'status', 'class' => 'form-control bm-select'))}}
					@if ($errors->has('status')) <p class="bg-danger">{{ $errors->first('status') }}</p> @endif
				</div> <!-- vendor end -->  
			</div>				<!-- column 1 end -->
			<div class="col-md-8">  <!-- column 2 -->
				<div class="form-group"> <!-- vendor -->  
					{{ Form::label('search', 'Search buyer') }}
					{{ Form::text('search', Input::get('search'),array('id' => 'search', 'class' => 'form-control'))}}
					@if ($errors->has('search')) <p class="bg-danger">{{ $errors->first('search') }}</p> @endif
				</div> <!-- vendor end -->  
			</div>				<!-- column 2 end -->
		</div>				<!-- end row 1 -->
		<div class="row-fluid">	<!-- row 3 --> 
			<div class="col-md-12"> <!-- Column 1 -->
				@if (isset($mode))
				@else
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
					<a href="" class="btn btn-info bm-btn" id="lnksubmit">
						Search
					</a>
				@endif    
				
			</div> <!-- Column 1 end -->
		</div> <!--row 3 end -->		
		<div class="row-fluid">	<!-- row 3 -->
			<table id="listtable" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Company</th>
					<th>Ship To Party Name</th>				
					<th>Address</th>
					<th>City / Country</th>
					<th>Delivery Address</th>
					<th>Delivery City / Country</th>
					<th>VAT</th>
					@if (isset($showcontrols))
						<th>&nbsp;</th>
					@endif
				</tr>		
			</thead>
			<tbody>			
				@foreach ($shippingaddresses as $shippingaddress)
					<tr>
						<td> {{ $shippingaddress->company->companyname }} </td>					
						<td> {{ $shippingaddress->partyname }} </td>
						<td> {{ $shippingaddress->address }} </td>
						<td> {{ $shippingaddress->city->cityname }} / {{ $shippingaddress->city->country->countryname }}</td>
						<td> {{ $shippingaddress->delivery_address }} </td>
						<td> {{ $shippingaddress->deliverycity->cityname ?? '' }} / {{ $shippingaddress->deliverycity->country->countryname ?? '' }}</td>
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
						@if (isset($showcontrols))
							<td>
								<a href="{{ url("/vatexempt/approve/" . $shippingaddress->id) }}" role="button"><span class="confirm-icon" title="Approve"></span></a>
								<a href="{{ url("/vatexempt/reject/" . $shippingaddress->id) }}" role="button"><span class="cancel-icon" title="Reject"></span></a>
							</td>
						@endif
					</tr>	
				@endforeach	
			</tbody>
			</table>
		</div> <!-- end row 3 -->
	@endif
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});
		});
	</script>
@endpush 