<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PoAddress;
use App\Purchaseorder;
use App\Quotation;
use App\Settings;
use Carbon\Carbon;

class POaddSupplierAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:supplieraddress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add supplier address to existing POs';

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
			if ($purchaseorder->getBillToAddress() && !$purchaseorder->getSupplierAddress()) {
				$shippingcity = $purchaseorder->shippingaddress['city'] ? $purchaseorder->shippingaddress['city']['cityname'] : $purchaseorder->shippingaddress['city_name'];
				$shippingcountry = $purchaseorder->shippingaddress['city'] ? $purchaseorder->shippingaddress['city']['country']['countryname'] : $purchaseorder->shippingaddress['country_name'];
				$shippingdeliverycity = $purchaseorder->shippingaddress->deliverycity->cityname;
				$shippingdeliverycountry = $purchaseorder->shippingaddress->deliverycity->country->countryname;
				$supplier = new PoAddress([
					"po_id" => $purchaseorder->id,
					"type" => PoAddress::SUPPLIER,
					"party_name" => $purchaseorder->vendor->companyname,
					"city" => $purchaseorder->vendor->city->cityname,
					"country" => $purchaseorder->vendor->city->country->countryname,
					"address" => $purchaseorder->vendor->address,
					"po_box" => $purchaseorder->vendor->pobox,
					"phone" => $purchaseorder->vendor->phone,
					"fax" => $purchaseorder->vendor->fax,
					"tax" => $purchaseorder->vendor->tax
				]);
				$supplier->save();
			}
		}
		
		$i = Purchaseorder::where('salesorder', '<>', 0)->max('salesorder');
		$pos = Purchaseorder::where('salesorder', 0)->get();
		foreach ($pos as $purchaseorder) {
			$i = $i + 1;
			$purchaseorder->salesorder = $i;
			$purchaseorder->save();
		}
		//update sales order number settings 
		$setting  = Settings::find(Settings::SALES_ORDER);
		$setting->value = $i;
		$setting->save();
		
		//quotations
		$quotations = Quotation::all();
		foreach ($quotations as $quotation) {
			$quotation->saveAddresses();
		}
    }
}
