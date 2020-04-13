<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Mail;
use Auth;
use DateTime;
use DB;
use File;
use Gate;
use Input;
use PDF;
use Storage;

use App\Company;
use App\Creditrequest;
use App\Quotation;
use App\Purchaseorder;
use App\Role;
use App\User;

use DHL\Entity\AP\BookPickupRequest;
use DHL\Datatype\AP\ServiceHeader;
use DHL\Datatype\AP\Piece;
use DHL\Datatype\AP\Pieces;

use DHL\Entity\GB\ShipmentResponse;
use DHL\Entity\GB\ShipmentRequest;
use DHL\Client\Web as WebserviceClient;
//use DHL\Datatype\GB\Piece;
use DHL\Datatype\GB\SpecialService;

//use SAPNWRFC\Connection as SapConnection;
//use SAPNWRFC\Exception as SapException;
use DocuSign\eSign as docusignclient;

use App\Helpers\AWSsmsHelper;
use App\Helpers\TwilioHelper;
use App\Helpers\SapConnection;
use App\Helpers\CalendarQuarter;
use App\Helpers\SpellNumber;

use App\Jobs\Processcompany;
use App\Jobs\Processvendor;
use App\Jobs\ProcessSinvoice;

use App\Jobs\ProcessBinvoice;
use App\Jobs\Processdelivery;

use App\Jobs\Processcreditrequestsecurities;
use App\Jobs\Processcreditrequest;

class testcontroller extends Controller
{	
	
	//protected $AWSsmsHelper;
	
	public function testtest() {
		$TwilioHelper = new TwilioHelper();
		$TwilioHelper->sendSMS('+971504203972', 'tell me if you get this');
	}
	
	public function test() {
		$po = Purchaseorder::find(26);
		ProcessBinvoice::dispatch($po);
		die;
		$po = Purchaseorder::find(1);
		ProcessBinvoice::dispatch($po);
		ProcessSinvoice::dispatch($po);
	}
	
	public function orgtest() {
		$creditrequest = Creditrequest::find(14);
		echo $creditrequest->isSecuritesCompleted();
		die;
		$data = [[
		  "label" => "Weight",
		  "data" => "40"
		]];
		
		$data = [[
				"label" => "Weight",
				"data" => "40"
			], 
			[
				"label" => "Products",
				"data" => [["ABC", "2"],
					["XYZ", "4"]
				]
			]
		];
		
		//prev key 53616786f747510a1d517b651647254319e6ccfe28de7d375a1403
		$postData = [
            'api_key' => '53616580f5440c0343482d651256254319e2c1fd2fdc793b551a02',
			'order_id' => '411000112',
			'job_description' => 'groceries delivery',
			'customer_email' => '',
			'customer_username' => 'Manual',
			'customer_phone' => '	+2034635678',
			'customer_address' => '3 street 9B, Maadi, Cairo, Egypt',
			'job_delivery_datetime' => '2019-03-20 21:00:00',
			'custom_field_template' => 'NewTest',
			'meta_data' => json_encode($data),
			'team_id' => '',
			'ref_images' => ['https://bizzmo.com/images/bizzmo-logo.png'],
			'auto_assignment' => '0',
			'has_pickup' => '0',
			'has_delivery' => '1',
			'layout_type' => '0',
			'tracking_link' => '1',
			'timezone' => '-330',
			'notify' => '1',
			'tags' => '',
			'geofence' => '0'
        ];

        $options = self::commonCurlOpts();
        $options[CURLOPT_URL] = 'https://api.tookanapp.com/v2/create_task';
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/x-www-form-urlencoded']; 
        $options[CURLOPT_POSTFIELDS] = http_build_query($postData);
		
		$result = self::execCurl($options);
        $response = $result['response'];
        $httpCode = $result['httpCode'];

        if ($httpCode != Response::HTTP_OK)
            throw new \Exception("$httpCode: Failed to get access token");

        if ($response) {
            $responseDecoded = json_decode($response, true);
            return $responseDecoded;
        }

	}
	
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
	
	public function test2() {
		$creditrequest = Creditrequest::find(10);
		Processcreditrequest::dispatch($creditrequest);
		
		$spell = new SpellNumber();
		echo $spell->spell(1151.43);
		die;
		echo 1/0;
		
		$creditrequest = Creditrequest::find(1);
		$provider = env('SIGNATURE_PROVIDER');
		Processcreditrequestsecurities::dispatch($provider, $creditrequest);
	}
	
	public function json(Request $request) {
		Storage::disk('local')->append('json.txt', 'POST');
		//$a = $request::get('id');
		$a = $request->input('id');
		Storage::disk('local')->append('json.txt', $a);
	}
	
	public function dhltest() {
		
		$company = new Company();
		echo $company->toXML();
		die;
	// DHL settings
		//$dhl = $config['dhl'];

		//piece
		$piece = new Piece();
		$piece->PieceID = '1';
		$piece->PackageType = 'EE';
		$piece->Weight = '5.0';
		$piece->DimWeight = '600.0';
		$piece->Width = '50';
		$piece->Height = '100';
		$piece->Depth = '150';
		
		//$pieces = new Pieces();
		//$pieces->addPiece($piece);
		//Pickup
		$pickup = new BookPickupRequest();

		$pickup->ShipmentDetails->NumberOfPieces = 2;
		$pickup->ShipmentDetails->addPiece($pieces);
		die;
		$serviceheader = new ServiceHeader();
		$serviceheader->MessageTime = '';
		$serviceheader->MessageReference = '';
		$serviceheader->SiteID = '';
		$serviceheader->Password = '';
		$pickup->addServiceHeader();
		
		die;
		
		// Test a ShipmentRequest using DHL XML API
		$sample = new ShipmentRequest();

		// Assuming there is a config array variable with id and pass to DHL XML Service
		$sample->SiteID = 'metragroup';
		$sample->Password = '3qGy9Fw6MN';

		// Set values of the request
		$sample->MessageTime = '2018-04-17T09:30:47-05:00';
		$sample->MessageReference = '1234567890123456789012345678901';
		$sample->RegionCode = 'AM';
		$sample->RequestedPickupTime = 'Y';
		$sample->NewShipper = 'Y';
		$sample->LanguageCode = 'en';
		$sample->PiecesEnabled = 'Y';
		$sample->Billing->ShipperAccountNumber = $dhl['shipperAccountNumber'];
		$sample->Billing->ShippingPaymentType = 'S';
		$sample->Billing->BillingAccountNumber = $dhl['billingAccountNumber'];
		$sample->Billing->DutyPaymentType = 'S';
		$sample->Billing->DutyAccountNumber = $dhl['dutyAccountNumber'];
		$sample->Consignee->CompanyName = 'Ssense';
		$sample->Consignee->addAddressLine('333 Chabanel West, #900');
		$sample->Consignee->City = 'Montreal';
		$sample->Consignee->PostalCode = 'H3E1G6';
		$sample->Consignee->CountryCode = 'CA';
		$sample->Consignee->CountryName = 'Canada';
		$sample->Consignee->Contact->PersonName = 'Bashar Al-Fallouji';
		$sample->Consignee->Contact->PhoneNumber = '0435 336 653';
		$sample->Consignee->Contact->PhoneExtension = '123';
		$sample->Consignee->Contact->FaxNumber = '506-851-7403';
		$sample->Consignee->Contact->Telex = '506-851-7121';
		$sample->Consignee->Contact->Email = 'bashar@alfallouji.com';
		$sample->Commodity->CommodityCode = 'cc';
		$sample->Commodity->CommodityName = 'cn';
		$sample->Dutiable->DeclaredValue = '200.00';
		$sample->Dutiable->DeclaredCurrency = 'USD';
		$sample->Dutiable->ScheduleB = '3002905110';
		$sample->Dutiable->ExportLicense = 'D123456';
		$sample->Dutiable->ShipperEIN = '112233445566';
		$sample->Dutiable->ShipperIDType = 'S';
		$sample->Dutiable->ImportLicense = 'ALFAL';
		$sample->Dutiable->ConsigneeEIN = 'ConEIN2123';
		$sample->Dutiable->TermsOfTrade = 'DTP';
		$sample->Reference->ReferenceID = 'AM international shipment';
		$sample->Reference->ReferenceType = 'St';
		$sample->ShipmentDetails->NumberOfPieces = 2;

		$piece = new Piece();
		$piece->PieceID = '1';
		$piece->PackageType = 'EE';
		$piece->Weight = '5.0';
		$piece->DimWeight = '600.0';
		$piece->Width = '50';
		$piece->Height = '100';
		$piece->Depth = '150';
		$sample->ShipmentDetails->addPiece($piece);

		$piece = new Piece();
		$piece->PieceID = '2';
		$piece->PackageType = 'EE';
		$piece->Weight = '5.0';
		$piece->DimWeight = '600.0';
		$piece->Width = '50';
		$piece->Height = '100';
		$piece->Depth = '150';
		$sample->ShipmentDetails->addPiece($piece);

		$sample->ShipmentDetails->Weight = '10.0';
		$sample->ShipmentDetails->WeightUnit = 'L';
		$sample->ShipmentDetails->GlobalProductCode = 'P';
		$sample->ShipmentDetails->LocalProductCode = 'P';
		$sample->ShipmentDetails->Date = date('Y-m-d');
		$sample->ShipmentDetails->Contents = 'AM international shipment contents';
		$sample->ShipmentDetails->DoorTo = 'DD';
		$sample->ShipmentDetails->DimensionUnit = 'I';
		$sample->ShipmentDetails->InsuredAmount = '1200.00';
		$sample->ShipmentDetails->PackageType = 'EE';
		$sample->ShipmentDetails->IsDutiable = 'Y';
		$sample->ShipmentDetails->CurrencyCode = 'USD';
		$sample->Shipper->ShipperID = '751008818';
		$sample->Shipper->CompanyName = 'IBM Corporation';
		$sample->Shipper->RegisteredAccount = '751008818';
		$sample->Shipper->addAddressLine('1 New Orchard Road');
		$sample->Shipper->addAddressLine('Armonk');
		$sample->Shipper->City = 'New York';
		$sample->Shipper->Division = 'ny';
		$sample->Shipper->DivisionCode = 'ny';
		$sample->Shipper->PostalCode = '10504';
		$sample->Shipper->CountryCode = 'US';
		$sample->Shipper->CountryName = 'United States Of America';
		$sample->Shipper->Contact->PersonName = 'Mr peter';
		$sample->Shipper->Contact->PhoneNumber = '1 905 8613402';
		$sample->Shipper->Contact->PhoneExtension = '3403';
		$sample->Shipper->Contact->FaxNumber = '1 905 8613411';
		$sample->Shipper->Contact->Telex = '1245';
		$sample->Shipper->Contact->Email = 'test@email.com';

		$specialService = new SpecialService();
		$specialService->SpecialServiceType = 'A';
		$sample->addSpecialService($specialService);

		$specialService = new SpecialService();
		$specialService->SpecialServiceType = 'I';
		$sample->addSpecialService($specialService);

		$sample->EProcShip = 'N';
		$sample->LabelImageFormat = 'PDF';

		// Call DHL XML API
		$start = microtime(true);

		// Display the XML that will be sent to DHL
		echo $sample->toXML();

		// DHL webservice client using the staging environment
		$client = new WebserviceClient('staging');

		// Call the DHL service and display the XML result
		echo $client->call($sample);
		echo PHP_EOL . 'Executed in ' . (microtime(true) - $start) . ' seconds.' . PHP_EOL;

		echo 'aa';
	}
	
	
	public function form() {
		return view('test.test');
	}
	
	public function abc(Request $request) {
		echo 'bb';
		
		Storage::disk('local')->append('file.txt', 'POST');
		
		foreach( $_POST as $key => $val) {
			if( is_array( $val )) {
				foreach( $val as $key2 => $val2 ) {
					Storage::disk('local')->append('file.txt', $key2 . ' - '. $val2);
				}
			} else {
				Storage::disk('local')->append('file.txt', $key . ' - '. $val);
			}
		} 

		Storage::disk('local')->append('file.txt', 'GET');
		
		foreach( $_GET as $key => $val) {
			if( is_array( $val )) {
				foreach( $val as $key2 => $val2 ) {
					Storage::disk('local')->append('file.txt', $key2 . ' - '. $val2);
				}
			} else {
				Storage::disk('local')->append('file.txt', $key . ' - '. $val);
			}
		} 
	}
	
	public function sign(Request $request) {
		echo $request->input('event');
	}
	
	public function test4() {					
		// Input your info here:
		$email = "docusign@bizzmo.com";			// your account email
		$password = 'Bi$$m0';		// your account password
		$integratorKey = "4de09912-8366-4a54-9727-16e70c9c5214";		// your account integrator key, found on (Preferences -> API page)
		// copy the envelopeId from an existing envelope in your account that you want
		// to download documents from
		$envelopeId = "2866ef5e-b474-4790-8a0d-1415ce78d31a";
		// construct the authentication header:
		$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// STEP 1 - Login (retrieves baseUrl and accountId)
		/////////////////////////////////////////////////////////////////////////////////////////////////
		$url = "https://demo.docusign.net/restapi/v2/login_information";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 200 ) {
			echo "error calling webservice, status is:" . $status;
			exit(-1);
		}
		$response = json_decode($json_response, true);
		$accountId = $response["loginAccounts"][0]["accountId"];
		$baseUrl = $response["loginAccounts"][0]["baseUrl"];
		curl_close($curl);
		//--- display results
		echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
		die;
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// STEP 2 - Get document information
		/////////////////////////////////////////////////////////////////////////////////////////////////                                                                                  
		
		$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			"X-DocuSign-Authentication: $header" )                                                                       
		);
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 200 ) {
			echo "error calling webservice, status is:" . $status;
			exit(-1);
		}
		$response = json_decode($json_response, true);
		curl_close($curl);
		//--- display results
		echo "Envelope has following document(s) information...\n";
		print_r($response);	echo "\n";
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// STEP 3 - Download the envelope's documents
		/////////////////////////////////////////////////////////////////////////////////////////////////
		foreach( $response["envelopeDocuments"] as $document ) {
			$docUri = $document["uri"];
			
			$curl = curl_init($baseUrl . $docUri );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);  
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
				"X-DocuSign-Authentication: $header" )                                                                       
			);
			
			$data = curl_exec($curl);
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ( $status != 200 ) {
				echo "error calling webservice, status is:" . $status;
				exit(-1);
			}
			file_put_contents("envelopes/" . $envelopeId . "-" . $document["name"], $data);
			curl_close($curl);
			
			//*** Documents should now be downloaded in the same folder as you ran this program
		}
		//--- display results
		echo "Envelope document(s) have been downloaded, check your local directory.\n";
		
	}

	public function testpost(Request $request) 
	{
		
		$building = Building::find(3);		
		$building->active = 0;
		$building->save();
		$path = $request->file('attach')->store('images');
		return 777;
	}
	
	
	public function test1() 
	{

		echo 'aa';
		
		$config = [
			'ashost' => '10.0.1.1',
			'sysnr'  => '00',
			'client' => '300',
			'user' => 'sherif',
			'passwd' => 'metsys11',
			'trace'  => SapConnection::TRACE_LEVEL_OFF,
		];

		try {
			$c = new SapConnection($config);

			$f = $c->getFunction('ZCREDIT_EXPOSURE');
			$result = $f->invoke([
				'KKBER' => 'M001',
				'KUNNR' => '0100004969',
				'DATE_CREDIT_EXPOSURE' => '31129999'
			]);

			var_dump($result);
			echo $result['CREDITLIMIT'];
		} catch(SapException $ex) {
			echo 'Exception: ' . $ex->getMessage() . PHP_EOL;

			/*
			 * You could also catch \SAPNWRFC\ConnectionException and \SAPNWRFC\FunctionCallException
			 * separately if you want to.
			 */
		}
	}
	
	public function testmail() {
		
		$from = 's_abdelnabi@metragroup.com';
		$subject = 'test';

		// 'contents' key in array matches variable name used in view
		$data = array(
			'contents' => "I don't know"
		);

		Mail::send('companies.nonview', $data, function($message) use ($from, $subject) {
			$message->from($from, 'user');
			$message->to('sherifan@gmail.com','Someone')->subject($subject);
		});

	}
	
}
