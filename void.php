<?php

$token = base64_encode('pv_71b67b73f86648f298ade3663453d57b');


$arr = array('id' => '"a19c35e0-1118-41fe-8c70-09e2e371d340"',);

$post_json = json_encode($arr);
$endpoint = "https://api.rightsignature.com/public/v1/documents/a19c35e0-1118-41fe-8c70-09e2e371d340/void";

$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,  $post_json);       
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Basic ' . $token, 'cache-control: no-cache' ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo $response;