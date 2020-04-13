<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SapConnection;
use App\Attachment;

class ProcessBuyerDeliverySap implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $po;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($po)
	{
		$this->po = $po;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$po = $this->po;

		$invoiceDate = strtotime($po->binvoicedate);
		$bInvoiceDate = date('Ymd', $invoiceDate);
		$bInvoiceYear = date('Y', $invoiceDate);
		$bInvoiceMonth = date('m', $invoiceDate);


		$total = 1 * number_format($po->total, 2, '.', '');
		$fees = 1 * number_format($total * $po->buyup / 100, 2, '.', '');
		$vat = 1 * number_format(($total + $fees) * $po->vat / 100, 2, '.', '');
		$grandTotal = $total + $fees + $vat;

		$ORDER_HEADER_IN = array(
			"BUS_ACT" => "RFBU",
			"USERNAME" => "SHERIF",
			"HEADER_TXT" => "Bizzmo Inv " . $po->binvoice,
			"COMP_CODE" => env('SAP_COMPANY_CODE'),
			"DOC_DATE" => $bInvoiceDate,
			"PSTNG_DATE" => $bInvoiceDate,
			"TRANS_DATE" => $bInvoiceDate,
			"FISC_YEAR" => $bInvoiceYear,
			"FIS_PERIOD" => $bInvoiceMonth,
			"REF_DOC_NO" => "Buyer PO " . $po->company_id . '-' . $po->number,
			"DOC_TYPE" => "DR"
		);

		$AR_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000001",
				"CUSTOMER" => $po->company->sapnumber,
				"GL_ACCOUNT" => "0000112000",
				"PROFIT_CTR" => "PV00000000",
				"PMNTTRMS" => $po->paymentterm->sappaymentterm,
				"BLINE_DATE" => $bInvoiceDate
			)
		);

		$GL_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000002",
				"GL_ACCOUNT" => "0000400000"
			),
			array(
				"ITEMNO_ACC" => "0000000003",
				"GL_ACCOUNT" => "0000404090"
			)
		);
		if ($po->vat !== 0) {
			$GL_ITEMS[] = array(
				"ITEMNO_ACC" => "0000000004",
				"GL_ACCOUNT" => "0000212020"
			);
		}

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
			),
			array(
				"ITEMNO_ACC" => "0000000003",
				"CURRENCY" => "USD",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => -1 * $fees
			)
		);
		if ($po->vat !== 0) {
			$CURR_ITEMS[] = array(
				"ITEMNO_ACC" => "0000000004",
				"CURRENCY" => "USD",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => -1 * $vat
			);
		}

		$sap_connection = SapConnection::getConnection();
		$func = $sap_connection->getFunction('ZFI_DOC');
		$result = $func->invoke([
			'DOC_HEADER' => $ORDER_HEADER_IN,
			'AR_ITEMS' => $AR_ITEMS,
			'GL_ITEMS' => $GL_ITEMS,
			'CURR_ITEMS' => $CURR_ITEMS
		]);
		var_dump($result);
		$responseMessage = $result['RETURN'][0]['MESSAGE'];
		if (strpos($responseMessage, 'Document posted successfully') > -1) {
			// Save SAP document number
			$docNumber = $result['RETURN'][0]['MESSAGE_V2'];
			$po->sap_buyer_doc_number = trim($docNumber);
			$po->save();
		} else {
			throw new \Exception("Error Processing Delivery To SAP. " . $responseMessage, 1);
		}

		return true;
	}
}
