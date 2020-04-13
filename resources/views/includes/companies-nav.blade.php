@auth
	@php
                $isCompany = \Request::is(['companies/*', 'companies', 'company/*/*', 'company/*']);
                $isShippingAddress = \Request::is(['shippingaddresses', 'shippingaddresses/*']);
                $isPickupAddress = \Request::is(['pickupaddresses', 'pickupaddresses/*']);
                $hasCompany = Auth::user()->companies->count();
                $userCompany = $hasCompany ? Auth::user()->companies->first() : null;
                $hasReadyBuyerCompany = Auth::user()->hasReadyBuyerCompany();
                $buyerCompany =  Auth::user()->getBuyerCompany();
                $supplierCompany =  Auth::user()->getSupplierCompany();
                $isMySuppliers = \Request::is('companies/mysuppliers/*');

                $buyerCompanyId = $hasReadyBuyerCompany ? $buyerCompany->id : null;
    @endphp
@endauth
<div class="page-header-container">
@if ((Gate::allows('co_cr') ||  Gate::allows('co_sc') || Gate::allows('vn_sc')) && ($isCompany || $isShippingAddress || $isPickupAddress || $isVatExempt))
    @if (Gate::allows('co_cr') && !$hasCompany)
    <div class="item">
        <a class="<?= \Request::is('companies/create') ? 'active' : ''?>" href="/companies/create">Create Company</a>
    </div>
    @endif
    @if (Gate::allows('co_sc'))
        @if ($hasCompany)
            @if(!$userCompany->iscomplete)
            <div class="item ">
                <a class=" <?= (\Request::is(['companies/*', 'company/*']) && !$isMySuppliers) ? 'active' : ''?>" href="/companies/{{ $userCompany->id }}">Edit Company</a>
            </div>
            @else
            <div class="item ">
                <a class=" <?= \Request::is('companies/view/*') ? 'active' : ''?>" href="/companies/view/{{ $userCompany->id }}">Company Registeration</a>
            </div>
            <div class="item ">
                <a class=" <?= \Request::is('companies/profile/*') ? 'active' : ''?>" href="/companies/profile/{{ $userCompany->id }}">Profile</a>
            </div>
            @endif
            @php
                $app_url = env('APP_URL');
            @endphp
            @if($app_url != 'https://bizzmo.com' && $userCompany->companytype->id == 4)

                <div class="item">
                    <a class=" <?= (\Request::is(['forwarder/route/template/*', 'forwarder/route/*']) && !$isMySuppliers) ? 'active' : ''?>" href="/forwarder/route/template/{{ $userCompany->id }}">Routes</a>
                </div>
                <div class="item">
                    <a class=" <?= (\Request::is(['forwarder/services/create/*', 'forwarder/services/*']) && !$isMySuppliers) ? 'active' : ''?>" href="/forwarder/services/create/{{ $userCompany->id }}">Services</a>
                </div>
                <div class="item">
                    <a class=" <?= (\Request::is(['forwarder/inspection/template/*', 'forwarder/inspection/*']) && !$isMySuppliers) ? 'active' : ''?>" href="/forwarder/inspection/template/{{ $userCompany->id }}">Inspections</a>
                </div>
            @endif
        @else
            @if (!Gate::allows('co_cr'))
            <div class="item">
                <a class="  <?= \Request::is('companies') ? 'active' : ''?>" href="/companies">Companies</a>
            </div>
            @endif
        @endif
    @endif
    @if ((Gate::allows('co_cr') || Gate::allows('co_ch')) && $buyerCompany)
    <div class="item ">
        <a class=" <?= $isShippingAddress ? 'active' : ''?>" href="/shippingaddresses">Manage Ship To Address</a>
    </div>
    @endif
	 @if ((Gate::allows('co_cr') || Gate::allows('co_ch')) && $supplierCompany)
    <div class="item">
        <a class=" <?= $isPickupAddress ? 'active' : ''?>" href="/pickupaddresses">Manage Pickup Address</a>
    </div>
    @endif
    @if ((Gate::allows('coXXcr') || Gate::allows('cXXch')) && $buyerCompany)
    <div class="item">
        <a class=" <?= $isMySuppliers ? 'active' : ''?>" href="/companies/mysuppliers/{{ $buyerCompany->id }}">Manage My Suppliers</a>
    </div>
    @endif
    @if ((Gate::allows('coXXcr') || Gate::allows('coXXch')) && $supplierCompany)
    <div class="item">
        <a class=" <?= $isMyBuyers ? 'active' : ''?>" href="/companies/mybuyers/{{ $supplierCompany->id }}">Manage My Buyers</a>
    </div>
    @endif
    @if (Gate::allows('cr_ap'))
    <div class="item">
        <a class=" <?= $isVatExempt ? 'active' : ''?>" href="/vatexempt">VAT Exempt Request</a>
    </div>
    @endif
    @if(App\Module::find(4)->active)
    {{-- <div cl ass="item "> --}}
        {{-- <a class=" @php \Request::is(['companies/productadd', 'companies/productadd'])  ? 'active' : '' @endphp" href="/companies/productadd">Add Product</a> --}}
    {{-- </div> --}}
    <div class="item ">
        <a class=" <?= \Request::is(['companies/products', 'companies/products']) ? 'active' : ''?>" href="/companies/products">Product Catalog</a>
    </div>
    @endif

@endif
</div>