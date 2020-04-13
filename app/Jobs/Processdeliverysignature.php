<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use DB;

class Processdeliverysignature implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $envelope;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
   public function __construct($envelope)
    {
        $this->envelope = $envelope;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        		
		// Input your info here:
		$email = "sherif@egynile.com";			// your account email
		$password = "26192619";		// your account password
		$integratorKey = "726b5548-94eb-4cdc-817e-0adb38fadb8d";		// your account integrator key, found on (Preferences -> API page)
		// copy the envelopeId from an existing envelope in your account that you want
		// to download documents from
		$envelopeId = $this->envelope;
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
		//echo "Envelope has following document(s) information...\n";
		//print_r($response);	echo "\n";
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
			file_put_contents("storage/app/envelopes/" . $envelopeId . "-" . $document["documentId"] . "-". $document["name"], $data);
			curl_close($curl);
			if (substr( $document["name"], -3, 3) == 'pdf') {
				DB::table('attachments')
				->where('envelope', $envelopeId)
				->update(['document' => $envelopeId . "-" . $document["documentId"] . "-" . $document["name"]]);
			}
		}
		//--- display results
		echo "Envelope document(s) have been downloaded, check your local directory.\n";

    }
}
