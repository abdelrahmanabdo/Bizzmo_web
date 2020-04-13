<div class="row-fluid">
	@if(isset($title))	
		<h4 class="tb-title">{{ $title }}</h4>
    @endif
    <table id="listtable" class="table table-striped table-bordered table-hover table-tight dataTable">
        <thead>
            <tr>
                <th>Buyer Number</th>
                <th>Bizzmo Number</th>
                <th>Date</th>
                <th>Buyer</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Total</th>
                <th class="no-sort" width="10%">
                    @if (Gate::allows('po_cr_XX'))
						<a href="{{ url("/purchaseorders/create") }}" class="add-icon" role="button" title="Add"></a>	
                    @endif
                </th>
            </tr>		
        </thead>
        <tbody>			
            @foreach ($purchaseorders as $purchaseorder)
                <tr>					
                    <td> {{ $purchaseorder->company_id }}-{{ $purchaseorder->number }} (ver. {{ $purchaseorder->version }})</td>
                    <td> {{ $purchaseorder->vendornumber }} </td>
                    <td align="right"> {{ date('j/n/Y', strtotime($purchaseorder->date)) }} </td>
                    <td> {{ $purchaseorder->company->companyname }} </td>
                    <td> {{ $purchaseorder->vendor->companyname }} </td>
                    <td> {{ $purchaseorder->status->name }} </td>
                    @if ($purchaseorder->userrelation == 2)
                        <td align="right"> {{ number_format($purchaseorder->total * (100 - $purchaseorder->company->margin) / 100, 2, '.', ',') }} </td>
                    @else
                        <td align="right"> {{ number_format($purchaseorder->total, 2, '.', ',') }} </td>
                    @endif					

                    <td nowrap>
                        @if (Gate::allows('po_vw') || Gate::allows('po_vm') || Gate::allows('vp_vw', $purchaseorder->id))
                            @if (Gate::allows('po_vw', $purchaseorder->id))
                                <a href="{{ url("/purchaseorders/view/" . $purchaseorder->id) }}" role="button"><span class="view-icon" title="View"></span></a>
                                &nbsp;
                            @elseif (Gate::allows('po_vm', $purchaseorder->id))
                                <a href="{{ url("/purchaseorders/mview/" . $purchaseorder->id) }}" role="button"><span class="view-icon" title="View"></span></a>
                                &nbsp;
                            @elseif (Gate::allows('vp_vw', $purchaseorder->id))
                                <a href="{{ url("/purchaseorders/vview/" . $purchaseorder->id) }}" role="button"><span class="view-icon" title="View"></span></a>
                                &nbsp;
                            @endif
                        @endif						
                    </td>
                </tr>	
            @endforeach			
        </tbody>
    </table>
</div>