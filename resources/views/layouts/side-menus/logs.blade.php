@if ($isLogs)
    <li class="<?= \Request::is('log-viewer/logs') || \Request::is('log-viewer/logs/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/log-viewer/logs">App Logs</a>
    </li>
	 <li class="<?= \Request::is('admin/metrics') || \Request::is('admin/metrics/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/admin/metrics">Metrics</a>
    </li>
    <li class="<?= \Request::is('logs/login-logs') || \Request::is('logs/login-logs/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/logs/login-logs/">Login Logs</a>
    </li>
	<li class="<?= \Request::is('logs/phpinfo') || \Request::is('logs/phpinfo/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/logs/phpinfo/">PHP Info</a>
    </li>
@endif