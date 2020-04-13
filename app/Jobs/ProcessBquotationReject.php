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


class ProcessBquotationReject implements ShouldQueue
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

		// Process supplier quotation mail		
		$bquotation = $this->quotation->vendornumber;
		$data = ['company' => $this->quotation->company->companyname, 'quotation' => $this->quotation];
		Mail::send('emails.bquotationreject', $data, function ($message) use ($bquotation) {
			$message->subject('Your Bizzmo Offer # ' . $bquotation . ' has been cancelled!');
			$message->to($this->quotation->company->email);
			$message->bcc(env('MAIL_ARCHIVE'));
		});
	}
}
