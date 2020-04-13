<div class="row-fluid">
	@if(isset($title))	
		<h4 class="tb-title">{{ $title }}</h4>
    @endif
    <table id="quotations-table" class="table table-striped table-bordered table-hover table-tight dataTable">
        <thead>
            <tr>
                <th>Supplier Number</th>
                <th>Bizzmo Number</th>
                <th>Date</th>
                <th>Buyer</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Total</th>
                <th class="no-sort" width="10%"></th>
            </tr>		
        </thead>
        <tbody>			
            @foreach ($quotations as $quotation)
                <tr>					
                    <td> {{ $quotation->vendor_id }}-{{ $quotation->number }} (ver. {{ $quotation->version }})</td>
                    <td> {{ $quotation->vendornumber }} </td>
                    <td align="right"> {{ date('j/n/Y', strtotime($quotation->date)) }} </td>
                    <td> {{ $quotation->company->companyname }} </td>
                    <td> {{ $quotation->vendor->companyname }} </td>
                    <td> {{ $quotation->status->name }} </td>
                    @if ($quotation->userrelation == 2)
                        <td align="right"> {{ number_format($quotation->total * (100 - $quotation->company->margin) / 100, 2, '.', ',') }} </td>
                    @else
                        <td align="right"> {{ number_format($quotation->total, 2, '.', ',') }} </td>
                    @endif					

                    <td nowrap>
                        @if (Gate::allows('qu_vw', $quotation->id))
                            <a href="{{ url("/quotations/view/" . $quotation->id) }}" role="button"><span class="view-icon" title="View"></span></a>
                            &nbsp;
                        @elseif (Gate::allows('cq_vw', $quotation->id))
                            <a href="{{ url("/quotations/bview/" . $quotation->id) }}" role="button"><span class="view-icon" title="View"></span></a>
                            &nbsp;
                        @endif		
                    </td>
                </tr>	
            @endforeach			
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function(){
    $('#quotations-table').dataTable({	
        "order": [],
        "aoColumnDefs": [ { 'bSortable': false, 'aTargets': [ "no-sort" ] } ],
        "paging": false,
        "bFilter" : false,
        "bLengthChange": false,
        "info": false
    });
});
</script>
@endpush