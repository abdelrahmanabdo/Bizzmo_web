<?php

$curl = curl_init();

$token = base64_encode('pv_71b67b73f86648f298ade3663453d57b');

curl_setopt_array($curl, array(
    CURLOPT_URL =>"https://api.rightsignature.com/public/v1/documents",
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
    echo $response;
	die;
	$json_a = json_decode($response, true);
	if(array_key_exists('user', $json_a)) {
		//echo $json_a['user']['name'];
	} else if (array_key_exists('error', $json_a)) {
		echo $json_a['error'];
	}
}

