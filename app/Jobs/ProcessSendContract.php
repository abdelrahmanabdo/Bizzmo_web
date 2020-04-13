<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\DocuSignHelper;
use App\Company;
use App\Attachment;
use App\Attachmenttype;
use Auth;


class ProcessSendContract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;
    protected $docuSignHelper;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
        $this->docuSignHelper = new DocuSignHelper();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company = $this->company;
        // Check if company type is buyer
        if ($company->companytype_id == 1 || $company->companytype_id == 3) {
            $envelopeId = $this->docuSignHelper->sendContractEnvelope($company, true);
            $this->createAttachment($envelopeId, true);
        } 
        // Check if company type is supplier
        if ($company->companytype_id == 2 || $company->companytype_id == 3) {
            $envelopeId = $this->docuSignHelper->sendContractEnvelope($company);
            $this->createAttachment($envelopeId, false);
        }
    }

    private function createAttachment($envelopeId, $isBuyer) {
        $documentType = $isBuyer ? 'BUY' : 'SUP';
        $attachment  = new Attachment;
        $attachment->path = '/';
        $attachment->created_by = $this->company->created_by;
        $attachment->updated_by = $this->company->created_by;
        $attachment->description = 'Digital Signature';
        $attachment->attachable_type = 'company';			
        $attachment->attachable_id = $this->company->id;
        $attachment->attachmenttype_id = $isBuyer ? Attachmenttype::BUYER_CONTRACT : Attachmenttype::SUPPLIER_CONTRACT;
        $attachment->document = $documentType;
        $attachment->filename = $documentType . '_contract_' . $this->company->id . '_' . date('Y') . '-' . date('m') . '-' . date('d') . '.pdf';
        $attachment->envelope = $envelopeId;
        $attachment->save();
    }
}
