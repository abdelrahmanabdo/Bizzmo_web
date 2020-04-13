@if ((Gate::allows('co_cr') ||  Gate::allows('co_sc') || Gate::allows('vn_sc')) && ($isCompany || $isShippingAddress || $isPickupAddress || $isVatExempt))
    @if (Gate::allows('co_cr') && !$hasCompany)
    <li class="<?= \Request::is('companies/create') ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/companies/create">Create Company</a>
    </li>
    @endif
    @if (Gate::allows('co_sc'))
        @if ($hasCompany)
            @if(!$userCompany->iscomplete)
            <li class="<?= (\Request::is(['companies/*', 'company/*']) && !$isMySuppliers) ? 'bm-active' : ''?>">
                <a class="bm-vr-nav-link" href="/companies/{{ $userCompany->id }}">Edit Company</a>
            </li>
            @else
            <li class="<?= \Request::is('companies/view/*') ? 'bm-active' : ''?>">
                <a class="bm-vr-nav-link" href="/companies/view/{{ $userCompany->id }}">Company Profile</a>
            </li>
            @endif
            @php
                $app_url = env('APP_URL');
            @endphp
            @if($app_url != 'https://bizzmo.com' && $userCompany->companytype->id == 4)

                <li class="<?= (\Request::is(['forwarder/route/view/*', 'forwarder/route/*']) && !$isMySuppliers) ? 'bm-active' : ''?>">
                    <a class="bm-vr-nav-link" href="/forwarder/route/view/{{ $userCompany->id }}">Routes</a>
                </li>
                <li class="<?= (\Request::is(['forwarder/services/create/*', 'forwarder/services/*']) && !$isMySuppliers) ? 'bm-active' : ''?>">
                    <a class="bm-vr-nav-link" href="/forwarder/services/create/{{ $userCompany->id }}">Services</a>
                </li>
                <li class="<?= (\Request::is(['forwarder/inspection/template/*', 'forwarder/inspection/*']) && !$isMySuppliers) ? 'bm-active' : ''?>">
                    <a class="bm-vr-nav-link" href="/forwarder/inspection/template/{{ $userCompany->id }}">Inspections</a>
                </li>
            @endif
        @else
            @if (!Gate::allows('co_cr'))
            <li class="<?= \Request::is('companies') ? 'bm-active' : ''?>">
                <a class="bm-vr-nav-link" href="/companies">Companies</a>
            </li>
            @endif
        @endif
    @endif
    @if ((Gate::allows('co_cr') || Gate::allows('co_ch')) && $buyerCompany)
    <li class="<?= $isShippingAddress ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/shippingaddresses">Manage Ship To Address</a>
    </li>
    @endif
	 @if ((Gate::allows('co_cr') || Gate::allows('co_ch')) && $supplierCompany)
    <li class="<?= $isPickupAddress ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/pickupaddresses">Manage Pickup Address</a>
    </li>
    @endif
    @if ((Gate::allows('coXXcr') || Gate::allows('cXXch')) && $buyerCompany)
    <li class="<?= $isMySuppliers ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/companies/mysuppliers/{{ $buyerCompany->id }}">Manage My Suppliers</a>
    </li>
    @endif
    @if ((Gate::allows('coXXcr') || Gate::allows('coXXch')) && $supplierCompany)
    <li class="<?= $isMyBuyers ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/companies/mybuyers/{{ $supplierCompany->id }}">Manage My Buyers</a>
    </li>
    @endif
    @if (Gate::allows('cr_ap'))
    <li class="<?= $isVatExempt ? 'bm-active' : ''?>">
        <a class="bm-vr-nav-link" href="/vatexempt">VAT Exempt Request</a>
    </li>
    @endif
    @if(App\Module::find(4)->active)
        <li class="<?= (\Request::is(['companies/productadd', 'companies/productadd'])) ? 'bm-active' : ''?>">
            <a class="bm-vr-nav-link" href="/companies/productadd">Add Product</a>
        </li>
        <li class="<?= (\Request::is(['companies/products', 'companies/products'])) ? 'bm-active' : ''?>">
            <a class="bm-vr-nav-link" href="/companies/products">Product Catalog</a>
        </li>
    @endif
@endif