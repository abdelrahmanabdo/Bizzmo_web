<?php  
// REQUEST: Get Access Token
$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_URL, 'https://api.na2.echosign.com/oauth/refresh');
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
// TODO: Change getenv function to laravel function env
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'refresh_token',
    'client_id' => 'CBJCHBCAABAAhxV_EORZQT6cInAKkkjjgwmJ0VixZ9mC',
    'client_secret' => '2uXDnj4twhZcoVvavcrISkhUEqwXbXuo',
    'refresh_token' => '3AAABLblqZhAJAEJQha_HZcSudSMfWLgaB5EuCJhgQeRlDeYQ6Az71fRjjvTo6o-z5lbmT79gVCU*'
]));

$at_response = curl_exec($curl);
curl_close($curl);

$at_decoded = json_decode($at_response, true);
$access_token = $at_decoded ['access_token'];

echo $access_token . "\r\n";
die;
// REQUEST: Make Transient Document
// $url = 'https://api.na2.echosign.com/api/rest/v5/transientDocuments';

// $file = realpath('sample.pdf');
// $cFile = new CURLFile($file, 'application/pdf', 'sample');

// // send the request now
// $curl = curl_init($url);
// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
// curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization :Bearer ' . $access_token . '', 'Content-Type: multipart/form-data']);
// curl_setopt($curl, CURLOPT_POSTFIELDS, ['File' => $cFile]);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// $td_response = curl_exec($curl);
// curl_close($curl);

// $td_decoded = json_decode($td_response, true);
// $transient_doc_id = $td_decoded['transientDocumentId'];
// echo $transient_doc_id;

// REQUEST: Create Agreement 
$url = 'https://api.na2.echosign.com/api/rest/v5/agreements';
$tmp = '3AAABLblqZhBX-tkfBLSDlSzWA99xQBAPXy4FdNceuW1oCwi6Awr4IRvU3SU3OXHlsqaotIomnx7bYQK8JtKP8wwyji8MVMLUiWXZgHqnIfg_bEOvR7bCmMHa4MADULw7D6uB_8171HSKcOjFlof73yQhiCRo43lMe69zGMH44MN4QjK1yF4TQAbDROc_W3hojonjnd6NEqrsvmsg1P-Wltult5slZivpIiOaBvDgcYWSwg8U4C4nJxr3P5-AZYRqXpRzOEwt5l7_AB5iwTWfN3yuYTpvHb_TzP_818pGhXouEcb5MnmKP8Hm9GDbJn0CC65xTHDtRmc*';
$data = [
    "documentCreationInfo" => [
        "fileInfos" => [
            [
                "transientDocumentId" => $tmp
            ]
        ],
        "name" => "MyTestAgreement",
        "recipientSetInfos" => 
            [
                [
                    "recipientSetMemberInfos" => [
                        [
                            "email" => "radwa.kamal@nextechnology.me",
                            "fax" => ""
                        ]
                    ],
                        "recipientSetRole" => "SIGNER"
                ],
                [
                "recipientSetMemberInfos" => [
                    [
                        "email" => "radwa.m.kamal@gmail.com",
                        "fax" => ""
                    ]
                ],
                    "recipientSetRole" => "SIGNER"
            ]
            ],
        "formFields" => [
            "name" =>  "echosign_signature1",
            "inputType" =>  "SIGNATURE",
            "contentType" =>  "SIGNATURE",
            "fontSize" =>  "12",
            "required" =>  "true",
            "readOnly" =>  "false",
            "defaultValue" =>  "Sign here",
            "borderColor" => "#004cff",
            "borderStyle" => "SOLID",
            "locations" =>  [[
                "height" =>  "18",
                "left" =>  "90",
                "pageNumber" =>  "1",
                "top"  => "90",
                "width" => "90",
            ],[
                "height" =>  "20",
                "left" =>  "120",
                "pageNumber" =>  "2",
                "top"  => "120",
                "width" =>  "200",
            ]
            ]
        ],
        "signatureType" => "ESIGN",      
        "signatureFlow" => "SEQUENTIAL"
    ],
];
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization :Bearer ' . $access_token . '', 'Content-Type: application/json']);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_VERBOSE, true);
curl_setopt($curl,CURLINFO_HEADER_OUT,true);

$ca_response = curl_exec($curl);
curl_close($curl);
print_r($ca_response);


// REQUEST: Get Status
// $tmp_ag_id = '3AAABLblqZhB-azVLBF6phTRAUJdMxdw2UmAS9TFm56ddSKCmwrX3W0WV-gIuPMcIksCe31Js3Hzhf8jKU7BsqRR6Z8JuIG8Z';
// $url = 'https://api.na2.echosign.com/api/rest/v5/agreements/' . $tmp_ag_id;
// $curl = curl_init($url);
// curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
// curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization :Bearer ' . $access_token . '', 'Content-Type: application/json']);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// $as_response = curl_exec($curl);
// curl_close($curl);
// print_r($as_response);
