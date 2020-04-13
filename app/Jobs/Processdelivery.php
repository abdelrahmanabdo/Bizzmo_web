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

class Processdelivery implements ShouldQueue
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
		$provider = env('SIGNATURE_PROVIDER');
		if(!$provider) 
			throw new \Exception("No signature provider found");
			
		// Check if the PO is pending buyer POD signature
		if($this->purchaseorder->status_id != 15)
			return true;		
		
		// Get PO Data
		$po = Purchaseorder::with('company', 'company.city', 'company.city.country', 'vendor', 'shippingaddress', 'shippingaddress.city', 'shippingaddress.city.country', 'currency', 'paymentterm', 'incoterm', 'purchaseorderitems', 'purchaseorderitems.unit')->find($this->purchaseorder->id);
		
		// Delivery
		$path = str_replace('\\', '/', storage_path()) . '/app/delivery/' . date('Y') . '/' . date('m') . '/';
		if (!File::exists($path)) {
			File::makeDirectory($path, 0777, true, true);
		}
		
		// Save PDF
		$filename = uniqid() . 'dn-' . $po->id . '.pdf';
		$pdf = PDF::loadView('pdfs/delivery', ['purchaseOrder' => $po, 'provider' => $provider]);
		$pdf->save($path . $filename);
		
		// Save attachment
		$authcode = str_random(20);
		$deliveryPath = 'delivery/' . date('Y') . '/' . date('m') . '/' . $filename;
		$attachment = new Attachment;
		$attachment->path = $deliveryPath;
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Delivery note';
		$attachment->attachable_type = 'purchaseorder';
		$attachment->attachable_id = $po->id;
		$attachment->attachmenttype_id = 14; //Delivery note
		$attachment->filename = $filename;
		$attachment->authcode = $authcode;
		$attachment->save();
		
		// Process delivery document to get company signature
		$attachmentPath = storage_path('app/' . $deliveryPath);
			
		if ($po->deliverytype_id == 1) {
			$tookanHelper = new TookanHelper();
			$response = $tookanHelper->createPickupDelivery($po->id);
			
			//$decoded = json_decode($response['response'], true);
			var_dump($response);
			foreach($response as $key => $data) {
				//echo $key, PHP_EOL;
				if ($key == 'response') {
					$decoded = json_decode($data, true);
					$job_id = $decoded['data']['job_id'];
					$pickup_job_id = $decoded['data']['pickup_job_id'];
					$delivery_job_id = $decoded['data']['delivery_job_id'];
					$pickup_tracking_link = $decoded['data']['pickup_tracking_link'];
					$delivery_tracking_link = $decoded['data']['delivery_tracing_link'];
				}				
			}
			$po->job_id = $job_id;
			$po->pickup_job_id = $pickup_job_id;
			$po->delivery_job_id = $delivery_job_id;
			$po->pickup_tracking_link = $pickup_tracking_link;
			$po->delivery_tracking_link = $delivery_tracking_link;
			$po->save();
			$this->createAttachment($po, $job_id);
		} else {			
			switch($provider) {
				case 'docusign':
					$docuSignHelper = new DocuSignHelper();
					$envelopeId = $docuSignHelper->sendDeliveryEnvelope($po, $attachmentPath);
					if($envelopeId)
						$this->createAttachment($po, $envelopeId);
					break;
				
				case 'rightsignature':
					$rightsignature = new RightSignatureHelper();
					$rightsignature->sendDeliveryDocument($po, $attachmentPath);
					break;
			}
		}
	}

	private function createAttachment($po, $envelopeId) {
		$attachment = new Attachment();
		$attachment->path = '/';
		$attachment->created_by = $po->created_by;
		$attachment->updated_by = $po->created_by;
		$attachment->description = 'Digital Signature - Signed Delivery Document';
		$attachment->attachable_type = 'purchaseorder';
		$attachment->attachable_id = $po->id;
		$attachment->status = 'pending_for_file';
		$attachment->attachmenttype_id = Attachmenttype::SIGNED_DELIVERY_DOCUMENT;
		$attachment->filename = "";
		$attachment->envelope = $attachment->document =  $envelopeId;
		$attachment->save();
	}
}
