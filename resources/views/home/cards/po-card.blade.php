<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">#{{ $po->userrelation == 2 ? $po->vendornumber : ($po->company_id . '-' . $po->number) }} (ver. {{ $po->version }})</div>
            <div>
                <div class="tag">{{ $po->getTypeName() }}</div>
            </div>
        </div>
            <div class="title-lg {{ $info ? 'blue' : 'red' }}">{{ $po->status->name }}</div>
            <div class="subtitle">{{ $po->currency->getSign() }}{{ number_format($po->total) }}</div>
            <div class="lower-meta">
                <div class="item" title="supplier">
                    <span class="glyphicon glyphicon-briefcase"></span><span class="item-text">{{ $po->vendor->companyname }}</span>
                </div>
                <span class="vr-divider">|</span>
                <div class="item" title="buyer">
                    <span class="glyphicon glyphicon-user"></span><span class="item-text">{{ $po->company->companyname }}</span>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/purchaseorders/{{ $view_link }}/{{$po->id}}">View details</a>
</div>