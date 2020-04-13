<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SapConnection;
use App\Attachment;

class ProcessBuyerDepositSap implements ShouldQueue
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

		$invoiceDate = strtotime($security->created_at);
		$bInvoiceDate = date('Ymd', $invoiceDate);
		$bInvoiceYear = date('Y', $invoiceDate);
		$bInvoiceMonth = date('m', $invoiceDate);


		$total = 1 * number_format($security->amount, 2, '.', '');
		$grandTotal = $total;

		$ORDER_HEADER_IN = array(
			"BUS_ACT" => "RFBU",
			"USERNAME" => "SHERIF",
			"HEADER_TXT" => "Bizzmo Invoice " . $security->binvoice,
			"COMP_CODE" => env('SAP_COMPANY_CODE'),
			"DOC_DATE" => $bInvoiceDate,
			"PSTNG_DATE" => $bInvoiceDate,
			"TRANS_DATE" => $bInvoiceDate,
			"FISC_YEAR" => $bInvoiceYear,
			"FIS_PERIOD" => $bInvoiceMonth,
			"REF_DOC_NO" => "Bizzmo Dep " . $security->creditrequest->id,
			"DOC_TYPE" => "DR"
		);

		$AR_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000001",
				"CUSTOMER" => $security->creditrequest->company->sapnumber,
				"GL_ACCOUNT" => "0000112000",
				"PROFIT_CTR" => "PV00000000",
				"PMNTTRMS" => "C001",
				"BLINE_DATE" => $bInvoiceDate
			)
		);

		$GL_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000002",
				"GL_ACCOUNT" => "0000209200"
			)
		);

		$CURR_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000001",
				"CURRENCY" => "USD",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => $grandTotal
			),
			array(
				"ITEMNO_ACC" => "0000000002",
				"CURRENCY" => "USD",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => -1 * $total
			)
		);

		$sap_connection = SapConnection::getConnection();
		$func = $sap_connection->getFunction('ZFI_DOC');
		$result = $func->invoke([
			'DOC_HEADER' => $ORDER_HEADER_IN,
			'AR_ITEMS' => $AR_ITEMS,
			'GL_ITEMS' => $GL_ITEMS,
			'CURR_ITEMS' => $CURR_ITEMS
		]);
		var_dump($result);
		$ressecuritynseMessage = $result['RETURN'][0]['MESSAGE'];
		if (strpos($ressecuritynseMessage, 'Document posted successfully') > -1) {
			// Save SAP document number
			$docNumber = $result['RETURN'][0]['MESSAGE_V2'];
			$security->sap_fi_doc_no = trim($docNumber);
			$security->save();
		} else {
			throw new \Exception("Error Processing SDinvoice To SAP. " . $ressecuritynseMessage, 1);
		}

		return true;
	}
}
