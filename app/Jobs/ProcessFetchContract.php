<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Company;
use App\Attachment;
use App\Attachmenttype;
use App\Helpers\DocuSignHelper;
use Auth;
use Storage;
use Log;

class ProcessFetchContract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $envelopeId;
    protected $docuSignHelper;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($envelopeId)
    {
        $this->envelopeId = $envelopeId;
        $this->docuSignHelper = new DocuSignHelper();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->envelopeId)
            return;

        $contractPdfContents = $this->docuSignHelper->getSignedDocument($this->envelopeId);
        $attachment = Attachment::where('envelope', $this->envelopeId)->first();
        if (isset($attachment)){
            $pdfName = $attachment->filename;
            $storeLocation = 'contracts/' . $pdfName;
            $isSaved = Storage::put($storeLocation, $contractPdfContents);
            if ($isSaved) {
                $attachment->path = $storeLocation;
                $attachment->save();
            }
        }
    }
}
