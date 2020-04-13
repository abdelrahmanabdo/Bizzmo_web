<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use File;
use PDF;
use App\Purchaseorder;
use App\Attachmenttype;
use App\Attachment;
use App\Helpers\RightSignatureHelper;
use App\Helpers\DocuSignHelper;
use App\Helpers\TookanHelper;

class ProcessDeliveryCancel implements ShouldQueue
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
					
		// Get PO Data
		$po = Purchaseorder::with('company', 'company.city', 'company.city.country', 'vendor', 'shippingaddress', 'shippingaddress.city', 'shippingaddress.city.country', 'currency', 'paymentterm', 'incoterm', 'purchaseorderitems', 'purchaseorderitems.unit')->find($this->purchaseorder->id);
		
		// Void the delivery (docusign or Tookan)
		if ($po->deliverytype_id == 1) {
			$tookanhelper  = new TookanHelper();
			$result = $tookanhelper->cancelJob($po->delivery_job_id);
			$deliverystatus  = $result['status'];
			$result = $tookanhelper->cancelJob($po->pickup_job_id);
			$pickupstatus  = $result['status'];
			if ($deliverystatus != '200' && $deliverystatus != '201') {
				throw new \Exception("Error deleteing Tookan delivery no. " . $po->delivery_job_id);
			}
			if ($pickupstatus != '200' && $pickupstatus != '201') {
				throw new \Exception("Error deleteing Tookan pickup no. " . $po->pickup_job_id);
			}
		} elseif ($po->deliverytype_id == 2) {			
			$attachments = Attachment::where('attachable_id', $po->id)->where('attachable_type', 'purchaseorder')->where('attachmenttype_id', Attachmenttype::SIGNED_DELIVERY_DOCUMENT)->get();
			$docuSignHelper = new DocuSignHelper();
			$result = $docuSignHelper->voidDocument($attachments->first()->envelope);
			var_dump($result);
		}
	}
}
