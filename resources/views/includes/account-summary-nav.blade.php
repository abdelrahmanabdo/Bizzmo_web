@php
    $isDataReporting = \Request::is('data-reporting') || \Request::is('data-reporting/*') || \Request::is('purchaseorders/pending*');
@endphp

@if ($isDataReporting)
    <div class="page-header-container">
        <div class="item">
            <a class="<?= \Request::is('data-reporting/statement-of-account') || \Request::is('data-reporting/statement-of-account/*') ? 'active' : ''?>" href="/data-reporting/statement-of-account/">Account Status</a>
        </div>
        <div class="item">
            <a class="<?= \Request::is('data-reporting/outstanding') || \Request::is('data-reporting/outstanding/*') ? 'active' : ''?>" href="/data-reporting/outstanding/">Statement of Outstanding</a>
        </div>
    </div>
@endif