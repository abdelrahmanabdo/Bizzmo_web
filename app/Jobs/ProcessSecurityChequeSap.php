<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SapConnection;

class ProcessSecurityChequeSap implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $security;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($security)
	{
		$this->security = $security;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$security = $this->security;		
		
		$chequeReceivedDate = strtotime($security->updated_at);		
		$chequeDate = date('d.m.Y', $chequeReceivedDate);
		$chequeYear = date('Y', $chequeReceivedDate);
		$chequeMonth = date('m', $chequeReceivedDate);
		
		$credit = 1 * number_format($security->amount, 2, '.', '');
		$debit = 1 * number_format($security->amount, 2, '.', '');

		$args = [
					'BLDAT_001' => $chequeDate,
					'BLART_002' => 'DP',
					'BUKRS_003' => env('SAP_COMPANY_CODE'),
					'BUDAT_004' => $chequeDate,
					'MONAT_005' => $chequeMonth,
					'WAERS_006' => 'USD',
					'NEWBS_007' => '09' ,
					'NEWKO_008' => $security->creditrequest->company->sapnumber,
					'NEWUM_009' => 'W',
					'WRBTR_010' => "$credit",
					'ZFBDT_011' => $chequeDate,
					'NEWBS_012' => '19',
					'NEWKO_013' => $security->creditrequest->company->sapnumber,
					'NEWUM_014' => 'X',
					'WRBTR_015' => "$debit",
					'ZFBDT_016' => $chequeDate,
					'XBLNR' => 'CR ' . $security->creditrequest->id . ' chq'

				];
		
		$sap_connection = SapConnection::getConnection();
		$func = $sap_connection->getFunction('ZFB05');
		$result = $func->invoke($args);
		var_dump($result);
		$docno = $result['BELNR'];
		if ($docno != '') {
			// Save SAP document number
			$security->sap_fi_doc_no = trim($docno . env('SAP_COMPANY_CODE') . $chequeYear);
			$security->save();
		} else {
			throw new \Exception("Error Processing security check To SAP. ", 1);
		}

		return true;
	}
}
