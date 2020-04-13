@if ($isUser && Gate::any(['us_cr', 'us_ch', 'us_vw']))
<li class="<?= \Request::is('users/create') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/users/create">Add user</a>
</li>
<li class="<?= \Request::is('users') || \Request::is('users/view/*') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/users">Users</a>
</li>
@endif
@if ($isUser && Gate::any(['ro_cr', 'ro_ch', 'ro_vw']))
<li class="<?= \Request::is('roles/create') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/roles/create">Add role</a>
</li>
<li class="<?= \Request::is('roles') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/roles">Roles</a>
</li>
@endif