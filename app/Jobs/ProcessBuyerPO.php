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


class ProcessBuyerPO implements ShouldQueue
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
	
		$number = $this->purchaseorder->company_id . '-' . $this->purchaseorder->number;
		$attachment = Attachment::where('attachable_id', $this->purchaseorder->id)->where('attachable_type', 'purchaseorder')
		->where('version',$this->purchaseorder->version)->where('attachmenttype_id', Attachment::BUYER_PO)->get()->first();
		$data = ['company' => $this->purchaseorder->company->companyname, 'number' => $number, 'purchaseorder' => $this->purchaseorder];
		if ($this->purchaseorder->changed) {
			$this->purchaseorder->changed = false;
			$this->purchaseorder->save();
			Mail::send('emails.buyerpochange', $data, function ($message) use ($attachment, $number) {
				$message->subject('Your Bizzmo Order # ' . $number . ' has been modified!');
				$message->to($this->purchaseorder->company->email);
				$message->bcc(env('MAIL_ARCHIVE'));
				$message->attach(Storage::path($attachment->path));
			});
			//to supplier
			$creditreleasedbefore = 0;
			foreach ($this->purchaseorder->audits as $audit) {
				if (array_key_exists('status_id', $audit->old_values)) {
					if ($audit->old_values['status_id'] == 7) {
						$creditreleasedbefore = 1;
					}
				}
			}
			//if the po was credit released before, then send to supplier
			if ($creditreleasedbefore) {
				$number = $this->purchaseorder->vendornumber;
				$data = ['company' => $this->purchaseorder->vendor->companyname, 'number' => $number, 'purchaseorder' => $this->purchaseorder];			
				Mail::send('emails.buyerpochangetosup', $data, function ($message) use ($attachment, $number) {
					$message->subject('Your Bizzmo Order # ' . $number . '  has changed!');
					$message->to($this->purchaseorder->vendor->email);
					$message->bcc(env('MAIL_ARCHIVE'));
				});
			}
		} else {
			Mail::send('emails.buyerpo', $data, function ($message) use ($attachment, $number) {
				$message->subject('We have received your order # ' . $number);
				$message->to($this->purchaseorder->company->email);
				$message->bcc(env('MAIL_ARCHIVE'));
				$message->attach(Storage::path($attachment->path));
			});
		}		

	}
}
