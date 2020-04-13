<?php 

namespace App\Helpers;

use Storage;
use Illuminate\Http\Response;

class AdobeSignHelper {
    const REQUESTS_URLS = [
        'ACCESS_TOKEN' => 'https://api.na2.echosign.com/oauth/refresh',
        'API_ACCESS_POINT' => 'https://api.na2.echosign.com/api/rest/v5'
    ];
    const SUPPLIER_TEMPLATE = 'Supplier_T&C';
    const BUYER_TEMPLATE = 'Customer_T&C';
    const CONTRACT_WEBHOOK_REL_PATH = '/api/signature/adobe-sign/contract';
    
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $accessToken;

    public function __construct() {
        $this->clientId = env('ADOBE_CLIENT_ID');
        $this->clientSecret = env('ADOBE_CLIENT_SECRET');
        $this->refreshToken = env('ADOBE_REFRESH_TOKEN');
    }

    private function getAccessToken($refreshToken, $renew = false) {
        if ($this->accessToken && !$renew)
            return $this->accessToken;
        
        if (!$refreshToken)
            return null;

        $postData = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken
        ];

        $options = self::commonCurlOpts();
        $options[CURLOPT_URL] = self::REQUESTS_URLS['ACCESS_TOKEN'];
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
            return $responseDecoded['access_token'];
        }
        return null;
    }

    public function createAgreement($agrName, $companyName, $fRecipientEmail, $sRecipientEmail = null, $isBuyer = true) {
        $this->accessToken = $this->getAccessToken($this->refreshToken);

        if(!$this->accessToken)
            throw new \Exception("Create Agreement: Access token is empty");
        
        $transientDocId = $this->getTransientDocumentId($isBuyer);

        if(!$transientDocId)
            throw new \Exception("Transient document id is empty");

        if (!$sRecipientEmail)
            $sRecipientEmail = env('CONTRACT_LAST_RECIP');

        $recipientSetInfos = self::constructRecipientSetInfos($fRecipientEmail, $sRecipientEmail);
        $formFields = self::constructFormFields($isBuyer, $companyName);
        $callbackUrl = !empty(env('APP_URL')) ? env('APP_URL') . self::CONTRACT_WEBHOOK_REL_PATH : url(self::CONTRACT_WEBHOOK_REL_PATH, [], true);
        $postData = [
            "documentCreationInfo" => [
                "fileInfos" => [
                    ["transientDocumentId" => $transientDocId]
                ],
                "name" => $agrName,
                "recipientSetInfos" => $recipientSetInfos,
                "callbackInfo" => $callbackUrl,
                "signatureType" => "ESIGN",
                "formFields" =>  $formFields,
                "signatureFlow" => "SEQUENTIAL"
            ],
        ];
        $options = self::commonCurlOpts();
        $options[CURLOPT_URL] = self::REQUESTS_URLS['API_ACCESS_POINT'] . '/agreements';
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $this->accessToken . '', 'Content-Type: application/json']; 
        $options[CURLOPT_POSTFIELDS] = json_encode($postData);

        $result = self::execCurl($options);
        $response = $result['response'];
        $httpCode = $result['httpCode'];

        if ($httpCode != Response::HTTP_CREATED)
            throw new \Exception("$httpCode: Failed to create agreement");

        if($response) {
            $responseDecoded = json_decode($response, true);
            return $responseDecoded['agreementId'];
        }
        return null;
    }

    public function getSignedContract($agrId) {
        $this->accessToken = $this->getAccessToken($this->refreshToken);

        if(!$this->accessToken)
            throw new \Exception("Signed Contract: Access token is empty");

        $options = self::commonCurlOpts();
        $options[CURLOPT_URL] = self::REQUESTS_URLS['API_ACCESS_POINT'] . '/agreements/' . $agrId . '/combinedDocument';
        $options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $this->accessToken . ''];
        
        $result = self::execCurl($options);
        $response = $result['response'];
        $httpCode = $result['httpCode'];

        if ($httpCode != Response::HTTP_OK)
            throw new \Exception("$httpCode: Failed to get signed contract");

        return $response ? $response : null;
    }

    private static function constructRecipientSetInfos($fRecipientEmail, $sRecipientEmail) {
        return [ 
            [
                "recipientSetMemberInfos" => [
                    [
                        "email" => $fRecipientEmail,
                    ]
                ],
                    "recipientSetRole" => "SIGNER"
            ],
            [
                "recipientSetMemberInfos" => [
                    [
                        "email" => $sRecipientEmail,
                    ]
                ],
                    "recipientSetRole" => "SIGNER"
            ],
        ];
    }

    private static function constructFormFields($isBuyer, $companyName) {
        if ($isBuyer) {
            return [
                [
                    "backgroundColor" => "#ffffff",
                    "fontColor" => "#000",
                    "inputType" => "TEXT_FIELD",
                    "locations" => [
                        [
                            "pageNumber" => 14,
                            "top" => 716.6099853515625,
                            "left" => 117.31999969482422,
                            "width" => 185.1600112915039,
                            "height" => 12.739990234375
                        ]
                    ],
                    "alignment" => "LEFT",
                    "contentType" => "DATA",
                    "defaultValue" => "Bizzmo",
                    "readOnly" => true,
                    "name" => "entity_name",
                ],
                [
                    "backgroundColor" => "#ffffff",
                    "fontColor" => "#000",
                    "visible" => true,
                    "inputType" => "TEXT_FIELD",
                    "locations" => [
                        [
                            "pageNumber" => 14,
                            "top" => 729.3599853515625,
                            "left" => 106.06999969482422,
                            "width" => 92.94998931884766,
                            "height" => 11.989990234375
                        ]
                    ],
                    "alignment" => "LEFT",
                    "contentType" => "DATA",
                    "defaultValue" => $companyName,
                    "readOnly" => true,
                    "name" => "company_name",
                ],
                [
                    "visible" => true,
                    "inputType" => "SIGNATURE",
                    "locations" => [
                        [
                            "pageNumber" => 14,
                            "top" => 286.33001708984375,
                            "left" => 93.33000183105469,
                            "width" => 110.94000244140625,
                            "height" => 22.489990234375
                        ]
                    ],
                    "alignment" => "LEFT",
                    "contentType" => "SIGNATURE",
                    "readOnly" => false,
                    "required" => true,
                    "name" => "Signature 2",
                    "recipientIndex" => 2
                ],
                [
                    "visible" => true,
                    "inputType" => "SIGNATURE",
                    "locations" => [
                        [
                            "pageNumber" => 14,
                            "top" => 212.8699951171875,
                            "left" => 285.9800109863281,
                            "width" => 173.91000366210938,
                            "height" => 33.72999572753906
                        ]
                    ],
                    "alignment" => "LEFT",
                    "contentType" => "SIGNATURE",
                    "defaultValue" => "",
                    "readOnly" => false,
                    "required" => true,
                    "name" => "Signature 1",
                    "recipientIndex" => 1
                ]
            ];
        } else {
            return [
                [
                    "visible" => true,
                    "inputType" => "SIGNATURE",
                    "locations" => [
                        [
                            "pageNumber" => 7,
                            "top" => 115.41998291015625,
                            "left" => 85.83000183105469,
                            "width" => 168.66000366210938,
                            "height" => 35.97999572753906
                        ]
                    ],
                    "alignment" => "LEFT",
                    "contentType" => "SIGNATURE",
                    "readOnly" => false,
                    "required" => true,
                    "name" => "Signature 1",
                    "recipientIndex" => 1
                ],
                [
                    "visible" => true,
                    "inputType" => "SIGNATURE",
                    "locations" => [
                        [
                            "pageNumber" => 7,
                            "top" => 115.41998291015625,
                            "left" => 289.7300109863281,
                            "width" => 182.91000366210938,
                            "height" => 37.47999572753906
                        ]
                    ],
                    "alignment" => "LEFT",
                    "contentType" => "SIGNATURE",
                    "readOnly" => false,
                    "required" => true,
                    "name" => "Signature 2",
                    "recipientIndex" => 2
                ]
            ];
        }
    }

    private function getTransientDocumentId($isBuyer) {
        $result = $this->uploadTransientDocument($isBuyer);
        $response = $result['response'];
        $httpCode = $result['httpCode'];

        if ($httpCode != Response::HTTP_CREATED)
            throw new \Exception("$httpCode: Failed to create transient document");

        if ($response) {
            $responseDecoded = json_decode($response, true);
            return $responseDecoded['transientDocumentId'];
        }
        return null;
    }

    private function uploadTransientDocument($isBuyer) {
        $this->accessToken = $this->getAccessToken($this->refreshToken);

        $fileName = $isBuyer ? self::BUYER_TEMPLATE : self::SUPPLIER_TEMPLATE;
        $filePath = realpath(storage_path('app/contract_templates/' . $fileName . '.pdf'));
        if (!file_exists($filePath))
            throw new \Exception("$fileName path doesn't exist");

        $cFile = new \CURLFile($filePath, 'application/pdf', $fileName);

        $options = self::commonCurlOpts();
        $options[CURLOPT_URL] = self::REQUESTS_URLS['API_ACCESS_POINT'] . '/transientDocuments';
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_HTTPHEADER] = ['Authorization:Bearer ' . $this->accessToken . '', 'Content-Type: multipart/form-data'];
        $options[CURLOPT_POSTFIELDS] = ['File' => $cFile];

        $result = self::execCurl($options);
        $response = $result['response'];
        $httpCode = $result['httpCode'];

        // Renew access token in case it's expired
        if ($httpCode == Response::HTTP_UNAUTHORIZED) {
            $this->accessToken = $this->getAccessToken($this->refreshToken, true);
            $this->uploadTransientDocument($isBuyer);
        }
        return $result;
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
}