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
use App\Purchaseorder;
use App\Mail\Deliverymail;


class ProcessDeliveryMail implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $args;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		$this->args = $args;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$id = $this->args['id'];
		$job_type = $this->args['job_type'];
		$event = $this->args['event'];
		$po = Purchaseorder::with('company', 'company.city', 'company.city.country', 'vendor', 'shippingaddress', 'shippingaddress.city', 'shippingaddress.city.country', 'currency', 'paymentterm', 'incoterm', 'purchaseorderitems', 'purchaseorderitems.unit')->find($id);
		//var_dump($this->args);
		//return true;
		//die;

		// Process bizzmo delivery mail
		if ($job_type == 'Pickup' && $event == 'Started') {
			$company = $po->vendor->companyname;
			$subject = 'Your Bizzmo Order # ' . $po->vendornumber . ' pick up has started!';
			$email = $po->vendor->email;
		} elseif ($job_type == 'Pickup' && $event == 'Successful') {
			$company = $po->company->companyname;
			$subject = 'Your Bizzmo Order # ' . $po->company_id . '-' . $po->number . ' has shipped!';
			$email = $po->company->email;
		} elseif ($job_type == 'Delivery' && $event == 'Successful') {
			$company = $po->vendor->companyname;
			$subject = 'Your Bizzmo Order # ' . $po->vendornumber . ' has been delivered!';
			$email = $po->vendor->email;
			$data = ['company' => $company, 'job_type' => $job_type, 'event' => $event, 'purchaseorder' => $po];
			Mail::send('emails.tookan', $data, function ($message) use ($subject, $email, $po) {
				$message->subject($subject);
				$message->to($email);
				$message->bcc(env('MAIL_ARCHIVE'));
			});
			$company = $po->company->companyname;
			$subject = 'Your Bizzmo Order # ' . $po->company_id . '-' . $po->number . ' has been delivered!';
			$email = $po->company->email;
		}
		$data = ['company' => $company, 'job_type' => $job_type, 'event' => $event, 'purchaseorder' => $po];
		Mail::send('emails.tookan', $data, function ($message) use ($subject, $email, $po) {
			$message->subject($subject);
			$message->to($email);
			$message->bcc(env('MAIL_ARCHIVE'));
		});
	}
}
