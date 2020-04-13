<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">{{ $vt->partyname }}</div>
            <div>
                <div class="tag">VAT exemption</div>
            </div>
        </div>
        <div class="title-lg {{ $info ? 'blue' : 'red' }}">Pending VAT exemption approval</div>
        <div class="subtitle">{{ $vt->address }}</div>
        <div class="lower-meta">
            <div class="item" title="country">
                <span class="glyphicon glyphicon-map-marker"></span><span class="item-text">{{ $vt->city->country->isocode }}</span>
            </div>
            <span class="vr-divider">|</span>
            <div class="item" title="company">
                <span class="glyphicon glyphicon-user"></span><span class="item-text">{{ $vt->company->companyname }}</span>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/vatexempt">View details</a>
</div>