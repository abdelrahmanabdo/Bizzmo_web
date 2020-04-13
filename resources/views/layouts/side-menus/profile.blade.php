@if ($isProfile)
<li class="<?= \Request::is('profile') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/profile">Account Information</a>
</li>
<li class="<?= \Request::is('profile/edit') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/profile/edit">Edit Account</a>
</li>
<li class="<?= \Request::is('profile/change-password') ? 'bm-active' : ''?>">
    <a class="bm-vr-nav-link" href="/profile/change-password">Change Password</a>
</li>
@endif