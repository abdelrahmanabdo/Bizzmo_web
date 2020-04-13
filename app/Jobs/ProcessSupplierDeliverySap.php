<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SapConnection;
use App\Attachment;

class ProcessSupplierDeliverySap implements ShouldQueue
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

		$invoiceDate = strtotime($po->sinvoicedate);
		$sInvoiceDate = date('Ymd', $invoiceDate);
		$sInvoiceYear = date('Y', $invoiceDate);
		$sInvoiceMonth = date('m', $invoiceDate);

		$total = 1 * number_format($po->total, 2, '.', '');
		$vat = 1 * number_format($total * $po->vat / 100, 2, '.', '');
		$grandTotal = $total + $vat;

		$ORDER_HEADER_IN = array(
			"BUS_ACT" => "RFBU",
			"USERNAME" => "SHERIF",
			"HEADER_TXT" => "Supp Invoice " . $po->sinvoice,
			"COMP_CODE" => env('SAP_COMPANY_CODE'),
			"DOC_DATE" => $sInvoiceDate,
			"PSTNG_DATE" => $sInvoiceDate,
			"TRANS_DATE" => $sInvoiceDate,
			"FISC_YEAR" => $sInvoiceYear,
			"FIS_PERIOD" => $sInvoiceMonth,
			"REF_DOC_NO" => "Bizzmo PO " . $po->vendornumber,
			"DOC_TYPE" => "KR"
		);

		$AP_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000003",
				"VENDOR_NO" => $po->vendor->sapvendornumber,
				"PMNTTRMS" => $po->vendor->vendorpaymentterm->sappaymentterm,
				"BLINE_DATE" => $sInvoiceDate
			)
		);

		$GL_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000001",
				"GL_ACCOUNT" => "0000500010",
				"PROFIT_CTR" => "PV00000000"
			)
		);
		if ($po->vat !== 0) {
			$GL_ITEMS[] = array(
				"ITEMNO_ACC" => "0000000002",
				"GL_ACCOUNT" => "0000212020",
				"PROFIT_CTR" => "PV00000000"
			);
		}

		$CURR_ITEMS = array(
			array(
				"ITEMNO_ACC" => "0000000003",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => -1 * $grandTotal
			),
			array(
				"ITEMNO_ACC" => "0000000001",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => $total
			)
		);
		if ($po->vat !== 0) {
			$CURR_ITEMS[] = array(
				"ITEMNO_ACC" => "0000000002",
				"CURRENCY_ISO" => "USD",
				"AMT_DOCCUR" => $vat
			);
		}

		$sap_connection = SapConnection::getConnection();
		$func = $sap_connection->getFunction('ZFI_DOC');
		$result = $func->invoke([
			'DOC_HEADER' => $ORDER_HEADER_IN,
			'AP_ITEMS' => $AP_ITEMS,
			'GL_ITEMS' => $GL_ITEMS,
			'CURR_ITEMS' => $CURR_ITEMS
		]);

		$responseMessage = $result['RETURN'][0]['MESSAGE'];

		if (strpos($responseMessage, 'Document posted successfully') > -1) {
			// Save SAP document number
			$docNumber = $result['RETURN'][0]['MESSAGE_V2'];
			$po->sap_supplier_doc_number = trim($docNumber);
			$po->save();
		} else {
			throw new \Exception("Error Processing Delivery To SAP", 1);
		}

		return true;
	}
}
