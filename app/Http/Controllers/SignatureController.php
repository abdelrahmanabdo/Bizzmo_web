<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\JsonResponse as response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Helpers\RightSignatureHelper;

use App\Attachmenttype;
use App\Attachment;
use App\Creditrequestsecurity;
use App\Purchaseorder;

use App\Jobs\ProcessDownloadSignedDeliveryDocument;
use App\Jobs\ProcessDownloadSignedSecuritiesDocument;
use App\Jobs\ProcessBinvoice;
use App\Jobs\ProcessSinvoice;
use App\Jobs\ProcessUpdateCompanySignedField;
use App\Jobs\ProcessFetchContract;
use App\Jobs\Processcreditrequest;

use App\Helpers\TookanHelper;

class SignatureController extends Controller
{
	public function rightSignatureDocumentUpdate(Request $request)
	{
		// TO-DO Check the document new status and take the desired action
		\Log::debug($request);

		if ($request->documentState == "executed") {
			\Log::debug("Executed");
			$this->handleDeliveryDocumentSigned($request->id, 'rightsignature');
		}

		return response()->json(['response' => 'OK']);
	}

	public function rightSignatureSecuritiesDocumentUpdate(Request $request)
	{
		\Log::debug($request);

		if ($request->documentState == "executed") {
			\Log::debug("Executed");
			$this->handleSecurityDocumentSigned($request->id);

			// Download document
			ProcessDownloadSignedSecuritiesDocument::dispatch('rightsignature', $request->id);
		}

		return response()->json(['response' => 'OK']);
	}

	public function rightSignatureRequestUpdate(Request $request)
	{
		// TO-DO Check the document new status and take the desired action
		\Log::debug($request);

		if ($request->status == "completed") {
			\Log::debug("Completed");
			$attachment = Attachment::where('envelope', $request->id)->first();

			if (!$attachment)
				throw new \Exception("Getting attachment failed");

			// Save attachment id
			$attachment->status = "Pending Signature";
			$attachment->document = $request->document_template_id;
			$attachment->save();
		}

		return response()->json(['response' => 'OK']);
	}

	public function docuSignContractUpdate(Request $request) 
	{
		// xml -> json -> array
		$xmlFile = file_get_contents('php://input');
		$xmlToJson = json_encode(simplexml_load_string($xmlFile));
		$requestUpdate = json_decode($xmlToJson, true);
		$envelopeStatus = $requestUpdate['EnvelopeStatus'];
		$envelopeId = $envelopeStatus['EnvelopeID'];		
		
		if(!$envelopeId) 
			throw new \Exception('Envelope Id is empty');

		\Log::debug("Contract update: " . $envelopeId);
	
		$recipientStatuses = $envelopeStatus['RecipientStatuses']['RecipientStatus'];
		$status = $envelopeStatus['Status'];

		if (!isset($recipientStatuses[0]))
			throw new \Exception('Failed to get first recipient status');

		// Get recipients statuses
		if ($recipientStatuses[0]['RoutingOrder'] == 1) {
			$fRecipientStatus = $recipientStatuses[0]['Status'];
			$sRecipientStatus = $recipientStatuses[1]['Status'];
		} else {
			$fRecipientStatus = $recipientStatuses[1]['Status'];
			$sRecipientStatus = $recipientStatuses[0]['Status'];
		}
		
		// Company owner signed
		if ($fRecipientStatus == 'Completed' && $sRecipientStatus == 'Sent')
			//ProcessUpdateCompanySignedField::dispatch($envelopeId);
			ProcessUpdateCompanySignedField::dispatch($envelopeId, date('Y-m-d H:i:s'));

			DB::table('attachments')->where(['envelope' => $envelopeId])->update(['status' => $status]);
			ProcessFetchContract::dispatch($envelopeId);

		if ($status == 'Completed') {
			//$signed_on = $envelopeStatus['Signed'];
			//ProcessUpdateCompanySignedField::dispatch($envelopeId, $signed_on);

			ProcessFetchContract::dispatch($envelopeId);
		}
		
		return "OK";
	}

	public function docuSignSecurityUpdate(Request $request) 
	{
		$envelopeStatusDetails = $this->processXMLResponse();
		$envelopeId = $envelopeStatusDetails['EnvelopeID'];
		$envelopeStatusCode = $envelopeStatusDetails['Status'];
		\Log::Debug($envelopeId);
		
		if(!$envelopeId) 
			throw new \Exception('Envelope Id for security is empty');

		if($envelopeStatusCode == 'Completed') {
			\Log::debug("Completed");
			$this->handleSecurityDocumentSigned($envelopeId);

			// Download document
			ProcessDownloadSignedSecuritiesDocument::dispatch('docusign', $envelopeId);
		}
		return "OK";
	}

	public function docuSignDeliveryUpdate(Request $request) 
	{
		$envelopeStatusDetails = $this->processXMLResponse();
		$envelopeId = $envelopeStatusDetails['EnvelopeID'];
		$envelopeStatusCode = $envelopeStatusDetails['Status'];
		\Log::Debug($envelopeId);
		
		if(!$envelopeId) 
			throw new \Exception('Envelope Id for delivery is empty');

		if($envelopeStatusCode == 'Completed') {
			\Log::debug("Completed");
			$this->handleDeliveryDocumentSigned( 'docusign', $envelopeId);
		}
		return "OK";
	}
	private function handleSecurityDocumentSigned($documentId) 
	{
		$attachment = Attachment::where('document', $documentId)->first();

		if (!$attachment)
			throw new \Exception("Getting attachment failed");

		// Save attachment id
		$attachment->status = "Signed";
		$attachment->save();

		// Update security status
		$security = Creditrequestsecurity::where('id', $attachment->attachable_id)->first();

		if (!$security)
			throw new \Exception("Getting security failed");

		$security->document_id = $attachment->id;
		$security->document = $attachment->document;
		$security->status = 'signing_complete';
		$security->save();

		// Check if all securities completed and update company credit check
		if($security->creditrequest->isSecuritesCompleted())
			$security->creditrequest->approveCredit();
			Processcreditrequest::dispatch($security->creditrequest);
	}

	private function handleDeliveryDocumentSigned($provider, $documentId) 
	{
		$attachment = Attachment::where('document', $documentId)->first();

		if (!$attachment)
			throw new \Exception("Getting attachment failed");

		// Save attachment id
		$attachment->status = "Signed";
		$attachment->save();

		// Save signing date
		$po = Purchaseorder::where('id', $attachment->attachable_id)->first();

		if (!$po)
			throw new \Exception("Getting purchase order failed");

		$po->signed_at = date("Y-m-d H:i:s");
		$po->status_id = 22; // POD Signed
		$po->save();

		// Download document
		ProcessDownloadSignedDeliveryDocument::dispatch($provider, $documentId);

		// Process buyer and supplier invoices
		ProcessBinvoice::dispatch($po);
		ProcessSinvoice::dispatch($po);
	}

	private function processXMLResponse() {
		// xml -> json -> array
		$xmlFile = file_get_contents('php://input');
		\Log::Debug($xmlFile);
		$xmlToJson = json_encode(simplexml_load_string($xmlFile));
		$requestUpdate = json_decode($xmlToJson, true);
		\Log::Debug($requestUpdate['EnvelopeStatus']);
		return $requestUpdate['EnvelopeStatus'];
	}
	function tookanWebhook (Request $request) {
		$tookanHelper = new TookanHelper();
		//\Log::Debug($request);
		return $tookanHelper->tookanWebhook($request);
	}
	
	function tookanCreated (Request $request) {
		$tookanHelper = new TookanHelper();
		return $tookanHelper->tookanWebhook($request, 'created');
		
	}
}
