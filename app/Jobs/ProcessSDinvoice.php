<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use File;
use Mail;
use PDF;
use App\Attachment;
use App\Creditrequestsecurity;


class ProcessSDinvoice implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $creditrequestsecurity;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($creditrequestsecurity)
	{
		$this->creditrequestsecurity = $creditrequestsecurity;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{		
		$security = Creditrequestsecurity::find($this->creditrequestsecurity->id);

		// Buyer invoice
		$path = str_replace('\\', '/', storage_path()) . '/app/sdinvoice/' . date('Y') . '/' . date('m') . '/';
		if (!File::exists($path)) {
			File::makeDirectory($path, 0777, true, true);
		}

		// Save PDF
		$filename = uniqid() . 'sdi-' . $security->id . '.pdf';
		$pdf = PDF::loadView('pdfs/sdinvoice', ['security' => $security]);
		$pdf->save($path . $filename);

		// Save attachment
		$attachment = new Attachment;
		$attachment->path = 'sdinvoice/' . date('Y') . '/' . date('m') . '/' . $filename;
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Buyer invoice';
		$attachment->attachable_type = 'creditrequestsecurity';
		$attachment->attachable_id = $security->id;
		$attachment->attachmenttype_id = 15; //Buyer invoice
		$attachment->filename = $filename;
		$attachment->save();

		$data = ['company' => $this->creditrequestsecurity->creditrequest->company->companyname, 'number' => $this->creditrequestsecurity->inv_no];
		Mail::send('emails.sdinvoice', $data, function ($message) use ($path, $filename) {
			$message->subject(config('app.companyname') . ' invoice');
			$message->to($this->creditrequestsecurity->creditrequest->company->email);
			$message->attach($path . $filename);
		});

		ProcessBuyerDepositSap::dispatch($this->creditrequestsecurity);
	}
}
