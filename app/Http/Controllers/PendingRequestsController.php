<?php
namespace App\Http\Controllers;

use Auth;
use Gate;

use App\Appointment;
use App\Creditrequest;

class PendingRequestsController extends Controller {
    public function view() {
		$pendingCustomerPOs = $pendingVendorPOs = $pendingCreditPOs = $pendingCRs = $pendingCustomerCRs = $pendingCustomerAppointments = $pendingCreditAppointments = [];
		
		// Get pending POs
		if (Gate::allows('po_sc')) {
			$pendingCustomerPOs = Auth::user()->pospendingcustomer();
			$pendingVendorPOs = Auth::user()->pospendingvendor();
		}
		
		if (Gate::allows('po_rc'))
			$pendingCreditPOs = Auth::user()->pospendingcredit();
		
		// pendint appointments for customer
		if (Gate::allows('cr_vw') || Gate::allows('cr_ch')) 
			$pendingCustomerAppointments = Auth::user()->pendingcustomerappointments();
		
		// Get pending CRs
		if (Gate::allows('cr_ap')) {
			$pendingCRs = Creditrequest::where('creditstatus_id', '2')->get();
			$date=date_create(date('Ymd'));
			$from = date_format($date,"Y-m-d");
			$pendingCreditAppointments = Appointment::where('date', '>=', $from)->whereIn('status_id', [1, 8])->get();
		}
		
		if (Gate::allows('cr_cr') || Gate::allows('cr_ch') || Gate::allows('cr_vw'))
			$pendingCustomerCRs = Auth::user()->creditrequestpendingcustomer();
		
		$pendingRequestsCount = count($pendingCustomerPOs) + count($pendingVendorPOs) + count($pendingCreditPOs) + count($pendingCRs) + 
		count($pendingCustomerCRs) + count($pendingCustomerAppointments) + count($pendingCreditAppointments);
		
		return view('pendingRequests.index', [
			'pendingCustomerPOs' => $pendingCustomerPOs,
			'pendingVendorPOs' => $pendingVendorPOs,
			'pendingCreditPOs' => $pendingCreditPOs,
			'pendingCRs' => $pendingCRs,
			'pendingCustomerCRs' => $pendingCustomerCRs,
			'pendingRequestsCount' => $pendingRequestsCount,
			'pendingCustomerAppointments' => $pendingCustomerAppointments,
			'pendingCreditAppointments' => $pendingCreditAppointments
		]);
	}
}