@php
    $purchaseOrdersAction = count($posPendingCustomerAction) + count($posPendingCreditAction) + count($posPendingVendorAction);
    $creditRequestsAction = count($creditRequestPendingAction) + count($creditrequestpendingcustomer);
    $appointmentsAction = count($appointmentpendingconfirmation) + count($appointmentPendingCompleteSiteVisit);
    $quotationsAction = count($quotationsPendingVendorAction) + count($quotationsPendingCustomer);
    $actionNeeded = $purchaseOrdersAction + $appointmentsAction + count($unconfirmedcompany) + count($unsignedcompany) + $creditRequestsAction + count($opensupport) + $quotationsAction + count($pendingvatexemptrequest) + count($shpinqsPending);
    
    $creditRequestsPending = count($creditRequestPendingCustomerInfo) + count($creditRequestPendingInfo);
    $appointmentsPending = count($appointmenttoday) + count($appointmenttomorrow) + count($appointmentpending) + count($appointmentconfirmed);
    $purchaseOrdersPending = count($posPendingCustomerInfo) + count($posPendingVendorInfo) + count($posPendingCreditInfo);
    $quotationsPending = count($quotationsPendingVendorInfo);
    $informative = $appointmentsPending + $creditRequestsPending + $purchaseOrdersPending + $quotationsPending + count($inactivecompany);
@endphp
@extends('layouts.app')
@section('content')
<div class="row-fluid cards-pg">
@if (Auth::guest())
    @include('home.guest')
@else
    @if($actionNeeded)
		<h2 class="bm-title">Action needed</h2>
		<div class="items-count action">
			@if($purchaseOrdersAction)
			<button class="btn item" type="button" data-link="po_action" title="view purchase orders cards">
				<h3 class="item title-with-count">Purchase orders<span class="badge">{{ $purchaseOrdersAction }}</span></h3>
			</button>
			@endif
			@if($quotationsAction)
			<button class="btn item" type="button" data-link="qu_action" title="view credit requests cards">
				<h3 class="item title-with-count">Quotations<span class="badge">{{ $quotationsAction }}</span></h3>
			</button>
			@endif
			@if($appointmentsAction)
			<button class="btn item" type="button" data-link="appt_action" title="view appointments cards">
				<h3 class="item title-with-count">Appointments<span class="badge">{{ $appointmentsAction }}</span></h3>
			</button>
			@endif
			@if($creditRequestsAction)
			<button class="btn item" type="button" data-link="cr_action" title="view credit requests cards">
				<h3 class="item title-with-count">Credit requests<span class="badge">{{ $creditRequestsAction }}</span></h3>
			</button>
			@endif			
			@if(count($opensupport))
			<button class="btn item" type="button" data-link="support" title="view open issues cards">
				<h3 class="item title-with-count">Issues<span class="badge">{{ count($opensupport) }}</span></h3>
			</button>
			@endif
			@if(count($unconfirmedcompany))
			<button class="btn item" type="button" data-link="unconfirmed_company" title="view unconfirmed company card">
				<h3 class="item title-with-count">Company<span class="badge">{{ count($unconfirmedcompany) }}</span></h3>
			</button>
			@endif
			@if(count($unsignedcompany))
			<button class="btn item" type="button" data-link="unsigned_company" title="view unsigned company card">
				<h3 class="item title-with-count">Company<span class="badge">{{ count($unsignedcompany) }}</span></h3>
			</button>
			@endif
			@if(count($pendingvatexemptrequest))
			<button class="btn item" type="button" data-link="vat_exempt" title="view pending VAT exempt">
				<h3 class="item title-with-count">VAT exempt<span class="badge">{{ count($pendingvatexemptrequest) }}</span></h3>
			</button>
			@endif
			@if(count($shpinqsPending))
			<button class="btn item" type="button" data-link="vat_shpinq" title="view pending Shipment Inquiries">
				<h3 class="item title-with-count">Shipment Inquiries<span class="badge">{{ count($shpinqsPending) }}</span></h3>
			</button>
			<div id="shpinq_action">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $shpinqsPending, "view_link" => "mview", "type" => "shpinq", "info" => false])
				</div>
			</div>
			@endif
		</div>
		<div id="current_action"></div>
		<div class="hidden">
			<div id="po_action">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $posPendingCustomerAction, "view_link" => "view", "type" => "po", "info" => false])
				@include('home.cards.index', ["items" => $posPendingCreditAction, "view_link" => "mview", "type" => "po", "info" => false])
				@include('home.cards.index', ["items" => $posPendingVendorAction, "view_link" => "vview", "type" => "po", "info" => false])
				</div>
			</div>
			<div id="qu_action">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $quotationsPendingVendorAction, "type" => "qu", "view_link" => "view", "info" => false])
				@include('home.cards.index', ["items" => $quotationsPendingCustomer, "type" => "qu", "view_link" => "bview", "info" => false])
				</div>
			</div>
			<div id="cr_action">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $creditrequestpendingcustomer, "type" => "cr", "info" => false])
				@include('home.cards.index', ["items" => $creditRequestPendingAction, "type" => "cr", "info" => false])
				</div>
			</div>
			<div id="vat_exempt">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $pendingvatexemptrequest, "type" => "vt", "info" => false])
				</div>
			</div>
			<div id="appt_action">
				<div class="row cards-container">
				@include('home.cards.index', [
					"items" => $appointmentPendingCompleteSiteVisit, 
					"type" => "appt", 
					"info" => false, 
					"is_comp_site_visit" => true
					])
				@include('home.cards.index', [
					"items" => $appointmentpendingconfirmation, 
					"type" => "appt", 
					"info" => false,
					"is_comp_site_visit" => false
					])
				</div>
			</div>
			<div id="support">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $opensupport, "type" => "support"])
				</div>
			</div>
			<div id="unconfirmed_company">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $unconfirmedcompany, "type" => "company"])
				</div>
			</div>
			<div id="unsigned_company">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $unsignedcompany, "type" => "unsignedcompany"])
				</div>
			</div>
		</div>
		@if($informative)
			<div class="divider mb-20"></div>
		@endif
    @endif
    @if($informative)
		<h2 class="bm-pg-title">Pending</h2>
		<div class="items-count pending">
			@if($purchaseOrdersPending)
			<button class="btn item" type="button" data-link="po_pending" title="view pending purchase orders cards">
				<h3 class="item title-with-count">Purchase orders<span class="badge">{{ $purchaseOrdersPending }}</span></h3>
			</button>
			@endif
			@if($quotationsPending)
			<button class="btn item" type="button" data-link="qu_pending" title="view pending quotations cards">
				<h3 class="item title-with-count">Quotations<span class="badge">{{ $quotationsPending }}</span></h3>
			</button>
			@endif
			@if(count($inactivecompany) > 0)
			<button class="btn item" type="button" data-link="co_inactive" title="view pending companies cards">
				<h3 class="item title-with-count">Company<span class="badge">{{ count($inactivecompany) }}</span></h3>
			</button>
			@endif
			@if($appointmentsPending)
			<button class="btn item" type="button" data-link="appt_pending" title="view pending appointments cards">
				<h3 class="item title-with-count">Appointments<span class="badge">{{ $appointmentsPending }}</span></h3>
			</button>
			@endif
			@if($creditRequestsPending)
			<button class="btn item" type="button" data-link="cr_pending" title="view pending credit requests cards">
				<h3 class="item title-with-count">Credit requests<span class="badge">{{ $creditRequestsPending }}</span></h3>
			</button>
			@endif			
		</div>
		<div id="current_pending"></div>
		<div class="hidden">
			<div id="po_pending">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $posPendingCustomerInfo, "type" => "po", "view_link" => "view", "info" => true])
				@include('home.cards.index', ["items" => $posPendingVendorInfo, "type" => "po", "view_link" => "view", "info" => true])
				@include('home.cards.index', ["items" => $posPendingCreditInfo, "view_link" => "mview", "type" => "po", "info" => true])
				</div>
			</div>
			<div id="qu_pending">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $quotationsPendingVendorInfo, "type" => "qu", "view_link" => "view", "info" => true])
				</div>
			</div>
			<div id="co_inactive">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $inactivecompany, "type" => "co", "view_link" => "view", "info" => true])
				</div>
			</div>
			<div id="cr_pending">
				<div class="row cards-container">
				@include('home.cards.index', ["items" => $creditRequestPendingCustomerInfo, "type" => "cr", "info" => true])
				@include('home.cards.index', ["items" => $creditRequestPendingInfo, "type" => "cr", "info" => true])
				</div>
			</div>
			<div id="appt_pending">
				<div class="row cards-container">
					@include('home.cards.index', ["items" => $appointmentconfirmed, "type" => "appt", "info" => true, "is_comp_site_visit" => false])
					@include('home.cards.index', ["items" => $appointmenttoday, "type" => "appt", "info" => true, "is_comp_site_visit" => false])
					@include('home.cards.index', ["items" => $appointmenttomorrow, "type" => "appt", "info" => true, "is_comp_site_visit" => false])
					@include('home.cards.index', ["items" => $appointmentpending, "type" => "appt", "info" => true, "is_comp_site_visit" => false])
				</div>
			</div>
		</div>
    @endif
@endif
</div>
@stop
@push('scripts')	
    <script type="text/javascript">
    $(document).ready(function(){
        // initialize action cards container
        var action_btns = $('.action .btn');
        if(action_btns.length > 0){
            action_btns.first().addClass('active');
            populateSection('current_action', action_btns.first().data('link'));
        }
        
        // initialize pending cards container
        var pending_btns = $('.pending .btn');
        if(pending_btns.length > 0){
            pending_btns.first().addClass('active');
            populateSection('current_pending', pending_btns.first().data('link'));
        }
        
        $('.action .btn').click(function (){
            var old_active = $('.action .active');
            if(old_active){
                old_active.removeClass('active');
            }
            $(this).addClass('active');
            populateSection('current_action', $(this).data('link'));
        });

        $('.pending .btn').click(function (){
            var old_active = $('.pending .active');
            if(old_active){
                old_active.removeClass('active');
            }
            $(this).addClass('active');
            populateSection('current_pending', $(this).data('link'));
        });

        function populateSection(sectionId, targetId) {
            $("#" + sectionId).empty();
            $("#" + sectionId).html($("#" + targetId).html());
        }
    });
	</script>
@endpush
