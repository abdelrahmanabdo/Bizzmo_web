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
use Storage;

use App\Attachment;
use App\Purchaseorder;


class ProcessPOconfirmation implements ShouldQueue
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

		//Bizzmo PO (to supplier)
		$number = $this->purchaseorder->vendornumber;
		$attachment = Attachment::where('attachable_id', $this->purchaseorder->id)->where('attachable_type', 'purchaseorder')
		->where('version',$this->purchaseorder->version)->where('attachmenttype_id', Attachment::BIZZMO_PO)->get()->first();
		$data = ['company' => $this->purchaseorder->vendor->companyname, 'number' => $number, 'purchaseorder' => $this->purchaseorder];
		
		Mail::send('emails.bizzmopoconf', $data, function ($message) use ($attachment, $number) {
			$message->subject('Bizzmo Order Confirmation # ' . $number);
			$message->to($this->purchaseorder->vendor->email);
			$message->bcc(env('MAIL_ARCHIVE'));
			$message->attach(Storage::path($attachment->path));
		});
		
		//Buyer PO confirmation (to Buyer)
		$number = $this->purchaseorder->company_id . '-' . $this->purchaseorder->number;
		$attachment = Attachment::where('attachable_id', $this->purchaseorder->id)->where('attachable_type', 'purchaseorder')
		->where('version',$this->purchaseorder->version)->where('attachmenttype_id', Attachment::BUYER_PO)->get()->first();
		$data = ['company' => $this->purchaseorder->company->companyname, 'number' => $number, 'purchaseorder' => $this->purchaseorder];
		Mail::send('emails.buyerpoconf', $data, function ($message) use ($attachment, $number) {
			$message->subject('Bizzmo Order Confirmation # ' . $number);
			$message->to($this->purchaseorder->company->email);
			$message->bcc(env('MAIL_ARCHIVE'));
			$message->attach(Storage::path($attachment->path));
		});

	}
}
