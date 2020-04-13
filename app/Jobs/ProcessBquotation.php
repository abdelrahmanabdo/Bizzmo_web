<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use DB;
use File;
use Mail;
use PDF;
use App\Attachment;
use App\Mail\Deliverymail;
use App\Quotation;


class ProcessBquotation implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $quotation;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($quotation)
	{
		$this->quotation = $quotation;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		// Get PO Data
		$qu = Quotation::with('company', 'company.city', 'company.city.country', 'vendor', 'shippingaddress', 'shippingaddress.city', 'shippingaddress.city.country', 'currency', 'paymentterm', 'incoterm', 'quotationitems', 'quotationitems.unit')->find($this->quotation->id);
		

		// Bizzmo quotation 
		$path = str_replace('\\', '/', storage_path()) . '/app/bquotation/' . date('Y') . '/' . date('m') . '/';
		if (!File::exists($path)) {
			File::makeDirectory($path, 0777, true, true);
		}

		// Save PDF
		$filename = uniqid() . 'bq-' . $qu->id . '.pdf';
		$pdf = PDF::loadView('pdfs/bquotation', ['quotation' => $qu]);
		$pdf->save($path . $filename);

		//Delete attachment of same version
		DB::table('attachments')->where('attachable_id', $this->quotation->id)->where('attachmenttype_id', Attachment::BIZZMO_QUOTATION)
			->where('attachable_type', 'quotation')->where('version', $this->quotation->version)->delete();
		// Save attachment
		$attachment = new Attachment;
		$attachment->path = 'bquotation/' . date('Y') . '/' . date('m') . '/' . $filename;
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Bizzmo quotation';
		$attachment->attachable_type = 'quotation';
		$attachment->attachable_id = $qu->id;
		$attachment->version = $this->quotation->version;
		$attachment->attachmenttype_id = Attachment::BIZZMO_QUOTATION;
		$attachment->filename = $filename;
		$attachment->save();

		// Process bizzmo quotation mail		
		$bquotation = $this->quotation->vendornumber;
		$data = ['company' => $this->quotation->company->companyname, 'quotation' => $this->quotation];
		Mail::send('emails.bquotation', $data, function ($message) use ($path, $filename, $bquotation) {
			$message->subject('Congratulations! You have received an Offer# ' . $bquotation);
			$message->to($this->quotation->company->email);
			$message->bcc(env('MAIL_ARCHIVE'));
			$message->attach($path . $filename);
		});
	}
}
