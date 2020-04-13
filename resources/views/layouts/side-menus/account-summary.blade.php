@if ($isDataReporting)
    <li class="<?= \Request::is('data-reporting/statement-of-account') || \Request::is('data-reporting/statement-of-account/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/data-reporting/statement-of-account/">Account Status</a>
    </li>
    <li class="<?= \Request::is('data-reporting/outstanding') || \Request::is('data-reporting/outstanding/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/data-reporting/outstanding/">Statement of Outstanding</a>
    </li>
@endif