<?php

$curl = curl_init();

$token = base64_encode('pv_71b67b73f86648f298ade3663453d57b');

curl_setopt_array($curl, array(
    CURLOPT_URL =>"https://api.rightsignature.com/public/v1/me",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING =>"",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST =>"GET",
	CURLOPT_HTTPHEADER => array(
		"authorization: Basic " . $token,
		"cache-control: no-cache",
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    //echo $response;
	$json_a = json_decode($response, true);
	if(array_key_exists('user', $json_a)) {
		//echo $json_a['user']['name'];
	} else if (array_key_exists('error', $json_a)) {
		echo $json_a['error'];
	}
}



$arr = array(
    'file' =>
        array(
        'name' => 'ppt.pdf',
        'source' => 'upload',
        ),
    'document' => array(
        'signer_sequencing' => false,
		//'identity_method' => 'none',
		//'api_embedded' => true,
        'expires_in' => '12',
        'name' => 'Delivery note',
		'callback_url' => 'https://bizzmo.com/sign',
        'roles' => array(
            array('name' => 'a',
            'signer_email' => 'sherifan@gmail.com',
            'signer_name' => 'Sherif',
            )),
        ), 
        'sending_request' =>array(),
);

$post_json = json_encode($arr);
$endpoint = "https://api.rightsignature.com/public/v1/sending_requests?access_token=" . $token;

$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,  $post_json);       
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Basic ' . $token, 'cache-control: no-cache' ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
//echo $response;
$json_a = json_decode($response, true);
if(array_key_exists('sending_request', $json_a)) {
	//echo $json_a['sending_request']['upload_url'];
	$id = $json_a['sending_request']['id'];
	echo $id, PHP_EOL;
	$url = $json_a['sending_request']['upload_url'];	
	//echo $url, PHP_EOL;
} else if (array_key_exists('error', $json_a)) {
	echo $json_a['error'];
}

curl_close($ch); 	

//echo PHP_EOL;
	
$file_path = 'upload/ppt.pdf';
//$url = 'https://rightsignature-sr-production.s3.amazonaws.com/public_api/sending_requests/03ecf43e-8adb-4271-8928-e76527274b5b/ppt.pdf?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIAJPXHLRKDG5JOKUMQ%2F20180412%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20180412T123744Z&X-Amz-Expires=3600&X-Amz-Security-Token=FQoDYXdzECoaDCInIANPXXCiPepXFiK3Aw0iTMlNG5V%2FYUeXwRYj0RQQMu0AUODLURGEdt%2BF5yqjE8iDgS%2B7ldd0zP4DugQR8ad3On1AKwpysu1KPYlPuezpCDuHX1gtoaSZVzTuMarhrLh9sBv2KIDOrZNgzFr5RNDXbrCElMRpwWmvbGnGggsAEmm1mcK62hWzf21H0Q92XwuyD8NYVXT6QwRCaaJIxyeV%2FlElxfT1VwCWM%2BoFlzrGByVKJio1ezYu5rwE%2FvUEL3QL0hWbCARmPT3t7pe622AD71SXsKAGWc7%2B63vRVb6VexdEDofOcaAehw3GMgHXyfp%2BSftiq9rjgHUN9GqmIuz35%2F1TxUii0O0vzCRkLSj8VM1xLUFou6sdxQGMCPIMZUIC65HhbrX1UEPyPHzgl%2FKS9Mtt1NZs376ZPeey7Gkq0djZN4BcuWaOsjNLMNRTxo30PkjlFQ8lXXp9YN7XmnSOh6XHb%2FmMajnreki5R0YFWo5dsguwax1o8LfLHYOTx9kjPqwthhof3TqMQaf9vsHoviu0iJQDmW%2F884cPmBDGBQ0EXY8ATldVgzRRcK0kUYwSLrOs1htiRa3WAFxBRS3ygIpogiso77y81gU%3D&X-Amz-Signature=d5545c058b7d6f24539fcdb1af217c2d455033cd78d1de8cdc94892e9f05f98a&X-Amz-SignedHeaders=Host&x-amz-acl=private';
$image = fopen($file_path, "rb");

$curl = curl_init();
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_PUT, 1);
curl_setopt($curl, CURLOPT_INFILE, $image);
curl_setopt($curl, CURLOPT_INFILESIZE, filesize($file_path));

$result = curl_exec($curl);

$err = curl_error($curl);


curl_close($curl); 
//echo $result;
//echo $err;


$arr = array('id' => $id,);

$post_json = json_encode($arr);
$endpoint = "https://api.rightsignature.com/public/v1/sending_requests/" . $id . "/uploaded";

$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,  $post_json);       
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Basic ' . $token, 'cache-control: no-cache' ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo $response;

//echo 'Response:', PHP_EOL, $response, PHP_EOL, PHP_EOL;
die;
//Document info
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL =>"https://api.rightsignature.com/public/v1/documents/" . $id,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING =>"",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST =>"GET",
	CURLOPT_HTTPHEADER => array(
		"authorization: Basic " . $token,
		"cache-control: no-cache",
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
echo $response;

