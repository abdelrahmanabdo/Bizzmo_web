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


class ProcessBizzmoPOreject implements ShouldQueue
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
	
		$number = $this->purchaseorder->vendornumber;
		$data = ['company' => $this->purchaseorder->vendor->companyname, 'number' => $number, 'purchaseorder' => $this->purchaseorder];
		Mail::send('emails.bizzmoporeject', $data, function ($message) use ( $number) {
			$message->subject('Your Bizzmo Order # ' . $number . ' has been cancelled!');
			$message->to($this->purchaseorder->vendor->email);
			$message->bcc(env('MAIL_ARCHIVE'));
		});

	}
}
