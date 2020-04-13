@extends('layouts.app', ['pendingRequestsCount' => $pendingRequestsCount])
@section('content')
    @if(!$pendingRequestsCount)
        <h4>You don't have any pending requests</h4>
    @endif
    @if(isset($pendingCustomerPOs) || isset($pendingVendorPOs) || isset($pendingCreditPOs))
        @if($pendingCustomerPOs && count($pendingCustomerPOs))
            @include('purchaseorders.table', ['purchaseorders' => $pendingCustomerPOs, 'title' => 'Pending purchase orders'])
        @endif
        @if($pendingVendorPOs && count($pendingVendorPOs))
            @if(count($pendingCustomerPOs))
            <hr class="tbs-divider">
            @endif
            @include('purchaseorders.table', ['purchaseorders' => $pendingVendorPOs, 'title' => 'Pending vendor purchase orders'])
        @endif
        @if($pendingCreditPOs && count($pendingCreditPOs))
            @if(count($pendingVendorPOs) || count($pendingCustomerPOs))
            <hr class="tbs-divider">
            @endif
            @include('purchaseorders.table', ['purchaseorders' => $pendingCreditPOs, 'title' => 'Purchase orders pending credit'])
        @endif
    @endif
    @if(isset($pendingCRs) || isset($pendingCustomerCRs))
        @if($pendingCRs && count($pendingCRs))
            @if(count($pendingVendorPOs) || count($pendingCreditPOs) || count($pendingCustomerPOs))
            <hr class="tbs-divider">
            @endif
            @include('creditrequests.table', ['creditrequests' => $pendingCRs, 'title' => 'Pending credit requests'])
        @endif
        @if($pendingCustomerCRs && count($pendingCustomerCRs))
            @if(count($pendingVendorPOs) || count($pendingCreditPOs) || count($pendingCustomerPOs) || count($pendingCRs))
            <hr class="tbs-divider">
            @endif
            @include('creditrequests.table', ['creditrequests' => $pendingCustomerCRs, 'title' => 'Pending Credit requests'])
        @endif
    @endif
	@if($pendingCustomerAppointments && count($pendingCustomerAppointments))
        @if(count($pendingVendorPOs) || count($pendingCreditPOs) || count($pendingCustomerPOs) || count($pendingCRs) || count($pendingCustomerCRs))
        <hr class="tbs-divider">
        @endif
        @include('calendar.table', ['appointments' => $pendingCustomerAppointments, 'title' => 'Pending Appointments'])
	@endif
	@if($pendingCreditAppointments && count($pendingCreditAppointments))
        @if(count($pendingVendorPOs) || count($pendingCreditPOs) || count($pendingCustomerPOs) || count($pendingCRs) || count($pendingCustomerCRs) || count($pendingCustomerAppointments))
        <hr class="tbs-divider">
        @endif    
        @include('calendar.table', ['appointments' => $pendingCreditAppointments, 'title' => 'Pending Appointments'])
	@endif
@stop

