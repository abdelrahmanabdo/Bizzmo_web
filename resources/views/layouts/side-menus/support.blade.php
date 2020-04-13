@if ($isSupport && Gate::any(['su_ch', 'su_vw']))
<li class="<?= \Request::is('supports/open') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/supports/open">Open incidents</a>
</li>
<li class="<?= \Request::is('supports') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/supports">Incidents</a>
</li>
@endif