@if ($isCustomerSupport && Gate::allows('report_issue'))
<li class="<?= \Request::is('support/report-issue') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/support/report-issue">Report an issue</a>
</li>
<li class="<?= \Request::is('support/issues') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/support/issues">Issues</a>
</li>
@endif