<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SapConnection;
use App\Company;

class Processvendor implements ShouldQueue
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
		if ($this->company->vendorsapnumber == null) {
			$name1 = substr($this->company->companyname,0,35);
			$name2 = strlen($this->company->companyname)>35 ? substr($this->company->companyname,36,60) : ".";
			$address1 = substr($this->company->address,0,35);
			$address2 = strlen($this->company->address)>35 ? substr($this->company->address,36,60) : ".";
			$postcode = isset($this->company->pobox) ? $this->company->pobox : "";
			if ($this->company->city->country->isocode == 'SA' && strlen($postcode) != 5) {
				$postcode = '';
			}
			$sap_connection = SapConnection::getConnection();
			$func = $sap_connection->getFunction('ZCREATE_VENDOR');
			$result = $func->invoke([			
				'BUKRS_001' => config('app.SAP_COMPANY_CODE'),
				'KTOKK_002' => 'Z001',
				'ANRED_003' => 'COMPANY',
				'NAME1_004' => $name1,
				'SORTL_005' => 'BIZ' . $this->company->id,
				'STRAS_006' => $address1,
				'PFACH_007' => $postcode,
				'ORT01_008' => $this->company->city->cityname,
				'PSTLZ_009' => '12345',
				'ORT02_010' => 'DISTRICT',
				'LAND1_011' => $this->company->city->country->isocode,
				'REGIO_012' => '01',
				'SPRAS_013' => 'EN',
				'AKONT_014' => '208000',
				'ZUAWA_015' => '001',
				'FDGRV_016' => '1001',
				'ZTERM_017' => '0001',
				'REPRF_018' => 'X',
				'VEND_ID' => isset($this->company->sapvendornumber) ? $this->company->sapvendornumber : "",
			]);
			if (trim($result['LIFNR']) != '') {
				$this->company->sapvendornumber = $result['LIFNR'];
				$this->company->save();
			}
			//var_dump($result);
		}
    }
}
