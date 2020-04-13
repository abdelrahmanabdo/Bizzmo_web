<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Company;
use App\Pickupaddress;
use App\Settings;
use Carbon\Carbon;

class SupplierDeliveryAndPickup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supplier:deliveryandpickup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add supplier delivery method and pickup address';

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
		$companies = Company::with('pickupaddresses', 'deliverytypes')->where('companytype_id', '<>', '1')->get();
		foreach ($companies as $company) {
			if ($company->pickupaddresses->count() == 0) {
				$pickupaddress = new Pickupaddress;
				$pickupaddress->company_id = $company->id;
				$pickupaddress->partyname =$company->companyname;
				$pickupaddress->address =$company->address;
				$pickupaddress->po_box =$company->pobox;
				$pickupaddress->phone =$company->phone;
				$pickupaddress->fax =$company->fax;
				$pickupaddress->city_id = $company->city->id;
				$pickupaddress->default = 1;
				$pickupaddress->created_by = $company->created_by;
				$pickupaddress->updated_by =$company->updated_by;
				$pickupaddress->save();
			}
			if ($company->deliverytypes->count() == 0) {
				$company->deliverytypes()->attach(1);
			}
		}
	}
}
