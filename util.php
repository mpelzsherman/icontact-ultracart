<?php

define('STATUS_CODE_SUCCESS', 200);

function callResource($url, $method, $data = null, $type='json', $bDieOnError=true)
{
	$url    = $GLOBALS['icontact-config']['apiUrl'] . $url;
	$handle = curl_init();
	
	$headers = array(
		'Accept: application/'.$type,
		'Content-Type: application/'.$type,
		'Api-Version: 2.2',
		'Api-AppId: ' . $GLOBALS['icontact-config']['appId'],
		'Api-Username: ' . $GLOBALS['icontact-config']['username'],
		'Api-Password: ' . $GLOBALS['icontact-config']['password'],
	);
	
	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	
	switch ($method) {
		case 'POST':
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
		break;
		case 'PUT': 
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		break;
		case 'DELETE':
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		break;
	}
	
	$rawresponse = curl_exec($handle);
    var_dump($rawresponse);

	$response = json_decode($rawresponse, true);
    print_r($response);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	
	curl_close($handle);
	
	if ($code != STATUS_CODE_SUCCESS) {
		$sDataDump = var_export($data, true);
		$sResponseDump = var_export($response, true);
		$sErrorMessage = "iContact API Error: {$code} for url={$url}, method={$method}, data={$sDataDump}. response={$sResponseDump}";
        if ($bDieOnError) {
			die($sErrorMessage);
		} else {
			echo $sErrorMessage . "\n";
		}
	}
	
	return array(
		'code' => $code,
		'data' => $response,
	);
}

function dump($array)
{
	echo "<pre>" . print_r($array, true) . "</pre>";
}

?>
