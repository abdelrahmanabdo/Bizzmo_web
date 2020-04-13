<?php 

namespace App\Helpers;
 
use DocuSign\eSign as DocuSignClient;
use App\Helpers\SpellNumber;
use App\Securitytype;

class DocuSignHelper
{
    const SUPPLIER_TEMPLATE = 'Supplier_T&C';
    const BUYER_TEMPLATE = 'Customer_T&C';
    const CONTRACT_WEB_HOOK_URL = '/api/signature/docu-sign/contract-update';
    const SECURITY_WEB_HOOK_URL = '/api/signature/docu-sign/security-update';
    const DELIVERY_WEB_HOOK_URL = '/api/signature/docu-sign/delivery-update';

    protected $username;
    protected $password;
    protected $integratorKey;
    protected $host;

    public function __construct()
    {
        $this->username = env('DOCUSIGN_USERNAME');
        $this->password = env('DOCUSIGN_PASSWORD');
        $this->integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
        $this->host = env('DOCUSIGN_HOST');
    }

	public function voidDocument($envelopeId)
    {
        $intiateRequest = $this->preRequest();
        if (empty($intiateRequest))
            throw new \Exception("Failed to initiate docusign request");

        $accountId = $intiateRequest['account_id'];
        $apiClient = $intiateRequest['api_client'];

		
		// Input your info here:
		$integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
		$email = env('DOCUSIGN_USERNAME');
		$password = env('DOCUSIGN_PASSWORD');
		$name = env('DOCUSIGN_USERNAME');

		// construct the authentication header:
		$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// STEP 1 - Login (to retrieve baseUrl and accountId)
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
		
		$data = array("status" => "voided", "voidedReason" => "PO rejected by supplier");
		$data_string = json_encode($data);
		echo "Attempting to void envelope $envelopeId\nVoid request body is:  $data_string\n";
		//die;
		$curl = curl_init($baseUrl . "/envelopes/$envelopeId" );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS,$data_string);                                                                  
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string),
			"X-DocuSign-Authentication: $header" )                                                                       
		);

		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 200 ) {
			echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
			print_r($json_response); echo "\n";
			exit(-1);
		}

		$response = json_decode($json_response, true);

		echo "Done.\n";
		curl_close($curl);
		
    }
	
    public function sendSecurityEnvelope($security, $documentPath)
    {
        $intiateRequest = $this->preRequest();
        if (empty($intiateRequest))
            throw new \Exception("Failed to initiate docusign request");

        $accountId = $intiateRequest['account_id'];
        $apiClient = $intiateRequest['api_client'];

        $fRecipientEmail = $security->signeremail;

        // Document
        $documentPath = realpath($documentPath);
        $documentFileName = $securityName = $security->securitytype->name;
		if ($security->securitytype_id == Securitytype::SECURITY_CHEQUE) {
			$securityName = "Check authorization form";
		}

        if (!file_exists($documentPath))
            throw new \Exception("$documentFileName path doesn't exist in the path $documentPath");

        $document64Base = base64_encode(file_get_contents($documentPath));
        $documentData = [
            'name' => "$documentFileName",
            'document_base64' => $document64Base,
            'document_id' => 1,
            'file_extension' => 'pdf'
        ];
        $document = new DocuSignClient\Model\Document($documentData);

        // Tabs
        $tabsData = $this->createSecurityTabs($security);
        $tabs = new DocuSignClient\Model\Tabs($tabsData);

        // Signer / Recipient
        $signerData = [
            'email' => $security->signeremail,
            'name' => $security->signername,
            'recipient_id' => 1,
            'tabs' => $tabs
        ];
        $signer = new DocuSignClient\Model\Signer($signerData);
        $recipientData = ['signers' => [$signer]];
        $recipient = new DocuSignClient\Model\Recipients($recipientData);
        
        // Event notifications (Webhook)
        $callbackUrl = !empty(env('APP_URL')) ? env('APP_URL') . self::SECURITY_WEB_HOOK_URL : url(self::SECURITY_WEB_HOOK_URL, [], true);
        $eventNotificationData = $this->createDocumentEventNotification($callbackUrl);
        $eventNotification = new DocuSignClient\Model\EventNotification($eventNotificationData);

        // Envelope Definition
        $envelopeDefinitionData = [
            'documents' => [$document],
            'email_subject' => "$securityName",
            'recipients' => $recipient,
            'status' => 'sent',
            'event_notification' => $eventNotification
        ];
        $envelopeDefinition = new DocuSignClient\Model\EnvelopeDefinition($envelopeDefinitionData);
        
        // instantiate a new envelopeApi object
        $envelopeApi = new DocuSignClient\Api\EnvelopesApi($apiClient);

        // create and send the envelope! (aka signature request)
        try {
            $envelopSummary = $envelopeApi->createEnvelope($accountId, $envelopeDefinition);
            return $envelopSummary->getEnvelopeId();

        } catch (DocuSign\eSign\ApiException $e) {
            \Log::Debug("Error connecting Docusign : " . $e->getResponseBody()->errorCode . " " . $e->getResponseBody()->message);
        }
    }
    public function sendContractEnvelope($company, $isBuyer = false)
    {
        $intiateRequest = $this->preRequest();
        if (empty($intiateRequest))
            throw new \Exception("Failed to initiate docusign request");

        $accountId = $intiateRequest['account_id'];
        $apiClient = $intiateRequest['api_client'];

        $fRecipientEmail = $company->signatoryemail;
		$fRecipientName = $company->signatoryname;
        $sRecipientEmail = env('CONTRACT_LAST_RECIP');

        // Document
        $template = $isBuyer ? self::BUYER_TEMPLATE : self::SUPPLIER_TEMPLATE;
        $documentFileName = $template . '.pdf';
        $documentName = $isBuyer ? 'Customer Contract' : 'Supplier Contract';
        $documentPath = realpath(storage_path('app/contract_templates/' . $documentFileName));

        if (!file_exists($documentPath))
            throw new \Exception("$documentFileName path doesn't exist in the path $documentPath");

        $document64Base = base64_encode(file_get_contents($documentPath));
        $documentData = [
            'name' => $documentName,
            'document_base64' => $document64Base,
            'document_id' => 1,
            'file_extension' => 'pdf'
        ];
        $document = new DocuSignClient\Model\Document($documentData);
        
        // Tabs
        $tabs = $this->createEnvelopeTabs($company, $isBuyer);
        $companyTabs = $tabs['company'];
        $selfEntityTabs = $tabs['self_entity'];

        $companySignerPrepData = [
            'email' => $fRecipientEmail,
            'name' => $fRecipientName,
            'tabs' => $companyTabs
        ];

        $selfEntitySignerPrepData = [
            'email' => $sRecipientEmail,
            'name' => 'Mohammed Eissa',
            'tabs' => $selfEntityTabs
        ];

        $signers = $this->createEnvelopeSigners($companySignerPrepData, $selfEntitySignerPrepData);
        $companySignerData = $signers['company'];
        $selfEntitySignerData = $signers['self_entity'];

        $companySigner = new DocuSignClient\Model\Signer($companySignerData);
        $selfEntitySigner = new DocuSignClient\Model\Signer($selfEntitySignerData);

        $recipientsData = [
            'signers' => [$companySigner, $selfEntitySigner]
        ];

		// Recipients
        $recipients = new DocuSignClient\Model\Recipients($recipientsData);
        
        // Event notifications (Webhook)
        $eventNotificationData = $this->createContractEventNotification();
        $eventNotification = new DocuSignClient\Model\EventNotification($eventNotificationData);

        // Envelope Definition
        $envelopeDefinitionData = [
            'documents' => [$document],
            'email_subject' => ($isBuyer ? "Buyer's " : "Supplier's ") . "Agreement",
            'recipients' => $recipients,
            'status' => 'sent',
            'event_notification' => $eventNotification
        ];

        $envelopeDefinition = new DocuSignClient\Model\EnvelopeDefinition($envelopeDefinitionData);
        
        // instantiate a new envelopeApi object
        $envelopeApi = new DocuSignClient\Api\EnvelopesApi($apiClient);

        // create and send the envelope! (aka signature request)
        try {
            $envelopSummary = $envelopeApi->createEnvelope($accountId, $envelopeDefinition);
            return $envelopSummary->getEnvelopeId();

        } catch (DocuSign\eSign\ApiException $e) {
            \Log::Debug("Error connecting Docusign : " . $e->getResponseBody()->errorCode . " " . $e->getResponseBody()->message);
        }
    }
    public function sendDeliveryEnvelope($po, $documentPath)
    {
        $intiateRequest = $this->preRequest();
        if (empty($intiateRequest))
            throw new \Exception("Failed to initiate docusign request");

        $accountId = $intiateRequest['account_id'];
        $apiClient = $intiateRequest['api_client'];

        $fRecipientEmail = $po->company->email;

        // Document
        $documentPath = realpath($documentPath);
        $documentFileName = 'Delivery Document';

        if (!file_exists($documentPath))
            throw new \Exception("$documentFileName path doesn't exist in the path $documentPath");

        $document64Base = base64_encode(file_get_contents($documentPath));
        $documentData = [
            'name' => "$documentFileName",
            'document_base64' => $document64Base,
            'document_id' => 1,
            'file_extension' => 'pdf'
        ];
        $document = new DocuSignClient\Model\Document($documentData);

        // Tabs
        $tabsData = $this->createDeliveryTabs();
        $tabs = new DocuSignClient\Model\Tabs($tabsData);

        // Signer / Recipient
        $signerData = [
            'email' => $po->company->email,
            'name' => $po->company->email,
            'recipient_id' => 1,
            'tabs' => $tabs
        ];
        $signer = new DocuSignClient\Model\Signer($signerData);
        $recipientData = ['signers' => [$signer]];
        $recipient = new DocuSignClient\Model\Recipients($recipientData);
        
        // Event notifications (Webhook)
        $callbackUrl = !empty(env('APP_URL')) ? env('APP_URL') . self::DELIVERY_WEB_HOOK_URL : url(self::DELIVERY_WEB_HOOK_URL, [], true);
        $eventNotificationData = $this->createDocumentEventNotification($callbackUrl);
        $eventNotification = new DocuSignClient\Model\EventNotification($eventNotificationData);

		//Email body
		$par1 = 'Dear ' . $po->company->companyname;
		$par2 = 'Your order #' . $po->company_id . '-' . $po->number .' is being shipped by ' . $po->vendor->companyname . ' and will arrive according to the shipping method you agreed at the time of your purchase. ';
		$par3 = 'To view the details of your order or review your order history, visit <a href="' . env('APP_URL') . '">My Account</a>';
		$par4 = 'Upon delivery, you should inspect the goods for any defects or nonconformity, and if any, then do not sign the attached Proof of Delivery. By signing the attached Proof of Delivery, you will have accepted delivery and acknowledge taking receipt of the goods as attached. You hereby acknowledge that you inspected the shipment and exerted all the needed due diligence and confirm, accordingly, the conformity of the goods in quantity and quality with the placed purchase order. You hereby acknowledge that the goods as delivered are free from any apparent defect. You do accept that any hidden manufacturing or industrial defect should not be the responsibility of Bizzmo. You hereby waive any right of recourse, claim, setting off or request of compensation, against Bizzmo for quantitative or qualitative non-conformity with respect to the delivered goods.';
		$par5 = 'Thanks again for choosing Bizzmo. We appreciate your business.';
		$par6 = 'Thank you';
		$par7 = 'Bizzmo Team';
        // Envelope Definition
        $envelopeDefinitionData = [
            'documents' => [$document],
            'email_subject' => 'Your Bizzmo Order #' . $po->company_id . '-' . $po->number . ' is shipping soon ',
			'email_blurb' => $par1 . '<br><br>' . $par2 . '<br><br>' . $par3 . '<br><br>' . $par4 . '<br><br>' . $par5 . '<br><br><br>' . $par6 . '<br>' . $par7 ,
            'recipients' => $recipient,
            'status' => 'sent',
            'event_notification' => $eventNotification
        ];
        $envelopeDefinition = new DocuSignClient\Model\EnvelopeDefinition($envelopeDefinitionData);
        
        // instantiate a new envelopeApi object
        $envelopeApi = new DocuSignClient\Api\EnvelopesApi($apiClient);

        // create and send the envelope! (aka signature request)
        try {
            $envelopSummary = $envelopeApi->createEnvelope($accountId, $envelopeDefinition);
            return $envelopSummary->getEnvelopeId();

        } catch (DocuSign\eSign\ApiException $e) {
            \Log::Debug("Error connecting Docusign : " . $e->getResponseBody()->errorCode . " " . $e->getResponseBody()->message);
        }

    }
    public function getSignedDocument($envelopeId)
    {
        $intiateRequest = $this->preRequest();
        if (empty($intiateRequest))
            throw new \Exception("Failed to initiate docusign request");

        $accountId = $intiateRequest['account_id'];
        $apiClient = $intiateRequest['api_client'];

        $envelopeApi = new DocuSignClient\Api\EnvelopesApi($apiClient);

        try {
            $signedDocument = $envelopeApi->getDocument($accountId, '1', $envelopeId);
            $signedDocumentContents = file_get_contents($signedDocument->getPathname());
            return $signedDocumentContents;
        } catch (DocuSign\eSign\ApiException $e) {
            \Log::Debug("Error connecting Docusign : " . $e->getResponseBody()->errorCode . " " . $e->getResponseBody()->message);
        }
    }

    private function createSecurityTabs($security)
    {
        switch ($security->securitytype_id) {
            case 1:
                $companyname = $security->creditrequest->company->companyname;
                // $companycountry = $security->country->countryname;
                $companycountry = $security->creditrequest->company->country->countryname;
                $companyaddress = $security->creditrequest->company->address;
                $creditlimit = $security->creditrequest->limit . " USD";
                $spell = new SpellNumber();
                $creditlimittext = $spell->spell($creditlimit);
                $passportno = $security->passportno;
                $signernamecontry = $security->country->countryname;
                $signername = $security->signername;
                $tradelic = $security->creditrequest->company->license;

				$dateSigned2TabData = [
                    'name' => 'Date signed',
                    'x_position' => '155',
                    'y_position' => '127',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '1',
                    'font_size' => 'Size11'
                ];
				$signername3TabData = [
                    'name' => 'Signer name',
                    'x_position' => '86',
                    'y_position' => '173',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];				
                $signernamecontryTabData = [
                    'name' => 'Signer country',
                    'x_position' => '86',
                    'y_position' => '220',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signernamecontry",
                    'original_value' => "$signernamecontry",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $passportnoTabData = [
                    'name' => 'Passport Number',
                    'x_position' => '86',
                    'y_position' => '269',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$passportno",
                    'original_value' => "$passportno",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                		
                $compnameTabData = [
                    'name' => 'Company name',
                    'x_position' => '86',
                    'y_position' => '423',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyname",
                    'original_value' => "$companyname",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];				
				$compcountryTabData = [
                    'name' => 'Country name',
                    'x_position' => '303',
                    'y_position' => '450',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companycountry",
                    'original_value' => "$companycountry",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				// $tradelicTabData = [
                    // 'name' => 'Trade License',
                    // 'x_position' => '192',
                    // 'y_position' => '476',
                    // 'width' => 150,
                    // 'document_id' => '1',
                    // 'page_number' => '1',
                    // 'font' => 'Arial',
                    // 'font_size' => 'Size11',
                    // 'value' => "$tradelic",
                    // 'original_value' => "$tradelic",
                    // 'shared' => 'false',
                    // 'locked' => 'true',
                    // 'tab_label' => 'company',
                    // 'recipient_id' => '1'
                // ];
				$compaddressTabData = [
                    'name' => 'Company Address',
                    'x_position' => '86',
                    'y_position' => '502',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyaddress",
                    'original_value' => "$companyaddress",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                           
                // $creditlimitTabData = [
                    // 'name' => 'Credit Limit',
                    // 'x_position' => '142',
                    // 'y_position' => '196',
                    // 'width' => 150,
                    // 'document_id' => '1',
                    // 'page_number' => '1',
                    // 'font' => 'Arial',
                    // 'font_size' => 'Size11',
                    // 'value' => "$creditlimit",
                    // 'original_value' => "$creditlimit",
                    // 'shared' => 'false',
                    // 'locked' => 'true',
                    // 'tab_label' => 'company',
                    // 'recipient_id' => '1'
                // ];
                $creditlimit2TabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '86',
                    'y_position' => '611',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimit",
                    'original_value' => "$creditlimit",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $creditlimittextTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '192',
                    'y_position' => '177',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $creditlimittext2TabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '285',
                    'y_position' => '253',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $textTabData = [
                    'name' => 'Signer name',
                    'x_position' => '181',
                    'y_position' => '398',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '6',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'original_value' => 'Please insert your name',
                    'shared' => 'false',
                    'locked' => 'false',
                    'tab_label' => 'name',
                    'recipient_id' => '1',
                    'required' => 'true'
                ];                
                $signernameTabData = [
                    'name' => 'Signer name',
                    'x_position' => '92',
                    'y_position' => '231',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '6',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$text2TabData = [
                    'name' => 'Title',
                    'x_position' => '92',
                    'y_position' => '286',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '6',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'original_value' => 'Please insert your title',
                    'shared' => 'false',
                    'locked' => 'false',
                    'tab_label' => 'name',
                    'recipient_id' => '1',
                    'required' => 'true'
                ];
				$signHereTabData = [
                    'name' => 'Please sign here',
                    'x_position' => '95',
                    'y_position' => '313',
                    'optional' => 'false',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '6',
                ];
				$dateSignedTabData = [
                    'name' => 'Date signed',
                    'x_position' => '92',
                    'y_position' => '410',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '6',
                    'font_size' => 'Size11'
                ];
				
                $signHereTab = new DocuSignClient\Model\SignHere($signHereTabData);
                $dateSignedTab = new DocuSignClient\Model\DateSigned($dateSignedTabData);
                $dateSigned2Tab = new DocuSignClient\Model\DateSigned($dateSigned2TabData);
                $textTab = new DocuSignClient\Model\Text($textTabData);
                $text2Tab = new DocuSignClient\Model\Text($text2TabData);
                $signernamecontryTab = new DocuSignClient\Model\Text($signernamecontryTabData);
                $signernameTab = new DocuSignClient\Model\Text($signernameTabData);
                //$signername2Tab = new DocuSignClient\Model\Text($signername2TabData);
				$signername3Tab = new DocuSignClient\Model\Text($signername3TabData);
                $compnameTab = new DocuSignClient\Model\Text($compnameTabData);
                $compcountryTab = new DocuSignClient\Model\Text($compcountryTabData);
                //$creditlimitTab = new DocuSignClient\Model\Text($creditlimitTabData);
                $creditlimit2Tab = new DocuSignClient\Model\Text($creditlimit2TabData);
                $creditlimittextTab = new DocuSignClient\Model\Text($creditlimittextTabData);
                $creditlimittext2Tab = new DocuSignClient\Model\Text($creditlimittext2TabData);
                $passportnoTab = new DocuSignClient\Model\Text($passportnoTabData);
                //$tradelicTab = new DocuSignClient\Model\Text($tradelicTabData);
                $compaddressTab = new DocuSignClient\Model\Text($compaddressTabData);
                return [
                    'date_signed_tabs' => [$dateSignedTab, $dateSigned2Tab],
                    'sign_here_tabs' => [$signHereTab],
                    'text_tabs' => [$text2Tab, $signernamecontryTab, $signernameTab, $signername3Tab, $compnameTab, $compcountryTab, $creditlimit2Tab, $passportnoTab, $compaddressTab],
                ];
                break;
                break;
            case 2:
                $signername = $security->signername;
                $companyname = $security->creditrequest->company->companyname;
                $companycountry = $security->country->countryname;
                $creditlimit = $security->creditrequest->limit;
                $spell = new SpellNumber();
                $creditlimittext = "(" . $spell->spell($creditlimit) . " United States Dollars).";
                $passportno = $security->passportno;
                $signername = $security->signername;
				$national = $security->country->countryname;				
				$dateSignedTabData = [
                    'name' => 'Date signed',
                    'x_position' => '180',
                    'y_position' => '94',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '1',
                    'font_size' => 'Size11'
                ];
				$creditlimitTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '114',
                    'y_position' => '110',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimit",
                    'original_value' => "$creditlimit",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$creditlimittextTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '86',
                    'y_position' => '132',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                
                $signernameTabData = [
                    'name' => 'Signer name',
                    'x_position' => '86',
                    'y_position' => '155',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$nationalTabData = [
                    'name' => 'National',
                    'x_position' => '86',
                    'y_position' => '197',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$national",
                    'original_value' => "$national",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];				
				$passportnoTabData = [
                    'name' => 'Passport number',
                    'x_position' => '86',
                    'y_position' => '240',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$passportno",
                    'original_value' => "$passportno",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$creditlimit2TabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '86',
                    'y_position' => '326',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "US$/ " . "$creditlimit",
                    'original_value' => "US$/ " . "$creditlimit",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                
                $creditlimittext2TabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '86',
                    'y_position' => '343',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $issuanceTabData = [
                    'name' => 'Place of issuance',
                    'x_position' => '190',
                    'y_position' => '729',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'original_value' => 'Please insert Place of issuance',
                    'shared' => 'false',
                    'locked' => 'false',
                    'tab_label' => 'name',
                    'recipient_id' => '1',
                    'required' => 'true'
                ];
				
				$onbehalfTabData = [
                    'name' => 'Signer name',
                    'x_position' => '85',
                    'y_position' => '130',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '2',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                
                // $signplaceTabData = [
                //     'name' => 'Place of signature',
                //     'x_position' => '196',
                //     'y_position' => '720',
                //     'width' => 150,
                //     'document_id' => '1',
                //     'page_number' => '1',
                //     'font' => 'Arial',
                //     'font_size' => 'Size11',
                //     'original_value' => 'Please insert place of signature',
                //     'shared' => 'false',
                //     'locked' => 'false',
                //     'tab_label' => 'signplace',
                //     'recipient_id' => '1',
                //     'required' => 'true'
                // ];
								
                $dateSigned2TabData = [
                    'name' => 'Date signed',
                    'x_position' => '90',
                    'y_position' => '180',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '2',
                    'font_size' => 'Size11'
                ];
                
                $signHereTabData = [
                    'name' => 'Please sign here',
                    'x_position' => '90',
                    'y_position' => '130',
                    'optional' => 'false',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '2',
                ];
				
				
                $signHereTab = new DocuSignClient\Model\SignHere($signHereTabData);
                $dateSignedTab = new DocuSignClient\Model\DateSigned($dateSignedTabData);
                $dateSigned2Tab = new DocuSignClient\Model\DateSigned($dateSigned2TabData);
                //$signplaceTab = new DocuSignClient\Model\Text($signplaceTabData);
                $signernameTab = new DocuSignClient\Model\Text($signernameTabData);
				$onbehalfTab = new DocuSignClient\Model\Text($onbehalfTabData);
				$nationalTab = new DocuSignClient\Model\Text($nationalTabData);
                $creditlimitTab = new DocuSignClient\Model\Text($creditlimitTabData);
                $creditlimit2Tab = new DocuSignClient\Model\Text($creditlimit2TabData);
                $creditlimittextTab = new DocuSignClient\Model\Text($creditlimittextTabData);
                $creditlimittext2Tab = new DocuSignClient\Model\Text($creditlimittext2TabData);
                $passportnoTab = new DocuSignClient\Model\Text($passportnoTabData);
				//$issuanceTab = new DocuSignClient\Model\Text($issuanceTabData);
                return [
                    'sign_here_tabs' => [$signHereTab],
                    'date_signed_tabs' => [$dateSignedTab],
                    'text_tabs' => [$signernameTab, $nationalTab, $creditlimitTab, $creditlimittextTab, $creditlimit2Tab, $creditlimittext2Tab, $passportnoTab, $onbehalfTab]
                ];
                break;
            case 3:
                $companyname = $security->creditrequest->company->companyname;
                $companycountry = $security->creditrequest->company->country->countryname;
                $creditlimit = $security->creditrequest->limit;
                $spell = new SpellNumber();
                $creditlimittext = "(" . $spell->spell($creditlimit) . " United States Dollars)";
                $tradelic = $security->creditrequest->company->license;
				$signername = $security->signername;
				$dateSignedTabData = [
                    'name' => 'Date signed',
                    'x_position' => '188',
                    'y_position' => '116',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '1',
                    'font_size' => 'Size11'
                ];
				$creditlimitTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '114',
                    'y_position' => '132',
                    'width' => 151,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimit",
                    'original_value' => "$creditlimit",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$creditlimittextTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '86',
                    'y_position' => '153',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$compnameheadTabData = [
                    'name' => 'Company name',
                    'x_position' => '86',
                    'y_position' => '174',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyname",
                    'original_value' => "$companyname",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                
                $compcountryTabData = [
                    'name' => 'Country name',
                    'x_position' => '320',
                    'y_position' => '197',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companycountry",
                    'original_value' => "$companycountry",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                                
				$tradelicTabData = [
                    'name' => 'Trade License',
                    'x_position' => '215',
                    'y_position' => '219',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$tradelic",
                    'original_value' => "$tradelic",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $creditlimit2TabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '116',
                    'y_position' => '289',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimit",
                    'original_value' => "$creditlimit",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                
                $creditlimittext2TabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '86',
                    'y_position' => '305',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                                			
				$compnameTabData = [
                    'name' => 'Company name',
                    'x_position' => '85',
                    'y_position' => '125',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '2',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyname",
                    'original_value' => "$companyname",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$textTabData = [
                    'name' => 'Signer name',
                    'x_position' => '85',
                    'y_position' => '145',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '2',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
					'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'name',
                    'recipient_id' => '1',
                    'required' => 'true'
                ];                
				$signHereTabData = [
                    'name' => 'Please sign here',
                    'x_position' => '90',
                    'y_position' => '145',
                    'optional' => 'false',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '2',
                ];
                $signHereTab = new DocuSignClient\Model\SignHere($signHereTabData);
                $dateSignedTab = new DocuSignClient\Model\DateSigned($dateSignedTabData);
                $textTab = new DocuSignClient\Model\Text($textTabData);
                $compnameTab = new DocuSignClient\Model\Text($compnameTabData);
				$compnameheadTab = new DocuSignClient\Model\Text($compnameheadTabData);
                $compcountryTab = new DocuSignClient\Model\Text($compcountryTabData);
                $creditlimitTab = new DocuSignClient\Model\Text($creditlimitTabData);
                $creditlimit2Tab = new DocuSignClient\Model\Text($creditlimit2TabData);
                $creditlimittextTab = new DocuSignClient\Model\Text($creditlimittextTabData);
                $creditlimittext2Tab = new DocuSignClient\Model\Text($creditlimittext2TabData);
                $tradelicTab = new DocuSignClient\Model\Text($tradelicTabData);
                return [
                    'sign_here_tabs' => [$signHereTab],
                    'date_signed_tabs' => [$dateSignedTab],
                    'text_tabs' => [$compnameheadTab, $textTab, $compnameTab, $compcountryTab, $creditlimitTab, $creditlimit2Tab, $creditlimittextTab, $creditlimittext2Tab, $tradelicTab]
                ];
                break;			
            case 5:
                $companyname = $security->creditrequest->company->companyname;
                $address = $security->creditrequest->company->address;
                $companycountry = $security->creditrequest->company->country->countryname;
                $creditlimit = $security->creditrequest->limit;
                $spell = new SpellNumber();
                $creditlimittext = $spell->spell($creditlimit);
                $tradelic = $security->creditrequest->company->license;
                $tradelic2 = $security->commercial_register;
                $signername = $security->signername;
                $designation = $security->designation;
                $companyname2 = $security->company_name;
                $address2 = $security->address;
                $owner = $security->company_owner;
                $companycountry2 = $security->country->countryname;
				$dateSigned2TabData = [
                    'name' => 'Date signed Top',
                    'x_position' => '155',
                    'y_position' => '127',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '1',
                    'font_size' => 'Size11'
                ];                                
                $compname2TabData = [
                    'name' => 'Company name',
                    'x_position' => '86',
                    'y_position' => '172',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyname2",
                    'original_value' => "$companyname2",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $compcountry2TabData = [
                    'name' => 'Country name',
                    'x_position' => '302',
                    'y_position' => '197',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companycountry2",
                    'original_value' => "$companycountry2",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				// $tradelic2TabData = [
                    // 'name' => 'Trade License',
                    // 'x_position' => '186',
                    // 'y_position' => '197',
                    // 'width' => 150,
                    // 'document_id' => '1',
                    // 'page_number' => '1',
                    // 'font' => 'Arial',
                    // 'font_size' => 'Size11',
                    // 'value' => "$tradelic2",
                    // 'original_value' => "$tradelic2",
                    // 'shared' => 'false',
                    // 'locked' => 'true',
                    // 'tab_label' => 'company',
                    // 'recipient_id' => '1'
                // ];
                $address2TabData = [
                    'name' => 'Address',
                    'x_position' => '86',
                    'y_position' => '247',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$address2",
                    'original_value' => "$address2",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                
                $owner2TabData = [
                    'name' => 'Owner',
                    'x_position' => '86',
                    'y_position' => '297',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $title2TabData = [
                    'name' => 'Title',
                    'x_position' => '176',
                    'y_position' => '324',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$designation",
                    'original_value' => "$designation",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$ownerTabData = [
                    'name' => 'Owner',
                    'x_position' => '237',
                    'y_position' => '400',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$owner",
                    'original_value' => "$owner",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$compnameTabData = [
                    'name' => 'Company name',
                    'x_position' => '86',
                    'y_position' => '474',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyname",
                    'original_value' => "$companyname",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				$titleTabData = [
                    'name' => 'Title',
                    'x_position' => '88',
                    'y_position' => '450',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$designation",
                    'original_value' => "$designation",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $compcountryTabData = [
                    'name' => 'Country name',
                    'x_position' => '302',
                    'y_position' => '501',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companycountry",
                    'original_value' => "$companycountry",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                // $tradelicTabData = [
                    // 'name' => 'Trade License',
                    // 'x_position' => '188',
                    // 'y_position' => '475',
                    // 'width' => 150,
                    // 'document_id' => '1',
                    // 'page_number' => '1',
                    // 'font' => 'Arial',
                    // 'font_size' => 'Size11',
                    // 'value' => "$tradelic",
                    // 'original_value' => "$tradelic",
                    // 'shared' => 'false',
                    // 'locked' => 'true',
                    // 'tab_label' => 'company',
                    // 'recipient_id' => '1'
                // ];
                $addressTabData = [
                    'name' => 'Address',
                    'x_position' => '86',
                    'y_position' => '552',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$address",
                    'original_value' => "$address",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];                
                $creditlimitTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '186',
                    'y_position' => '600',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimit",
                    'original_value' => "$creditlimit",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $creditlimittextTabData = [
                    'name' => 'Credit Limit',
                    'x_position' => '186',
                    'y_position' => '642',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$creditlimittext",
                    'original_value' => "$creditlimittext",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                // $creditlimit2TabData = [
                //     'name' => 'Credit Limit',
                //     'x_position' => '123',
                //     'y_position' => '253',
                //     'width' => 150,
                //     'document_id' => '1',
                //     'page_number' => '1',
                //     'font' => 'Arial',
                //     'font_size' => 'Size12',
                //     'value' => "$creditlimit",
                //     'original_value' => "$creditlimit",
                //     'shared' => 'false',
                //     'locked' => 'true',
                //     'tab_label' => 'company',
                //     'recipient_id' => '1'
                // ];
                // $creditlimittext2TabData = [
                //     'name' => 'Credit Limit',
                //     'x_position' => '295',
                //     'y_position' => '253',
                //     'width' => 150,
                //     'document_id' => '1',
                //     'page_number' => '1',
                //     'font' => 'Arial',
                //     'font_size' => 'Size12',
                //     'value' => "$creditlimittext",
                //     'original_value' => "$creditlimittext",
                //     'shared' => 'false',
                //     'locked' => 'true',
                //     'tab_label' => 'company',
                //     'recipient_id' => '1'
                // ];
                // $textTabData = [
                    //     'name' => 'Signer name',
                    //     'x_position' => '196',
                //     'y_position' => '300',
                //     'width' => 150,
                //     'document_id' => '1',
                //     'page_number' => '5',
                //     'font' => 'Arial',
                //     'font_size' => 'Size12',
                //     'original_value' => 'Please insert your name',
                //     'shared' => 'false',
                //     'locked' => 'false',
                //     'tab_label' => 'name',
                //     'recipient_id' => '1',
                //     'required' => 'true'
                // ];
                $signernameTabData = [
                    'name' => 'Signer name',
                    'x_position' => '93',
                    'y_position' => '230',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '6',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $designationTabData = [
                    'name' => 'Designation',
                    'x_position' => '93',
                    'y_position' => '283',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '6',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$designation",
                    'original_value' => "$designation",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
                $dateSignedTabData = [
                    'name' => 'Date signed',
                    'x_position' => '93',
                    'y_position' => '388',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '6',
                    'font_size' => 'Size11'
                ];
                $signHereTabData = [
                    'name' => 'Please sign here',
                    'x_position' => '96',
                    'y_position' => '303',
                    'optional' => 'false',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '6',
                ];                
                $signernameTab = new DocuSignClient\Model\Text($signernameTabData);
                $designationTab = new DocuSignClient\Model\Text($designationTabData);
                $signHereTab = new DocuSignClient\Model\SignHere($signHereTabData);
                $dateSignedTab = new DocuSignClient\Model\DateSigned($dateSignedTabData);
                $dateSigned2Tab = new DocuSignClient\Model\DateSigned($dateSigned2TabData);
                // $textTab = new DocuSignClient\Model\Text($textTabData);
                $compnameTab = new DocuSignClient\Model\Text($compnameTabData);
                $compname2Tab = new DocuSignClient\Model\Text($compname2TabData);
                $compcountryTab = new DocuSignClient\Model\Text($compcountryTabData);
                $addressTab = new DocuSignClient\Model\Text($addressTabData);
                $address2Tab = new DocuSignClient\Model\Text($address2TabData);
                $compcountry2Tab = new DocuSignClient\Model\Text($compcountry2TabData);
                $creditlimitTab = new DocuSignClient\Model\Text($creditlimitTabData);
                // $creditlimit2Tab = new DocuSignClient\Model\Text($creditlimit2TabData);
                $creditlimittextTab = new DocuSignClient\Model\Text($creditlimittextTabData);
                // $creditlimittext2Tab = new DocuSignClient\Model\Text($creditlimittext2TabData);
                //$tradelicTab = new DocuSignClient\Model\Text($tradelicTabData);
                //$tradelic2Tab = new DocuSignClient\Model\Text($tradelic2TabData);
                $owner2Tab = new DocuSignClient\Model\Text($owner2TabData);
                $title2Tab = new DocuSignClient\Model\Text($title2TabData);
                $ownerTab = new DocuSignClient\Model\Text($ownerTabData);
                $titleTab = new DocuSignClient\Model\Text($titleTabData);
                return [
                    'sign_here_tabs' => [$signHereTab],
                    'date_signed_tabs' => [$dateSignedTab, $dateSigned2Tab],
                    'text_tabs' => [$compnameTab, $compname2Tab, $signernameTab, $designationTab, $compcountryTab, $compcountry2Tab, $addressTab, $address2Tab, $owner2Tab, $title2Tab, $creditlimittextTab]
                ];
                break;
				case 8:
				$signername = $security->signername;
                $companyname = $security->creditrequest->company->companyname;
				$signed_on = $security->creditrequest->company->signed_on;
				$designation = $security->creditrequest->company->signatorydesignation;
                $signername = $security->signername;
				
				$signernameTabData = [
                    'name' => 'Signer name',
                    'x_position' => '120',
                    'y_position' => '163',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				
				$checksTabData = [
                    'name' => 'Check numbers',
                    'x_position' => '112',
                    'y_position' => '215',
                    'width' => 250,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "",
                    'original_value' => "",
                    'shared' => 'false',
                    'locked' => 'false',
                    'tab_label' => 'checks',
                    'recipient_id' => '1'
                ];
				
				$capacityTabData = [
                    'name' => 'Signer name',
                    'x_position' => '112',
                    'y_position' => '276',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "Authorized signatory of $companyname",
                    'original_value' => "Authorized signatory of $companyname",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				
				$companynameTabData = [
                    'name' => 'Company name',
                    'x_position' => '112',
                    'y_position' => '351',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$companyname",
                    'original_value' => "$companyname",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				
				$contractsigned_onTabData = [
                    'name' => 'Signed on',
                    'x_position' => '218',
                    'y_position' => '379',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => date("j-M-Y",strtotime($signed_on)),
                    'original_value' => date("j-M-Y",strtotime($signed_on)),
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				
				$dateSignedTabData = [
                    'name' => 'Date signed',
                    'x_position' => '115',
                    'y_position' => '474',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '1',
                    'font_size' => 'Size11'
                ];
                $signernameTabData2 = [
                    'name' => 'Signer name',
                    'x_position' => '112',
                    'y_position' => '431',
                    'width' => 150,
                    'document_id' => '1',
                    'page_number' => '1',
                    'font' => 'Arial',
                    'font_size' => 'Size11',
                    'value' => "$signername",
                    'original_value' => "$signername",
                    'shared' => 'false',
                    'locked' => 'true',
                    'tab_label' => 'company',
                    'recipient_id' => '1'
                ];
				
                $signHereTabData = [
                    'name' => 'Please sign here',
                    'x_position' => '115',
                    'y_position' => '485',
                    'optional' => 'false',
                    'recipient_id' => '1',
                    'document_id' => '1',
                    'page_number' => '1',
                ];
				
				                
				$signernameTab = new DocuSignClient\Model\Text($signernameTabData);
				$checksTab = new DocuSignClient\Model\Text($checksTabData);
				$capacityTab = new DocuSignClient\Model\Text($capacityTabData);
				$companynameTab = new DocuSignClient\Model\Text($companynameTabData);
				$contractsigned_onTab = new DocuSignClient\Model\Text($contractsigned_onTabData);
                $signernameTab2 = new DocuSignClient\Model\Text($signernameTabData2);								
                $signHereTab = new DocuSignClient\Model\SignHere($signHereTabData);
                $dateSignedTab = new DocuSignClient\Model\DateSigned($dateSignedTabData);
				return [
                    'sign_here_tabs' => [$signHereTab],
                    'date_signed_tabs' => [$dateSignedTab],
                    'text_tabs' => [$signernameTab, $checksTab, $capacityTab, $companynameTab, $contractsigned_onTab, $signernameTab2]
                ];
                break;
        }
    }

    private function createDeliveryTabs()
    {
        $textTabData = [
            'name' => 'Signer name',
			'anchor_string' => 'Signer Name:',
			'anchor_x_offset' => '0',
			'anchor_y_offset' => '0.15',
			'anchor_units' => 'inches',
            'width' => 150,
            'document_id' => '1',            
            'font' => 'Arial',
            'font_size' => 'Size11',
            'original_value' => 'please insert your name',
            'shared' => 'false',
            'locked' => 'false',
            'tab_label' => 'name',
            'recipient_id' => '1',
            'required' => 'true'
        ];
        $signHereTabData = [
            'name' => 'Please sign here',
            'anchor_string' => 'Signature:',
			'anchor_x_offset' => '0',
			'anchor_y_offset' => '0.4',
			'anchor_units' => 'inches',
            'optional' => 'false',
            'recipient_id' => '1',
            'document_id' => '1',
        ];
        $dateSignedTabData = [
            'name' => 'Date signed',
            'anchor_string' => 'Signature:',
			'anchor_x_offset' => '0',
			'anchor_y_offset' => '0.7',
			'anchor_units' => 'inches',
            'recipient_id' => '1',
            'document_id' => '1',
            'font_size' => 'Size12'
        ];

        $signHereTab = new DocuSignClient\Model\SignHere($signHereTabData);
        $dateSignedTab = new DocuSignClient\Model\DateSigned($dateSignedTabData);
        $textTab = new DocuSignClient\Model\Text($textTabData);

        return [
            'sign_here_tabs' => [$signHereTab],
            'date_signed_tabs' => [$dateSignedTab],
            'text_tabs' => [$textTab]
        ];

    }

    private function createDocumentEventNotification($callbackUrl)
    {
        $securityEventNotificationData = [
            'url' => $callbackUrl,
            'envelope_events' => [['envelopeEventStatusCode' => "completed"]],
            'recipient_events' => [['recipientEventStatusCode' => "AutoResponded"]]
        ];
        return array_merge(self::eventNotificationCommonData(), $securityEventNotificationData);
    }

    private static function eventNotificationCommonData()
    {
        return [
            'logging_enabled' => 'true',
            'include_time_zone' => 'true',
            'include_documents' => 'false',
            'use_soap_interface' => 'false',
            'require_acknowledgment' => 'true',
            'include_document_fields' => 'false',
            'include_envelope_void_reason' => 'true',
            'sign_message_with_x509_cert' => 'false',
            'include_certificate_with_soap' => 'false',
            'include_certificate_of_completion' => 'false'
        ];
    }

    private function createContractEventNotification()
    {
        $callbackUrl = !empty(env('APP_URL')) ? env('APP_URL') . self::CONTRACT_WEB_HOOK_URL : url(self::CONTRACT_WEB_HOOK_URL, [], true);
        $envelopeStatusCodes = ['completed', 'sent', 'delivered', 'voided', 'declined'];
        $recipientStatusCodes = ['completed', 'sent', 'delivered', 'AutoResponded', 'declined', 'AuthenticationFailed'];

        $envelopeEvents = $recipientEvents = [];

        foreach ($envelopeStatusCodes as $code) {
            $envelopeEvents[] = [
                'envelopeEventStatusCode' => $code
            ];
        }
        foreach ($recipientStatusCodes as $code) {
            $recipientEvents[] = [
                'recipientEventStatusCode' => $code
            ];
        }

        $contractEventNotificationData = [
            'url' => $callbackUrl,
            'envelope_events' => $envelopeEvents,
            'recipient_events' => $recipientEvents
        ];
        return array_merge($contractEventNotificationData, self::eventNotificationCommonData());
    }

    private function createEnvelopeSigners($company, $selfEntity)
    {
        // Company Signer 
        $companySignerData = [
            'email' => $company['email'],
            'name' => $company['name'],
            'recipient_id' => 1,
            'routing_order' => 1,
            'tabs' => $company['tabs']
        ];

        // Self entity (Bizzmo) Signer
        $selfEntitySignerData = [
            'email' => $selfEntity['email'],
            'name' => $selfEntity['name'],
            'recipient_id' => 2,
            'routing_order' => 2,
            'tabs' => $selfEntity['tabs']
        ];

        return [
            'company' => $companySignerData,
            'self_entity' => $selfEntitySignerData
        ];
    }

    private function createEnvelopeTabs($company, $isBuyer)
    {
        $tabsPositions = $isBuyer ? self::getBuyerEnvelopeTabsPositions() : self::getSupplierEnvelopeTabsPositions();
        $companyTabsPositions = $tabsPositions['company'];
        $selfEntityTabsPositions = $tabsPositions['self_entity'];
        $companySignHereTabsData = $companyDateSignedTabsData = $selfEntitySignHereTabsData = $selfEntityDateSignedTabsData = [];
        $sharedTextTabs = $companyTextTabs = $companySignHereTabs = $companyDateSignedTabs = $selfEntitySignHereTabs = $selfEntityDateSignedTabs = [];
        // Company sign here | date tabs
        foreach ($companyTabsPositions as $position) {
            $companySignHereTabsData[] = [
                'name' => 'Please sign here',
                'x_position' => $position['sign_x_position'],
                'y_position' => $position['sign_y_position'],
                'optional' => 'false',
                'recipient_id' => '1',
                'document_id' => '1',
                'page_number' => $position['page_number'],
            ];
            $companyDateSignedTabsData[] = [
                'name' => 'Date signed',
                'x_position' => $position['date_x_position'],
                'y_position' => $position['date_y_position'],
                'recipient_id' => '1',
                'document_id' => '1',
                'page_number' => $position['page_number'],
                'font_size' => 'Size11'
            ];
        }

        // Self Entity (Bizzmo) sign here | date tabs
        foreach ($selfEntityTabsPositions as $position) {
            $selfEntitySignHereTabsData[] = [
                'name' => 'Please sign here',
                'x_position' => $position['sign_x_position'],
                'y_position' => $position['sign_y_position'],
                'optional' => 'false',
                'recipient_id' => '1',
                'document_id' => '1',
                'page_number' => $position['page_number']
            ];
            $selfEntityDateSignedTabsData[] = [
                'name' => 'Date signed',
                'x_position' => $position['date_x_position'],
                'y_position' => $position['date_y_position'],
                'recipient_id' => '1',
                'document_id' => '1',
                'page_number' => $position['page_number'],
                'font_size' => 'Size11'
            ];
        }

        if ($isBuyer) {
            // Shared Text Tab (PDF Text Manipulation)
            $textTabsData = $this->getTextTabsBuyer($company);
            $sharedTextTabsData = $textTabsData['shared'];
            $companyTextTabsData = $textTabsData['company'];
            foreach ($sharedTextTabsData as $textTabDate) {
                $sharedTextTabs[] = new DocuSignClient\Model\Text($textTabDate);
            }
            foreach ($companyTextTabsData as $textTabDate) {
                $companyTextTabs[] = new DocuSignClient\Model\Text($textTabDate);
            }
        } else {
			$textTabsData = $this->getTextTabsSupplier($company);
            $sharedTextTabsData = $textTabsData['shared'];
            $companyTextTabsData = $textTabsData['company'];
            foreach ($sharedTextTabsData as $textTabDate) {
                $sharedTextTabs[] = new DocuSignClient\Model\Text($textTabDate);
            }
            foreach ($companyTextTabsData as $textTabDate) {
                $companyTextTabs[] = new DocuSignClient\Model\Text($textTabDate);
            }
		}
        foreach ($companySignHereTabsData as $companySignHereTabData) {
            $companySignHereTabs[] = new DocuSignClient\Model\SignHere($companySignHereTabData);
        }
        foreach ($companyDateSignedTabsData as $companyDateSignedTab) {
            $companyDateSignedTabs[] = new DocuSignClient\Model\DateSigned($companyDateSignedTab);
        }
        foreach ($selfEntitySignHereTabsData as $selfEntitySignHereTabData) {
            $selfEntitySignHereTabs[] = new DocuSignClient\Model\SignHere($selfEntitySignHereTabData);
        }
        foreach ($selfEntityDateSignedTabsData as $selfEntityDateSignedTab) {
            $selfEntityDateSignedTabs[] = new DocuSignClient\Model\DateSigned($selfEntityDateSignedTab);
        }

        $companyTabsData = [
            'sign_here_tabs' => $companySignHereTabs,
            'date_signed_tabs' => $companyDateSignedTabs,
            'text_tabs' => $companyTextTabs
        ];

        $selfEntityTabsData = [
            'sign_here_tabs' => $selfEntitySignHereTabs,
            'date_signed_tabs' => $selfEntityDateSignedTabs,
            'text_tabs' => $sharedTextTabs
        ];

        return [
            'company' => new DocuSignClient\Model\Tabs($companyTabsData),
            'self_entity' => new DocuSignClient\Model\Tabs($selfEntityTabsData)
        ];
    }

	private function getTextTabsBuyer($company)
    {
        $date = new \DateTime();
        $dateFormatted = $date->format("j-M-Y");
        $poBox = $company->pobox ? ', POB ' . $company->pobox : '';
        $companyInfo = $company->address . $poBox . ', ' . $company->city->cityname;
        $countryName = $company->country->countryname;
        $sharedTabs = [
            [
                'name' => 'Company name',
                'x_position' => '112',
                'y_position' => '217',
                'document_id' => '1',
                'page_number' => '1',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->companyname",
                'original_value' => "$company->companyname",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company country',
                'x_position' => '112',
                'y_position' => '270',
                'document_id' => '1',
                'page_number' => '1',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$countryName",
                'original_value' => "$countryName",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company info',
                'x_position' => '112',
                'y_position' => '309',
                'document_id' => '1',
                'page_number' => '1',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$companyInfo",
                'original_value' => "$companyInfo",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company email',
                'x_position' => '108',
                'y_position' => '137',
                'document_id' => '1',
                'page_number' => '13',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->email",
                'original_value' => "$company->email",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company name',
                'x_position' => '105',
                'y_position' => '109',
                'document_id' => '1',
                'page_number' => '15',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->companyname",
                'original_value' => "$company->companyname",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company address',
                'x_position' => '132',
                'y_position' => '147',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '15',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$companyInfo",
                'original_value' => "$companyInfo",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company email',
                'x_position' => '159',
                'y_position' => '210',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '15',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->email",
                'original_value' => "$company->email",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company telephone',
                'x_position' => '142',
                'y_position' => '249',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '15',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->phone",
                'original_value' => "$company->phone",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company fax',
                'x_position' => '110',
                'y_position' => '274',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '15',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->fax",
                'original_value' => "$company->fax",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Date',
                'x_position' => '113',
                'y_position' => '362',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '15',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$dateFormatted",
                'original_value' => "$dateFormatted",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company name',
                'x_position' => '85',
                'y_position' => '447',
                'document_id' => '1',
                'page_number' => '16',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->companyname",
                'original_value' => "$company->companyname",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Date',
                'x_position' => '468',
                'y_position' => '477',
                'document_id' => '1',
                'page_number' => '16',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$dateFormatted",
                'original_value' => "$dateFormatted",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company name',
                'x_position' => '85',
                'y_position' => '643',
                'document_id' => '1',
                'page_number' => '16',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->companyname",
                'original_value' => "$company->companyname",
                'shared' => 'true',
                'locked' => 'true'
            ]
        ];

        $companyTabs = [
            [
                'name' => 'Recipient name',
                'x_position' => '326',
                'y_position' => '352',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '14',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'original_value' => 'please insert your name',
                'shared' => 'false',
                'locked' => 'false',
                'tab_label' => 'name',
                'recipient_id' => '1',
                'required' => 'true'
            ], [
                'name' => 'Recipient designation',
                'x_position' => '326',
                'y_position' => '401',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '14',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'original_value' => 'please insert your designation',
                'shared' => 'false',
                'locked' => 'false',
                'tab_label' => 'designation',
                'recipient_id' => '1',
                'required' => 'true'
            ],[
                'name' => 'Recipient name',
                'x_position' => '86',
                'y_position' => '520',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '16',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'original_value' => 'please insert your name',
                'shared' => 'false',
                'locked' => 'false',
                'tab_label' => 'name',
                'recipient_id' => '1',
                'required' => 'true'
            ], [
                'name' => 'Recipient designation',
                'x_position' => '86',
                'y_position' => '538',
                'width' => 200,
                'document_id' => '1',
                'page_number' => '16',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'original_value' => 'please insert your designation',
                'shared' => 'false',
                'locked' => 'false',
                'tab_label' => 'designation',
                'recipient_id' => '1',
                'required' => 'true'
            ]
        ];

        return [
            'shared' => $sharedTabs,
            'company' => $companyTabs
        ];
    }
	
	private function getTextTabsSupplier($company)
    {
        $date = new \DateTime();
        $dateFormatted = $date->format("F j, Y");
        $poBox = $company->pobox ? ', POB ' . $company->pobox : '';
        $companyInfo = $company->address . $poBox . ', ' . $company->city->cityname;
        $countryName = $company->country->countryname;
        $sharedTabs = [
            [
                'name' => 'Company name',
                'x_position' => '112',
                'y_position' => '155',
                'document_id' => '1',
                'page_number' => '1',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$company->companyname",
                'original_value' => "$company->companyname",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company country',
                'x_position' => '112',
                'y_position' => '215',
                'document_id' => '1',
                'page_number' => '1',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$countryName",
                'original_value' => "$countryName",
                'shared' => 'true',
                'locked' => 'true'
            ], [
                'name' => 'Company info',
                'x_position' => '112',
                'y_position' => '265',
                'document_id' => '1',
                'page_number' => '1',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'bold' => 'false',
                'value' => "$companyInfo",
                'original_value' => "$companyInfo",
                'shared' => 'true',
                'locked' => 'true'
            ]
        ];

        $companyTabs = [
            [
                'name' => 'Recipient name',
                'x_position' => '336',
                'y_position' => '485',
                'width' => 180,
                'document_id' => '1',
                'page_number' => '8',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'original_value' => 'please insert your name',
                'shared' => 'false',
                'locked' => 'false',
                'tab_label' => 'name',
                'recipient_id' => '1',
                'required' => 'true'
            ], [
                'name' => 'Recipient designation',
                'x_position' => '336',
                'y_position' => '536',
                'width' => 160,
                'document_id' => '1',
                'page_number' => '8',
                'font' => 'Arial',
                'font_size' => 'Size11',
                'original_value' => 'please insert your designation',
                'shared' => 'false',
                'locked' => 'false',
                'tab_label' => 'designation',
                'recipient_id' => '1',
                'required' => 'true'
            ]
        ];

        return [
            'shared' => $sharedTabs,
            'company' => $companyTabs
        ];
    }
		
    private static function getBuyerEnvelopeTabsPositions()
    {
        return [
            'company' => [
                [
                    'sign_x_position' => '334',
                    'sign_y_position' => '432',
                    'date_x_position' => '356',
                    'date_y_position' => '497',
                    'page_number' => '14'
                ],
                [
                    'sign_x_position' => '90',
                    'sign_y_position' => '541',
                    'date_x_position' => '87',
                    'date_y_position' => '601',
					'font' => 'Arial',
					'font_size' => 'Size11',
                    'page_number' => '16'
                ]
            ],
            'self_entity' => [
                [
                    'sign_x_position' => '123',
                    'sign_y_position' => '432',
                    'date_x_position' => '150',
                    'date_y_position' => '497',
                    'page_number' => '14'
                ],
                [
                    'sign_x_position' => '90',
                    'sign_y_position' => '305',
                    'date_x_position' => '87',
                    'date_y_position' => '365',
					'font' => 'Arial',
					'font_size' => 'Size11',
                    'page_number' => '16'
                ]
            ],
        ];
    }

    private static function getSupplierEnvelopeTabsPositions()
    {
        return [
            'company' => [
                [
                    'sign_x_position' => '341',
                    'sign_y_position' => '556',
                    'date_x_position' => '337',
                    'date_y_position' => '638',
                    'page_number' => '8'
                ]
            ],
            'self_entity' => [
                [
                    'sign_x_position' => '125',
                    'sign_y_position' => '556',
                    'date_x_position' => '118',
                    'date_y_position' => '638',
                    'page_number' => '8'
                ]
            ],
        ];
    }

    private function preRequest()
    {
        // create configuration object and configure custom auth header
        $config = new DocuSignClient\Configuration();
        $config->setHost($this->host);
        $headerValue = '{"Username": ' . '"' . $this->username . '"' . ',"Password": ' . '"' . $this->password . '"' . ',"IntegratorKey":' . '"' . $this->integratorKey . '"' . '}';
        $config->addDefaultHeader("X-DocuSign-Authentication", $headerValue);

        // instantiate a new docusign api client
        $apiClient = new DocuSignClient\ApiClient($config);
        $accountId = null;

        try {
            // STEP 1 - Login API: get first Account ID and baseURL
            $authenticationApi = new DocuSignClient\Api\AuthenticationApi($apiClient);
            $options = new DocuSignClient\Api\AuthenticationApi\LoginOptions();
            $loginInformation = $authenticationApi->login($options);

            if (!empty($loginInformation)) {
                $loginAccount = $loginInformation->getLoginAccounts()[0];
                $host = $loginAccount->getBaseUrl();
                $host = explode("/v2", $host);
                $host = $host[0];
    
                // UPDATE configuration object
                $config->setHost($host);
        
                // instantiate a NEW docusign api client (that has the correct baseUrl/host)
                $apiClient = new DocuSignClient\ApiClient($config);

                if (!empty($loginInformation)) {
                    $accountId = $loginAccount->getAccountId();
                    if (!empty($accountId)) {
                        return [
                            'account_id' => $accountId,
                            'api_client' => $apiClient
                        ];
                    }
                    return null;
                }
            }
        } catch (DocuSignClient\ApiException $ex) {
            \Log::Error("Exception: " . $ex->getMessage() . "\n");
        }
    }
}