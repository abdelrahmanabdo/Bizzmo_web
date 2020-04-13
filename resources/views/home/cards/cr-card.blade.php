<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">{{ $cr->creditrequesttype->name }} request</div>
            <div>
                <div class="tag">{{ $cr->getTypeName() }}</div>
            </div>
        </div>
        <div class="title-lg {{ $info ? 'blue' : 'red' }}">{{ $cr->creditstatus->name }}</div>
        <div class="subtitle">{{ number_format($cr->askedlimit) }}</div>
        <div class="lower-meta">
            <div class="item" title="country">
                <span class="glyphicon glyphicon-map-marker"></span><span class="item-text">{{ $cr->company->city->country->isocode }}</span>
            </div>
            <span class="vr-divider">|</span>
            <div class="item" title="company">
                <span class="glyphicon glyphicon-user"></span><span class="item-text">{{ $cr->company->companyname }}</span>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/creditrequests/view/{{$cr->id}}">View details</a>
</div>