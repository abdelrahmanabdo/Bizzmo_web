<?php

$token = base64_encode('pv_71b67b73f86648f298ade3663453d57b');

$arr = array(

    'file' =>
        array(
        'name' => 'visa.pdf',
        'source' => 'upload',
        ),
    'document' => array(
        'signer_sequencing' => false,
        'expires_in' => '12',
        'name' => 'Bizzmo',
        'roles' => array(
            array('name' => 'a',
            'signer_email' => 'sherifan@gmail.com',
            'signer_name' => 'Sherif',
            )),
        ), 
        'sending_request' =>array(),
    );

	$arr = array('id' => '03ecf43e-8adb-4271-8928-e76527274b5b',);
	
    $post_json = json_encode($arr);
    $endpoint = "https://api.rightsignature.com/public/v1/sending_requests/03ecf43e-8adb-4271-8928-e76527274b5b/uploaded";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $post_json);       
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Basic ' . $token, 'cache-control: no-cache' ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
	echo $response;