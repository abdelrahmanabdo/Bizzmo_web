<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use DB;
use Mail;
use App\Mail\Creditrequestsecuritymail;
use App\Creditrequest;
use App\Attachmenttype;
use App\Attachment;
use App\Helpers\RightSignatureHelper;
use App\Helpers\DocuSignHelper;
use App\Creditrequestsecurity;


class Processcreditrequestsecurities implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $creditrequest;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($provider, $creditrequest)
	{
		$this->creditrequest = $creditrequest;
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
			
		// Personal guarantee, Corporate guarantee, Promissory note
		$securities = Creditrequestsecurity::where('creditrequest_id', $this->creditrequest->id)
				->where('securitytype_id', '!=' , 4)
				->where('securitytype_id', '!=' , 6)
				->where('securitytype_id', '!=' , 7)
				->get();
		foreach ($securities as $security) {
			$data = ['company' => $security->signername, 'verificationcode' => $security->authcode . $this->creditrequest->id, 'document' => 'Security document(s)'];

			$securityPath = "";
			switch ($security->securitytype_id) {
				case 1:
					$securityPath = "personal_guarantee.pdf";
					break;

				case 2:
					$securityPath = "promissory_personal.pdf";
					break;

				case 3:
					$securityPath = "promissory_entity.pdf";
					break;
					
				case 8:
					$securityPath = "dateauth.pdf";
					break;
					
				case 5:
					$securityPath = "corporate_guarantee.pdf";
					break;

				default:
					break;
			}
			
			$attachmentPath = storage_path('app/security/' . $provider . '/' . $securityPath);
			
			// Process delivery document to get company signature
			switch($provider) {
				case 'docusign':
					$docuSignHelper = new DocuSignHelper();
					$envelopeId = $docuSignHelper->sendSecurityEnvelope($security, $attachmentPath);
					if($envelopeId)
						$this->createAttachment($security, $envelopeId);
					break;
				
				case 'rightsignature':
					$rightsignature = new RightSignatureHelper();
					$rightsignature->sendSecurityDocument($security, $attachmentPath);
					break;
			}
		}

		// Security Cheque
		$securities = DB::table('creditrequestsecurities')
			->select('signername', 'signeremail', 'amount', 'securitytype_id')
			->distinct()
			->where('creditrequest_id', $this->creditrequest->id)
			->whereIn('securitytype_id', [4, 6])
			->get();

			foreach ($securities as $security) {
				if($security->securitytype_id == 4){
					$data = ['name' => $security->signername, 
						'verificationcode' => '', 
						'document' => 'Security check', 
						'amount' => $security->amount, 
						'id' => $this->creditrequest->id, 
						'currency' => $this->creditrequest->currency->abbreviation
						];
					Mail::send('emails.creditrequestsecurities', $data, function($message) use ($security) {
						$message->subject(config('app.companyname') . ' Credit Request Security Check');
						$message->to($security->signeremail);
					});
				}elseif($security->securitytype_id == 6){
					$data = ['name' => $security->signername, 
						'verificationcode' => '', 
						'document' => 'Margin Deposit Cash', 
						'amount' => $security->amount, 
						'id' => $this->creditrequest->id, 
						'currency' => $this->creditrequest->currency->abbreviation
						];
					Mail::send('emails.creditrequestsecurities', $data, function($message) use ($security) {
						$message->subject(config('app.companyname') . ' Credit Request Margin Deposit Cash');
						$message->to($security->signeremail);
					});
				}
			}	
	}

	private function createAttachment($security, $envelopeId) {
		$attachmentType = "";
		switch ($security->securitytype_id) {
		case 1:
			$attachmentType = Attachmenttype::PERSONAL_GURANTEE_DOCUMENT;
			break;

		case 2:
			$attachmentType = Attachmenttype::CORPORATE_GURANTEE_DOCUMENT;
			break;

		case 3:
			$attachmentType = Attachmenttype::PROMISSORY_NOTE_DOCUMENT;
			break;		
			
		case 5:
			$attachmentType = Attachmenttype::CORPORATE_GURANTEE_DOCUMENT;
			break;
			
		case 8:
			$attachmentType = Attachmenttype::CHECK_AUTH_DOCUMENT;
			break;
			
		default:
			break;
		}

		$attachment = new Attachment();
		$attachment->path = '/';
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Digital Signature - Signed Check Authorization Document';
		$attachment->attachable_type = 'creditrequestsecurity';
		$attachment->attachable_id = $security->id;
		$attachment->status = 'sent';
		$attachment->attachmenttype_id = $attachmentType;
		$attachment->filename = "";
		$attachment->envelope = $attachment->document = $envelopeId;
		$attachment->save();
	}
}
