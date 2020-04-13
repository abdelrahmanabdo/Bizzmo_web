<?php

namespace App\Helpers;

use App\Settings;
use App\Attachment;
use App\Attachmenttype;
use App\Purchaseorder;

use App\Jobs\ProcessDeliveryMail;
use App\Jobs\ProcessBinvoice;
use App\Jobs\ProcessSinvoice;

use Illuminate\Http\Request;
use Storage;
use Mail;

class TookanHelper
{

  const REQUESTS_URLS = [
    'API_URL' => 'https://api.tookanapp.com/v2',
    'API_URL_MOCK' => 'https://private-anon-ff96584095-tookanapi.apiary-mock.com/v2'
  ];
  protected $purchaseorder;
  private static function commonCurlOpts() {
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true; 
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
    return $options;
  }

  private static function execCurl($options) {
    $curl = curl_init();
    curl_setopt_array($curl, $options);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

    try {
        $response = curl_exec($curl);
    } catch (\Exception $e) {
        echo "Error occurred: " . $e->getMessage();
    }
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [
        'response' => $response, 
        'httpCode' => $httpCode
    ];
  }

  public function cancelJob($job_id) {
	$api_key = env('TOOKAN_API_KEY') ? env('TOOKAN_API_KEY') : "53616580f5440c0343482d651256254319e2c1fd2fdc793b551a02";
	$postData = [
      "api_key" => $api_key,
      "job_id" => $job_id
    ];
	
	$options = self::commonCurlOpts();
    $options[CURLOPT_URL] = self::REQUESTS_URLS['API_URL'] . '/delete_task';
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/json']; 
    $options[CURLOPT_POSTFIELDS] = json_encode($postData);

    $result = self::execCurl($options);
    $response = $result['response'];
    $httpCode = $result['httpCode'];

    //var_dump($result);
    if (!$result)
      throw new \Exception("Sending request failed");

    $decoded = json_decode($response, true);
    return $decoded;
  }
  
  public function createPickupDelivery($id)
  {
    $po = Purchaseorder::with('company', 'company.city', 'company.city.country', 'vendor', 'shippingaddress', 'shippingaddress.city', 'shippingaddress.city.country', 'currency', 'paymentterm', 'incoterm', 'purchaseorderitems', 'purchaseorderitems.unit')->find($id);
    
    //return date_format(date_create($po->pickupbydate),"m/d/y H:i");
    //return $po->pickupbydate;
    // $po = Purchaseorder::find($id);
    $api_key = env('TOOKAN_API_KEY') ? env('TOOKAN_API_KEY') : "53616580f5440c0343482d651256254319e2c1fd2fdc793b551a02";
    $team_id = env('TOOKAN_TEAM_ID') ? env('TOOKAN_TEAM_ID') : "209793";
    $agent = env('TOOKAN_AGENT') ? env('TOOKAN_AGENT') : "406139";

    $prod =array();

    foreach ($po->purchaseorderitems as $purchaseorderitem)
    {
      array_push($prod, [[$purchaseorderitem['productname']], [$purchaseorderitem['mpn']], [strval($purchaseorderitem['quantity'])]]);
    }
	
	$currdate =  date("Y-m-d H:i");		
	$pickupdate =  $po->pickupbydate . ' ' . $po->pickupbytime->name;		
	$deliverydate =  $po->deliverbydate . ' ' . $po->deliverbytime->name;		
	if ($currdate > $pickupdate) {
		$pickupdate = $currdate;
	}
	if ($pickupdate > $deliverydate) {
		$deliverydate = $pickupdate;
	}

    $postData = [
      "api_key" => $api_key,
      "order_id" => $po->company_id . '-' . $po->number,
      "team_id" => $team_id,
      "auto_assignment" => "1",
      "job_description" => "Bizzmo delivery",
      "job_pickup_phone" => $po->pickupaddress->phone,
      "job_pickup_name" => $po->pickupaddress->partyname,
      "job_pickup_email" => $po->pickupaddress->email,
      "job_pickup_address" => $po->pickupaddress->address . ', ' . $po->pickupaddress->city->cityname . ', ' . $po->pickupaddress->city->country->countryname,
      "job_pickup_latitude" => "",
      "job_pickup_longitude" => "",
      "job_pickup_datetime" => date_format(date_create($pickupdate),"m/d/y H:i"),
      "customer_email" => $po->shippingaddress->email,
      "customer_username" => $po->shippingaddress->partyname,
      "customer_phone" => $po->shippingaddress->phone,
      "customer_address" => $po->shippingaddress->delivery_address . ', ' . $po->shippingaddress->city->cityname . ', ' . $po->shippingaddress->city->country->countryname,
      "latitude" => "",
      "longitude" => "",
      "job_delivery_datetime" => date_format(date_create($deliverydate),"m/d/y H:i"), //"2019-09-30 21:00:00",
      "has_pickup" => "1",
      "has_delivery" => "1",
      "layout_type" => "0",
      "tracking_link" => 1,
      "timezone" => "+20",
      "custom_field_template" => "BizzmoDelivery",
      "meta_data" => [
        [
          "label" => "CompanyName",
          "data" => $po->company->companyname
                ],
        [
          "label" => "BizzmoPO",
          "data" => $po->vendornumber
        ],
        [
          "label" => "BuyerPO",
          "data" => $po->company_id . '-' . $po->number
        ],
        [
          "label" => "PODate",
          "data" => $po->date
        ],
        [
          "label" => "SOno",
          "data" => $po->salesorder
        ],
        [
          "label" => "SODate",
          "data" => $po->date
        ],
        [
          "label" => "BillToPayer",
          "data" => $po->getBillToAddress()->party_name
        ],
        [
          "label" => "ShipToDeliverTo",
          "data" => $po->getShipToAddress()->party_name
        ],
        [
          "label" => "Supplier",
          "data" => $po->getSupplierAddress()->party_name
        ],
        [
          "label" => "SoldTo",
          "data" => $po->getSoldToAddress()->party_name
        ],
        [
          "label"=> "Products",
          "data"=>$prod
        ],
        [
          "label" => "IncoTerms",
          "data" => $po->incoterm['name']
        ],
      ],
      "pickup_custom_field_template" => "BizzmoDelivery",
      "pickup_meta_data" => [
        [
          "label" => "CompanyName",
          "data" => $po->company->companyname
                ],
        [
          "label" => "BizzmoPO",
          "data" => $po->vendornumber
        ],
        [
          "label" => "BuyerPO",
		  "data" => $po->company_id . '-' . $po->number
        ],
        [
          "label" => "PODate",
          "data" => $po->date
        ],
        [
          "label" => "SOno",
          "data" => $po->salesorder
        ],
        [
          "label" => "SODate",
          "data" => $po->date
        ],
        [
          "label" => "BillToPayer",
          "data" => $po->getBillToAddress()->party_name
        ],
        [
          "label" => "ShipToDeliverTo",
          "data" => $po->getShipToAddress()->party_name
        ],
        [
          "label" => "Supplier",
          "data" => $po->getSupplierAddress()->party_name
        ],
        [
          "label" => "SoldTo",
          "data" => $po->getSoldToAddress()->party_name
        ],
        [
          "label"=> "Products",
          "data"=>$prod
        ],
        [
          "label" => "IncoTerms",
          "data" => $po->incoterm['name']
        ],
      ],
      "fleet_id" => $agent,
      // "p_ref_images" => [
      //   "http://tookanapp.com/wp-content/uploads/2015/11/logo_dark.png",
      //   "http://tookanapp.com/wp-content/uploads/2015/11/logo_dark.png"
      // ],
      // "ref_images" => [
      //   "http://tookanapp.com/wp-content/uploads/2015/11/logo_dark.png",
      //   "http://tookanapp.com/wp-content/uploads/2015/11/logo_dark.png"
      // ],
      "notify" => 1,
      "tags" => "",
      "geofence" => 0,
      "ride_type" => 0
    ];
    
    // return $prod;
    // return $postData;
    $options = self::commonCurlOpts();
    $options[CURLOPT_URL] = self::REQUESTS_URLS['API_URL'] . '/create_task';
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/json']; 
    $options[CURLOPT_POSTFIELDS] = json_encode($postData);

    $result = self::execCurl($options);
    $response = $result['response'];
    $httpCode = $result['httpCode'];

    return  $result;
    //if (!$response)
      //throw new \Exception("Sending request failed");

    $decoded = json_decode($response, true);
    return $decoded;
    //$response = $this->sendRequest($data);
    //$this->createSecurityAttachment($security, $response['id']);
  }

  function tookanWebhook (Request $request) {
    $json = $request->getContent();    
    $shared_secret = env('TOOKAN_SECRET') ? env('TOOKAN_SECRET') : "iiiNyaoYEee6PXmc";
    $obj = json_decode($json, true);
	if ($obj["team_id"] != env('TOOKAN_TEAM_ID')) {
		//Storage::disk('local')->append('tookan.txt', 'metra');
		//return true;
	} else {
		//Storage::disk('local')->append('tookan.txt', $json);
		switch (json_last_error()) {
		  case JSON_ERROR_NONE:
			echo ' - No errors';
		  break;
		  case JSON_ERROR_DEPTH:
			echo ' - Maximum stack depth exceeded';
		  break;
		  case JSON_ERROR_STATE_MISMATCH:
			echo ' - Underflow or the modes mismatch';
		  break;
		  case JSON_ERROR_CTRL_CHAR:
			echo ' - Unexpected control character found';
		  break;
		  case JSON_ERROR_SYNTAX:
			echo ' - Syntax error, malformed JSON';
		  break;
		  case JSON_ERROR_UTF8:
			echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		  break;
		  default:
			echo ' - Unknown error';
		  break;
		}
		
		
		if ($obj["tookan_shared_secret"] == $shared_secret) {
			$auth=true;
		}
		$job_state = $obj["job_state"];
		$job_id = $obj["job_id"];
		$job_status = $obj["job_status"];
		$job_type = $obj["job_type"];
		$task_status = $obj["task_status"];
		$event = $job_state;
		if($auth)
			if ($job_type == '0') { //0 is pickup
				$po = Purchaseorder::where('pickup_job_id', $job_id)->first();
				$this->purchaseorder = $po;
				$po->delivery_status = 'Pickup ' . $job_state;
				if ($event == 'Started' || $event == 'Successful') {
					ProcessDeliveryMail::dispatch(['id' => $po->id, 'job_type' => 'Pickup', 'event' => $job_state]);
				}
			} elseif ($job_type == '1') { //1 is delivery
				$po = Purchaseorder::where('delivery_job_id', $job_id)->first();
				$this->purchaseorder = $po;
				if ($task_status != '6') {
					$po->delivery_status = 'Delivery ' . $job_state;
				}
				if ($event == 'Successful') {
					ProcessDeliveryMail::dispatch(['id' => $po->id, 'job_type' => 'Delivery', 'event' => $job_state]);
				}
				if ($job_status == '2') {
					$po->signed_at = date("Y-m-d H:i:s");
					$po->status_id = 22;
					// Download document
					//ProcessDownloadSignedDeliveryDocument::dispatch($provider, $documentId); XXXXXXXXXXXXXXXXXXXXXXXXX

					// Process buyer and supplier invoices	
					ProcessBinvoice::dispatch($po);
					ProcessSinvoice::dispatch($po);
				}
			}
		if (!$po)
		  throw new \Exception("Getting purchase order failed");
			 
		$po->save();
	}
    
  }
}