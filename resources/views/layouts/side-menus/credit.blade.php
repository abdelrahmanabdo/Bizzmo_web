@if (($isCreditRequest || $isSiteVisit || $isCreditCheck) && (Gate::any(['cr_ap', 'cr_of', 'fi_ar']) || (Gate::allows('cr_sc') && Auth::user()->hasReadyBuyerCompany())))
    @if (Gate::allows('cr_sc'))
    <li class="<?= $isCreditRequest ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/creditrequests">Credit requests</a>
    </li>
    @endif
    @if (Gate::any(['cr_ap', 'cr_of']))
		<li class="<?= \Request::is(['calendar']) ? 'bm-active' : ''?>">
			<a class="bm-vr-nav-link" href="/calendar/">Appointments</a>
		</li>
	@endif
	@if (Gate::any('cr_ap'))
		<li class="<?= \Request::is(['calendar/block-appointments', 'calendar/block-appointments/*']) ? 'bm-active' : ''?>">
			<a class="bm-vr-nav-link" href="/calendar/block-appointments">Block Calendar</a>
		</li>
    @endif
    @if (Gate::any(['cr_ap', 'fi_ar']) || (Gate::allows('co_sc') && Auth::user()->hasReadyBuyerCompany()))
    <li class="<?= \Request::is('credit/*') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/credit/check">Credit check</a>
    </li>							
    @endif
@endif