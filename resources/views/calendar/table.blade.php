@if($appointments)
<div class="row-fluid">
    @if(isset($title))	
		<h4 class="tb-title">{{ $title }}</h4>
    @endif
    <table id="listtable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Company</th>
				<th>Date</th>
				<th>Time</th>
				<th>Description</th>
				<th>Status</th>
				<th class="no-sort" width="10%">
				</th>
			</tr>		
		</thead>
		<tbody>			
			  @foreach ($appointments as $appointment)
				<tr>
					<td> {!! $appointment->company_id == 0 ? "" : $appointment->company->companyname !!} </td>
					<td align="right"> {!! date('j/n/Y', strtotime($appointment->date)) !!} </td>
					<td> {!! $appointment->timeslot->name !!} </td>
					<td> {!! $appointment->description !!} </td>
					@php $class =''; @endphp
					@switch($appointment->status_id)
						@case(1)
							@php $class ='bg-warning'; @endphp
							@break
						@case(2)
							@php $class ='bg-danger'; @endphp
							@break
						@case(3)
							@php $class ='bg-success'; @endphp
							@break
						@case(8)
							@php $class ='bg-success'; @endphp
							@break
						@case(9)
							@php $class ='bg-danger'; @endphp
							@break
					@endswitch					
					<td class="{{ $class }}"> 						
						{{ $appointment->status->name }} 
					</td>
					<td>
						<a href="{!! url('/calendar/view/' . $appointment->id) !!}" role="button"><span class="view-icon" title="View"></span></span></a>			
					</td>
				</tr>	
			  @endforeach			
		</tbody>
		</table>
</div>
@endif