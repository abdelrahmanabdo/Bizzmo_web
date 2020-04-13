<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PoAddress;
use App\Purchaseorder;
use App\Quotation;
use App\Settings;
use Carbon\Carbon;

class POfillpickupAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:fillpickupaddress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add pickup address to existing POs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $purchaseorders = Purchaseorder::all();
		foreach ($purchaseorders as $purchaseorder) {
			$pickupaddresses = $purchaseorder->vendor->pickupaddresses();
			//var_dump($pickupaddresses->first()->id);
			$purchaseorder->pickupaddress_id = $pickupaddresses->first()->id;
			$purchaseorder->deliverytype_id = 3;
			$purchaseorder->pickupbydate = $purchaseorder->date;
			$purchaseorder->pickupbytime_id = 32;
			$purchaseorder->deliverbydate = $purchaseorder->date;
			$purchaseorder->deliverbytime_id = 40;
			$purchaseorder->save();
		}
		
		$quotations = Quotation::all();
		foreach ($quotations as $quotation) {
			$pickupaddresses = $quotation->vendor->pickupaddresses();
			//var_dump($pickupaddresses->first()->id);
			$quotation->pickupaddress_id = $pickupaddresses->first()->id;
			$quotation->deliverytype_id = 3;
			$quotation->pickupbydate = $quotation->date;
			$quotation->pickupbytime_id = 32;
			$quotation->deliverbydate = $quotation->date;
			$quotation->deliverbytime_id = 40;
			$quotation->save();
		}
    }
}
