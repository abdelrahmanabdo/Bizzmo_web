<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Company;
use App\Attachment;
use App\Attachmenttype;
use Auth;

class Processcreatecompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $company;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $at_options[CURLOPT_URL] = 'https://api.na2.echosign.com/oauth/refresh';
        $at_options[CURLOPT_FRESH_CONNECT] = true;
        $at_options[CURLOPT_FAILONERROR] = true;
        $at_options[CURLOPT_RETURNTRANSFER] = true; 
        $at_options[CURLOPT_POST] = true; 
        $at_options[CURLOPT_HTTPHEADER] = ['Content-Type: application/x-www-form-urlencoded']; 
        $at_options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1; 
        $at_options[CURLOPT_POSTFIELDS] = http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => env('ADOBE_CLIENT_ID'),
            'client_secret' => env('ADOBE_CLIENT_SECRET'),
            'refresh_token' => env('ADOBE_REFRESH_TOKEN')
        ]);
        
        $curl = curl_init();
        curl_setopt_array($curl, $at_options);

        $at_response = curl_exec($curl);
        curl_close($curl);
        if (!$at_response)
           throw new \Exception("Getting access token failed");
        
        $at_decoded = json_decode($at_response, true);
        $access_token = $at_decoded ['access_token'];
        
        // REQUEST: Create Agreement 
        $agreement_name = "MyNewTestAgreement";
        $data = [
            "documentCreationInfo" => [
                "fileInfos" => [
                    [
                        "libraryDocumentId" => env('ADOBE_LIB_DOC_ID')
                    ]
                ],
                "name" => $agreement_name,
                "recipientSetInfos" => 
                    [
                        [
                            "recipientSetMemberInfos" => [
                                [
                                    "email" => $this->company->email,
                                    "fax" => ""
                                ]
                            ],
                                "recipientSetRole" => "SIGNER"
                        ],
                    ],
                "signatureType" => "ESIGN",  
                "signatureFlow" => "SEQUENTIAL"
            ],
        ];

        $ca_options[CURLOPT_URL] = 'https://api.na2.echosign.com/api/rest/v5/agreements';
        $ca_options[CURLOPT_FRESH_CONNECT] = true;
        $ca_options[CURLOPT_FAILONERROR] = true;
        $ca_options[CURLOPT_RETURNTRANSFER] = true;
        $ca_options[CURLOPT_POST] = true;
        $ca_options[CURLOPT_HTTPHEADER] = ['Authorization :Bearer ' . $access_token . '', 'Content-Type: application/json']; 
        $ca_options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $ca_options[CURLOPT_POSTFIELDS] = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, $ca_options);
        $ca_response = curl_exec($curl);
        curl_close($curl);

        if (!$ca_response)
           throw new \Exception("Creating agreement failed");

        $ca_decoded = json_decode($ca_response, true);
        $agreement_id = $ca_decoded ['agreementId'];

        $attachment  = new Attachment;
        $attachment->path = '/';
        $attachment->created_by = $this->company->created_by;
        $attachment->updated_by = $this->company->created_by;
        $attachment->description = 'Digital Signature';
        $attachment->attachable_type = 'company';			
        $attachment->attachable_id = $this->company->id;
        $attachment->attachmenttype_id = Attachmenttype::DIGITAL_SIGNATURE;
        $attachment->document = $this->company->companyname . '_' . $this->company->id . '.pdf';
        $attachment->envelope = $agreement_id;
        $attachment->save();
    }
}
