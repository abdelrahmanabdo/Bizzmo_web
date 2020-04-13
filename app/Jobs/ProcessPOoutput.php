<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Purchaseorder;


class ProcessPOoutput implements ShouldQueue
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
		//Create buyer and bizzmo PDF
		$this->purchaseorder->createAttachment();		
	}
}
