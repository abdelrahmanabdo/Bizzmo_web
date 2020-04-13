<div class="row-fluid">
	<table id="mytable-<?= $key ?>" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th>Date</th>
			<th>Description</th>
			<th>Reference</th>
			<th>Currency</th>
			<th>Value</th>
			<th>Due date</th>
			@if (Gate::allows('pt_as') || Gate::allows('cr_ap'))
				<th>SAP Doc No.</th>
			@endif
		</tr>		
	</thead>
	<tbody>			
			@foreach ($items as $item)
			<tr>
				<td align="right">{{ date("d/m/Y", strtotime($item["PSTNG_DATE"])) }} </td>
				<td>{{ $item["REF_DOC_NO"] }} </td>					
				<td>{{ $item["DOC_HEADER_TXT"] }} </td>					
				<td>{{ $item["CURRENCY"] }} </td>
				@if ($item["SHKZG"] == 'S')
					<td align="right">{{ $item["AMT_GRPCUR"] }} </td>
				@else
					<td align="right">-{{ $item["AMT_GRPCUR"] }} </td>
				@endif
				@if(date("Ymd") > date("Ymd", strtotime($item["NETDT"])))
					<td align="right" class="bg-danger"><b>{{ date("d/m/Y", strtotime($item["NETDT"])) }}</b></td>
				@else
					<td align="right" class="bg-success"><b>{{ date("d/m/Y", strtotime($item["NETDT"])) }}</b></td>
				@endif
				@if (Gate::allows('pt_as') || Gate::allows('cr_ap'))
					<td align="right">{{ $item["DOC_NO"] }}</td>
				@endif
			</tr>	
			@endforeach			
	</tbody>
	</table>
</div>

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			//data table
			$('#mytable-<?= $key ?>').dataTable({	
				"order": [ 1, 'desc' ],
				"aoColumnDefs": [ { 'bSortable': false, 'aTargets': [ "no-sort" ] } ],
				"iDisplayLength": 10,
				"language": [{
				  "emptyTable": "No data available in table"
				}],
				"pagingType": "full_numbers"		
			});
		});
	</script>
@endpush 