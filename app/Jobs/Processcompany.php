<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SapConnection;
use App\Company;

class Processcompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $company;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		if ($this->company->sapnumber == null) {
			$name1 = substr($this->company->companyname,0,35);
			$name2 = strlen($this->company->companyname)>35 ? substr($this->company->companyname,36,60) : "";
			$address1 = substr($this->company->address,0,35);
			$address2 = strlen($this->company->address)>35 ? substr($this->company->address,36,60) : "";
			$sap_connection = SapConnection::getConnection();
			$func = $sap_connection->getFunction('ZCREATE_CUSTOMER');
			$result = $func->invoke([			
				'BUKRS' => config('app.SAP_COMPANY_CODE'),
				'KTOKD' => 'M001',
				'TITLE_MEDI' => 'COMPANY',
				'NAME1' => $name1,
				'NAME2' => $name2,
				'STREET' => $address1,
				'STR_SUPPL1' => $address2,
				'CITY' => $this->company->city->cityname,
				'COUNTRY' => $this->company->city->country->isocode,
				'PO_BOX' => isset($this->company->pobox) ? $this->company->pobox : "",
				'LANGU' => 'EN',
				'STCD1' => 'a',
				'STCD2' => 'b',
				'STCEG' => 'c',
				'AKONT' => '112000',
				'ZUAWA' => '001',
				'FDGRV' => '1001',
				'ZTERM' => 'C018',
				'ZWELS' => 'C',
				'CUST_ID' => isset($this->company->sapnumber) ? $this->company->sapnumber : "",
			]);
			if (trim($result['KUNNR']) != '') {
				$this->company->sapnumber = $result['KUNNR'];
				$this->company->save();
			}
			//var_dump($result);
		}
    }
}
