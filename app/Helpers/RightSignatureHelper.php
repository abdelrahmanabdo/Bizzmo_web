<?php

namespace App\Helpers;

use App\Settings;
use App\Attachment;
use App\Attachmenttype;

class RightSignatureHelper
{
  private $ACCESS_TOKEN_URL = 'https://api.rightsignature.com/oauth/token';
  private $SENDING_REQUEST_URL = 'https://api.rightsignature.com/public/v1/sending_requests';
  private $SEND_DELIVERY_DOCUMENT_URL = 'https://rightsignature.com/api/documents.xml';

  public function getAccessToken()
  {
    $clientId = env('RIGHT_SIGNATURE_CLIENT_ID') ? env('RIGHT_SIGNATURE_CLIENT_ID') : "f44a011e4ccc08ad060ee99ce2350384ec3a20cb17e2a402a3365a0d7579fbed";
    $clientSecret = env('RIGHT_SIGNATURE_CLIENT_SECERT') ? env('RIGHT_SIGNATURE_CLIENT_SECERT') : "754f9a2749fd18852dae75c6abd4ee2f88faac33b19fda17638d7c74db6d3d99";
    $refreshToken = $this->getRefreshToken();

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->ACCESS_TOKEN_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "grant_type=refresh_token&client_id=$clientId&client_secret=$clientSecret&refresh_token=$refreshToken",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
      )
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err)
      throw new \Exception("Getting access token failed: " . $err);

    $decoded = json_decode($response, true);

    $refresh = $decoded['refresh_token'];
    $access = $decoded['access_token'];

    // Save the new refresh token
    $this->setRefreshToken($refresh);

    return $access;
  }

  public function sendRequest($data, $attachmentPath)
  {
    // Get access token
    $accessToken = $this->getAccessToken();

    $options[CURLOPT_URL] = $this->SENDING_REQUEST_URL;
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $accessToken . '', 'Content-Type: application/json'];
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
    $options[CURLOPT_POSTFIELDS] = json_encode($data);

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    curl_close($curl);

    if (!$response)
      throw new \Exception("Sending request failed");

    $decoded = json_decode($response, true);

    $processId = $decoded['sending_request']['id'];
    $uploadUrl = $decoded['sending_request']['upload_url'];

    $this->uploadDocument($attachmentPath, $uploadUrl);
    $this->submitUploaded($processId);

    return $decoded['sending_request'];
  }

  private function uploadDocument($filePath, $uploadUrl)
  {
    $file = realpath($filePath);
    $cFile = new \CURLFile($file, 'application/pdf', 'delivery_document');

    $options[CURLOPT_URL] = $uploadUrl;
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_CUSTOMREQUEST] = "PUT";
    $options[CURLOPT_HTTPHEADER] = ['Content-Type: multipart/form-data'];
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
    $options[CURLOPT_POSTFIELDS] = ['file' => $cFile];

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpcode != 200)
      throw new \Exception("Sending request failed");

    return true;
  }

  private function submitUploaded($id)
  {
    // Get access token
    $accessToken = $this->getAccessToken();

    $options[CURLOPT_URL] = "https://api.rightsignature.com/public/v1/sending_requests/$id/uploaded";
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $accessToken . ''];
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (!$response)
      throw new \Exception("Sending request failed");

    return true;
  }

  private function getRefreshToken()
  {
    $rightSignature = Settings::where('key', 'rightSignature')->first();
    $rightSignature = json_decode($rightSignature->value);

    if (!$rightSignature)
      throw new \Exception("Getting refresh token failed");

    return $rightSignature->refreshToken;
  }

  private function setRefreshToken($refreshToken)
  {
    $rightSignature = Settings::where('key', 'rightSignature')->first();
    $value = json_decode($rightSignature->value);

    if (!$value)
      throw new \Exception("Setting refresh token failed");

    $value->refreshToken = $refreshToken;
    $newValue = json_encode($value);
    $rightSignature->value = $newValue;

    // Save new value
    $rightSignature->save();
  }

  public function sendDeliveryDocument($po, $attachmentPath)
  {
    $data = [
      "file" => [
        "name" => "delivery_document.pdf",
        "source" => "upload"
      ],
      "document" => [
        "signer_sequencing" => false,
        "expires_in" => 365,
        "name" => "Delivery Document",
        "callback_url" => $this->getRelativeCallbackURL("/api/signature/right-signature/document/update"),
        "roles" => [[
          "name" => "a",
          "signer_name" => $po->company->companyname,
          "signer_email" => $po->company->email
        ]]
      ],
      "sending_request" => [],
      "callback_url" => $this->getRelativeCallbackURL("/api/signature/right-signature/request/update"),
    ];

    $response = $this->sendRequest($data, $attachmentPath);
    $this->createDeliveryAttachment($po, $response['id']);
  }

  public function sendSecurityDocument($security, $attachmentPath)
  {
    $data = [
      "file" => [
        "name" => "security_document.pdf",
        "source" => "upload"
      ],
      "document" => [
        "signer_sequencing" => false,
        "expires_in" => 365,
        "name" => "Security Document",
        "callback_url" => $this->getRelativeCallbackURL("/api/signature/right-signature/securites-document/update"),
        "roles" => [[
          "name" => "a",
          "signer_name" => $security->signername,
          "signer_email" => $security->signeremail
        ]]
      ],
      "sending_request" => [],
      "callback_url" => $this->getRelativeCallbackURL("/api/signature/right-signature/request/update"),
    ];

    $response = $this->sendRequest($data, $attachmentPath);
    $this->createSecurityAttachment($security, $response['id']);
  }

  private function getRelativeCallbackURL($route)
  {
    if (env('APP_URL'))
      return env('APP_URL') . $route;
    else
      return url($route, [], true);
  }

  public function getSignedyDocument($documentId)
  {
    // Get access token
    $accessToken = $this->getAccessToken();

    $options[CURLOPT_URL] = "https://api.rightsignature.com/public/v1/documents/$documentId";
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_CUSTOMREQUEST] = "GET";
    $options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $accessToken . ''];
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err)
      throw new \Exception("Getting document failed: " . $err);

    $decoded = json_decode($response, true);
    $fileContent = $this->downloadDocument($decoded['document']['signed_pdf_url']);

    return $fileContent;
  }

  public function downloadDocument($downloadLink)
  {
    $options[CURLOPT_URL] = $downloadLink;
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_CUSTOMREQUEST] = "GET";
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err)
      throw new \Exception("Downloading document failed: " . $err);

    return $response;
  }

  private function createDeliveryAttachment($po, $requestId)
  {
    $attachment = new Attachment();
    $attachment->path = '/';
    $attachment->created_by = $po->created_by;
    $attachment->updated_by = $po->created_by;
    $attachment->description = 'Digital Signature - Signed Delivery Document';
    $attachment->attachable_type = 'purchaseorder';
    $attachment->attachable_id = $po->id;
    $attachment->status = 'pending_for_file';
    $attachment->attachmenttype_id = Attachmenttype::SIGNED_DELIVERY_DOCUMENT;
    $attachment->filename = "";
    $attachment->envelope = $requestId;
    $attachment->save();
  }

  private function createSecurityAttachment($security, $requestId)
  {
    $attachmentType = "";
    switch ($security->securitytype_id) {
      case 1:
        $attachmentType = Attachmenttype::PERSONAL_GURANTEE_DOCUMENT;
        break;

      case 2:
        $attachmentType = Attachmenttype::CORPORATE_GURANTEE_DOCUMENT;
        break;

      case 3:
        $attachmentType = Attachmenttype::PROMISSORY_NOTE_DOCUMENT;
        break;

      default:
        break;
    }

    $attachment = new Attachment();
    $attachment->path = '/';
    $attachment->created_by = 1;
    $attachment->updated_by = 1;
    $attachment->description = 'Digital Signature - Signed Security Document';
    $attachment->attachable_type = 'creditrequestsecurity';
    $attachment->attachable_id = $security->id;
    $attachment->status = 'pending_for_file';
    $attachment->attachmenttype_id = $attachmentType;
    $attachment->filename = "";
    $attachment->envelope = $requestId;
    $attachment->save();
  }
  
  private function createAttachment($po, $requestId)
	{
		$attachment = new Attachment();
		$attachment->path = '/';
		$attachment->created_by = $po->created_by;
		$attachment->updated_by = $po->created_by;
		$attachment->description = 'Digital Signature - Signed Delivery Document';
		$attachment->attachable_type = 'purchaseorder';
		$attachment->attachable_id = $po->id;
		$attachment->status = 'pending_for_file';
		$attachment->attachmenttype_id = Attachmenttype::SIGNED_DELIVERY_DOCUMENT;
		$attachment->filename = "";
		$attachment->envelope = $requestId;
		$attachment->save();
  }
  
  public function voidDocument($id)
  {
    // Get access token
    $accessToken = $this->getAccessToken();

    $options[CURLOPT_URL] = "https://api.rightsignature.com/public/v1/documents/$id/void";
    $options[CURLOPT_FRESH_CONNECT] = true;
    $options[CURLOPT_FAILONERROR] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $accessToken . ''];
    $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (!$response)
      throw new \Exception("Voiding document failed");

    return true;
  }
}