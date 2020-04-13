<?php

$file_path = 'upload/ppt.pdf';
$url = 'https://rightsignature-sr-production.s3.amazonaws.com/public_api/sending_requests/648724d8-76d9-4139-a9dd-82b4e42680a7/ppt.pdf?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIAJ7BFI2GIOZ5K275Q%2F20180412%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20180412T145249Z&X-Amz-Expires=3600&X-Amz-Security-Token=FQoDYXdzEC4aDMoPxf5JHWxRZIEpFyK3A0c2Ba8VmcbjEMWUttpBCHpPontJlB6vLqC6BX%2FI7ZgiRY0Vbf8lnV5%2FcuFX8nc6zLYRUa7TSBzzFiQySFysuHk3iCsQtZ80eLuPpfLvklQMgR%2BnMe31LxPMKatQGpGavvgtU2GTkg7nps1YxmJzpsRl3GN145gV98CvyftMXCqnY80xQQU1llyU%2F8E%2FoAHGRDtwL91EIF%2BTkamPJUtKHG8nRrjwBBfh3Z%2BBensa4zGd1BDDQLQ8bijd6Or2rADIzANoCbdVMEttHLqL9Q31jPNxFE2%2FVF4cHlCE4sCD1fXnIZ3SwNQQd6qT3f%2BvfqSjLjgB1rV11EqtOt6VhWa8I0Lb7Bjl%2F%2FeqTJa3ATFykgSv1H8ZOO4LY4KUSkmZwCHBa3%2BKciwBSxgRWAhHLCiSBdgCzf3TEw6VRBmdVNVW7tRfbL6hIGdg0m8VYNiOQMrYzf%2BcBomRQMcFMATHMIU%2BuM26cB4yf2OGa7ppyHfatBwb7VlfxT8MQHe7Nda%2FRXjWLgtzznihTyjHwcj%2BUbtpjywVNrsQWMLxAvEez3gZBCQvKpyo%2Bmxenvx8JE9qzPYBiInQ1EOkUB0ola%2B91gU%3D&X-Amz-Signature=7d5a57806bf3bc7822367ecdf22dca1b28215338463d2a426d4f8f2556731acd&X-Amz-SignedHeaders=Host&x-amz-acl=private';


$image = fopen($file_path, "rb");

$curl = curl_init();
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_PUT, true);
curl_setopt($curl, CURLOPT_INFILE, $image);
curl_setopt($curl, CURLOPT_INFILESIZE, filesize($file_path));

$result = curl_exec($curl);

$err = curl_error($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $result;	
}

curl_close($curl); 
echo $result;
echo 'aa';