@if ($isTransaction && (Gate::any(['po_sc', 'vp_sc', 'po_vm']) || Gate::any(['qu_vw', 'cq_vw'])))
    @if (Gate::any(['po_sc', 'vp_sc', 'po_vm']) && (Auth::user()->getBuyerCompany() || Auth::user()->getSupplierCompany()))
    <li class="<?= \Request::is('transactions') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/transactions">Pending Transactions</a>
    </li>
    @endif
    @if (Gate::allows('po_cr') && Auth::user()->hasReadyBuyerCompany())
    <li class="<?= \Request::is(['purchaseorders/create', 'purchaseorders/create/*']) ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/purchaseorders/create">Request To Buy</a>
    </li>
    @endif
    @if (Gate::any(['po_sc', 'vp_sc', 'po_vm']))
    <li class="<?= (\Request::is('purchaseorders') || \Request::is('purchaseorders/*')) && !\Request::is(['purchaseorders/create', 'purchaseorders/create/*']) ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/purchaseorders">Purchase orders</a>
    </li>
    @endif
    @if (Gate::allows('qu_cr') && Auth::user()->hasReadySupplierCompany())
    <li class="<?= \Request::is('quotations/create') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/quotations/create">Request To Sell</a>
    </li>
    @endif
    @if (Gate::allows('qu_vw') || Gate::allows('cq_vw'))
    <li class="<?= (\Request::is('quotations') || (\Request::is('quotations/*') && !\Request::is('quotations/create'))) ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/quotations">Quotations</a>
    </li>
    @endif
@endif