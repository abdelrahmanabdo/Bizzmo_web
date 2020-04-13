<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Attachment;
use Auth;
use App\Helpers\RightSignatureHelper;
use App\Helpers\DocuSignHelper;
use Illuminate\Support\Facades\Storage;


class ProcessDownloadSignedSecuritiesDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $provider;    
    protected $documentId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($provider, $documentId)
    {
        $this->provider = $provider;
        $this->documentId = $documentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $documentId = $this->documentId;

        // Download document
        switch($this->provider) {
            case 'docusign':
                $docuSignHelper = new DocuSignHelper();
                $fileContent = $docuSignHelper->getSignedDocument($documentId);
                break;
            
            case 'rightsignature':
                $rightSignature = new RightSignatureHelper();
                $fileContent = $rightSignature->getSignedDocument($documentId);
                break;

            default:
                throw new \Exception("No provider found");
        }
        $year = date('Y');
        $month = date('m');
        $fileName = "$documentId.pdf";
        $filePath = "signed_securities_document/$year/$month/$fileName";
        
        // Save to file system
        Storage::put($filePath, $fileContent);

        // Update attachment
        $this->updateAttachment($filePath, $fileName);
        return true;
    }

    private function updateAttachment($filePath, $fileName)
    {
        $attachment = Attachment::where('document', $this->documentId)->first();

        if (!$attachment)
            throw new \Exception("Getting attachment failed");

        // Save attachment id
        $attachment->status = "Signed & Downloaded";
        $attachment->path = $filePath;
        $attachment->filename = $fileName;
        $attachment->save();
    }
}
