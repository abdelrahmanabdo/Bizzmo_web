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
use App\Purchaseorder;


class ProcessBinvoice implements ShouldQueue
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
		$po = Purchaseorder::with('company', 'company.city', 'company.city.country', 'vendor', 'shippingaddress', 'shippingaddress.city', 'shippingaddress.city.country', 'currency', 'paymentterm', 'incoterm', 'purchaseorderitems', 'purchaseorderitems.unit')->find($this->purchaseorder->id);

		// Buyer invoice
		$path = str_replace('\\', '/', storage_path()) . '/app/binvoice/' . date('Y') . '/' . date('m') . '/';
		if (!File::exists($path)) {
			File::makeDirectory($path, 0777, true, true);
		}

		// Save PDF
		$filename = uniqid() . 'bi-' . $po->id . '.pdf';
		$pdf = PDF::loadView('pdfs/binvoice', ['purchaseOrder' => $po]);
		$pdf->save($path . $filename);

		//Delete attachment of same version
		DB::table('attachments')->where('attachable_id', $po->id)->where('attachmenttype_id', Attachment::BUYER_INVOICE)
			->where('attachable_type', 'purchaseorder')->where('version', $po->version)->delete();
		// Save attachment
		$attachment = new Attachment;
		$attachment->path = 'binvoice/' . date('Y') . '/' . date('m') . '/' . $filename;
		$attachment->created_by = 1;
		$attachment->updated_by = 1;
		$attachment->description = 'Buyer invoice';
		$attachment->attachable_type = 'purchaseorder';
		$attachment->attachable_id = $po->id;
		$attachment->version = $po->version;
		$attachment->attachmenttype_id = Attachment::BUYER_INVOICE;
		$attachment->filename = $filename;
		$attachment->save();

		$data = ['company' => $this->purchaseorder->company->companyname, 'number' => $this->purchaseorder->binvoice];
		Mail::send('emails.binvoice', $data, function ($message) use ($path, $filename) {
			$message->subject(config('app.companyname') . ' invoice');
			$message->to($this->purchaseorder->company->email);
			$message->bcc(env('MAIL_ARCHIVE'));
			$message->attach($path . $filename);
		});

		ProcessBuyerDeliverySap::dispatch($this->purchaseorder);
	}
}
