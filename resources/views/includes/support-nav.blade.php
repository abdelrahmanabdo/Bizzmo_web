<div class="page-header-container">
    <div class="item"><a href="{{url('support/report-issue')}}" @if(\Request::is('support/report-issue')) class="active" @endif>Report an Issue</a></div>
    <div class="item"><a href="{{url('support/issues')}}" @if(\Request::is('support/issues')) class="active" @endif>Issues</a></div>
</div>