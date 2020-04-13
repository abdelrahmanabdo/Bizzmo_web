<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">#{{ $qu->userrelation == 1 ? $qu->vendornumber : ($qu->vendor_id . '-' . $qu->number) }} (ver. {{ $qu->version }})</div>
            <div>
                <div class="tag">{{ $qu->getTypeName() }}</div>
            </div>
        </div>
            <div class="title-lg  {{ $info ? 'blue' : 'red' }}">{{ $qu->status->name }}</div>
            <div class="subtitle">{{ $qu->currency->getSign() }}{{ number_format($qu->total) }}</div>
            <div class="lower-meta">
                <div class="item" title="supplier">
                    <span class="glyphicon glyphicon-briefcase"></span><span class="item-text">{{ $qu->vendor->companyname }}</span>
                </div>
                <span class="vr-divider">|</span>
                <div class="item" title="buyer">
                    <span class="glyphicon glyphicon-user"></span><span class="item-text">{{ $qu->company->companyname }}</span>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/quotations/{{$view_link}}/{{$qu->id}}">View details</a>
</div>