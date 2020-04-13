<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use DB;
use App\Purchaseorder;
use App\Helpers\SapConnection;
use App\Helpers\SapCustomerReport;
use Carbon\Carbon;

class Processpocredit implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $purchaseorder;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($purchaseorder)
	{
		$this->purchaseorder = $purchaseorder;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		// Check if the PO is pending credit decision
		if ($this->purchaseorder->status_id != 4)
			return true;

		$po = $this->purchaseorder;
		$company = $po->company;
		$reason_for_rejection = "";

		if ($po->paymentterm_id == 0) {
			ProcessBizzmoPO::dispatch($po);
			$po->status_id = 7;
			$po->save();
		} else {
			if ($company->sapnumber) {
				$args = [
					'COMPANYCODE' => env("SAP_COMPANY_CODE"),
					'CUSTOMER' => $company->sapnumber,
					'DATE_TO' => date("Ymd")
				];
				// Get customer report
				$report = new SapCustomerReport($args);
				
				// Calculate delta limit
				$creditLimit = $company->creditlimit;
				$balance = $report->getBalance();
				$deltaLimit = $creditLimit - $balance;
				$openPOsValue = $company->creditpos->sum('grand_total');
				$diff = $openPOsValue - $deltaLimit;

				// Check open POs value not exceedes the delta limit
				if ($diff <= 0 && !$report->isOverDueItems()) {
					// Approve
					ProcessBizzmoPO::dispatch($po);
					$po->status_id = 7;
				} 

				// Reasons for rejections
				if ($report->isOverDueItems()) {
					// Reject
					$po->status_id = 14;
					$oldestOpenItem = date("d/m/Y", strtotime($report->getOldestOpenItem()));
					$reason_for_rejection .= "Overdue date ($oldestOpenItem),";
				}
				if ($diff > 0) {
					// Reject
					$po->status_id = 14;
					$reason_for_rejection .= "Exceedes the credit limit ($diff),";
				}

				// Save PO
				$po->reason_for_rejection = $reason_for_rejection;
				$po->save();
				// if ($po->status_id == 14) {
					// ProcessBuyerPOreject::dispatch($po);
					// if ($po->accepted_by != null) {
						// ProcessBizzmoPOreject::dispatch($po);
					// }
				// }
				try {
					broadcast(new \App\Events\PoStatusUpdate($po));
				} catch(\Exception $e) {
					\Log::warning("Fail to fire the event");
				}
			}
		}
	}
}
