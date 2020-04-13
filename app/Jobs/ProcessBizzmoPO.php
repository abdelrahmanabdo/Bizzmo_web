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


class ProcessBizzmoPO implements ShouldQueue
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

		if ($this->purchaseorder->changed) {
			$this->purchaseorder->changed = false;
			$this->purchaseorder->save();
			Mail::send('emails.bizzmopochange', $data, function ($message) use ($attachment, $number) {
				$message->subject('Your Bizzmo Order # ' . $number . ' has been modified!');
				$message->to($this->purchaseorder->vendor->email);
				$message->bcc(env('MAIL_ARCHIVE'));
				$message->attach(Storage::path($attachment->path));
			});
			//to buyer
			$number = $this->purchaseorder->company_id . '-' . $this->purchaseorder->number;
			$data = ['company' => $this->purchaseorder->company->companyname, 'number' => $number, 'purchaseorder' => $this->purchaseorder];
			Mail::send('emails.bizzmopochangetobuyer', $data, function ($message) use ($number) {
				$message->subject('Your Bizzmo Order # ' . $number . ' has changed!');
				$message->to($this->purchaseorder->company->email);
				$message->bcc(env('MAIL_ARCHIVE'));
			});
		} else {
			Mail::send('emails.bizzmopo', $data, function ($message) use ($attachment, $number) {
				$message->subject('Congratulations! You have received an order# ' . $number);
				$message->to($this->purchaseorder->vendor->email);
				$message->bcc(env('MAIL_ARCHIVE'));
				$message->attach(Storage::path($attachment->path));
			});
		}
	}
}
