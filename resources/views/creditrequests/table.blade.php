@if($creditrequests)
<div class="row-fluid">
	@if(isset($title))	
		<h4 class="tb-title">{{ $title }}</h4>
    @endif
    <table id="listtable" class="table table-striped table-bordered table-hover dataTable table-condensed">
        <thead>
            <tr>
                <th>Company</th>
                <th>Request No.</th>
                <th>Date</th>
                <th>Requested limit</th>
                <th>Approved limit</th>
                <th>Request type</th>
                <th>Status</th>
                <th class="no-sort" width="10%"></th>			
            </tr>		
        </thead>
        <tbody>			
            @foreach ($creditrequests as $creditrequest)
                <tr>
                    <td> {{ $creditrequest->company->companyname }} </td>
                    <td> {{ $creditrequest->id }} </td>
                    <td> {{ $creditrequest->created_at->format('d/m/Y') }} </td>						
                    <td align="right"> {{ number_format($creditrequest->askedlimit, 2, '.', ',') }} </td>
                    <td align="right">
                        @if ($creditrequest->creditstatus_id == 2 || $creditrequest->creditstatus_id == 3)
                            N/A
                        @else
                            {{ number_format($creditrequest->limit, 2, '.', ',') }}
                        @endif
                    </td>
                    <td> {{ $creditrequest->creditrequesttype->name }} </td>
                    @php
                        $cssclass = '';
                    @endphp
                    @switch ($creditrequest->creditstatus_id)
                        @case('1')
                            @php $cssclass = 'bg-success' @endphp
                            @break
                        @case('2')
                            @php $cssclass = 'bg-warning' @endphp
                            @break
                        @case('3')
                            @php $cssclass = 'bg-danger' @endphp
                            @break
                        @case('4')
                            @php $cssclass = 'bg-warning' @endphp
                            @break
                        @case('5')
                            @php $cssclass = 'bg-warning' @endphp
                            @break
                    @endswitch
                    <td class="{{ $cssclass }}">
                        {{ $creditrequest->creditstatus->name }}
                    </td>
                    <td>
                        @if (Gate::allows('cr_vw', $creditrequest->id))
                            <a href="{{ url("/creditrequests/view/" . $creditrequest->id) }}" role="button"><span class="view-icon" title="View"></span></a>
                        @endif
                    </td>
                </tr>
            @endforeach			
        </tbody>
    </table>
</div>
@endif