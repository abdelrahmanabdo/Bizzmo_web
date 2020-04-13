<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">{{ $company->companyname }}</div>
            <div>
                <div class="tag">company</div>
            </div>
        </div>
        <div class="title-lg red">{{ $message}}</div>
        <div class="subtitle">{{ $company->phone }}</div>
        <div class="lower-meta">
            <div class="item" title="date">
                <span class="glyphicon glyphicon-map-marker"></span><span class="item-text">{{ $company->country->countryname }}</span>
            </div>
            <span class="vr-divider">|</span>
            <div class="item" title="time">
                <span class="item-text">{{ $company->city->cityname }}</span>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/companies/{{$company->iscomplete ? 'view/' : ''}}{{$company->id}}">View details</a>
</div>