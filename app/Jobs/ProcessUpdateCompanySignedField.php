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
use DB;

use App\Jobs\Processcompany;
use App\Jobs\Processvendor;

class ProcessUpdateCompanySignedField implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attachment;
	protected $signed_on;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($envelopeId, $signed_on = null)
    {
        $this->attachment = Attachment::where('envelope', $envelopeId)->first();
		$this->signed_on = $signed_on;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $attachmentTypeId = $this->attachment->attachmenttype_id;
		$company = Company::find($this->attachment->attachable_id);
        if ($attachmentTypeId == Attachmenttype::SUPPLIER_CONTRACT) {
            DB::table('companies')->where('id', $this->attachment->attachable_id)->update(['vendor_signed' => true]);
			Processvendor::dispatch($company);
        } elseif ($attachmentTypeId == Attachmenttype::BUYER_CONTRACT) {
            DB::table('companies')->where('id', $this->attachment->attachable_id)->update(['customer_signed' => true, 'signed_on' => date("Y-m-d H:i:s", strtotime(str_replace('T', '', $this->signed_on)))]);
			Processcompany::dispatch($company);
        }
    }
}
