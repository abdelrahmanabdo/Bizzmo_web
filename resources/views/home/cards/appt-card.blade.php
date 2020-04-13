@php
    $statusName = $is_comp_site_visit ? 'Complete Site Visit' : $appt->status->name;
    $view_link = $is_comp_site_visit ? 'complete' : 'view';
@endphp
<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">{{ $appt->company->companyname }}</div>
            <div>
                <div class="tag">{{ $appt->getTypeName() }}</div>
            </div>
        </div>
        <div class="title-lg {{ $info ? 'blue' : 'red' }}">{{ $statusName }}</div>
        <div class="subtitle">{{ $appt->description }}</div>
        <div class="lower-meta">
            <div class="item" title="date">
                <span class="glyphicon glyphicon-calendar"></span><span class="item-text">{{ $appt->date }}</span>
            </div>
            <span class="vr-divider">|</span>
            <div class="item" title="time">
                <span class="glyphicon glyphicon-time"></span><span class="item-text">{{ $appt->timeslot->name }}</span>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/calendar/{{$view_link}}/{{$appt->id}}">View details</a>
</div>