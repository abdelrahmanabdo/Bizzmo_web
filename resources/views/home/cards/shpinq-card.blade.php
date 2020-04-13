<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">#{{ $shpinq->id }})</div>
            <div>
                <div class="tag">{{ $shpinq->id }}</div>
            </div>
        </div>
            <div class="title-lg {{ $info ? 'blue' : 'red' }}">{{ $shpinq->id }}</div>
            <div class="subtitle">{{ $shpinq->purchaseorder->company->companyname }}</div>
            <div class="lower-meta">
                <div class="item" title="supplier">
                    <span class="glyphicon glyphicon-briefcase"></span><span class="item-text">{{ $shpinq->company->companyname }}</span>
                </div>
                <span class="vr-divider">|</span>
                <div class="item" title="buyer">
                    <span class="glyphicon glyphicon-user"></span><span class="item-text">{{ $shpinq->id }}</span>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/forwarder/route/display/{{$shpinq->id}}">View details</a>
</div>