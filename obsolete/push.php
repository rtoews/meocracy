<?php
define('APPKEY','RoI5yC2cRz20xrTe-DknoQ'); 
define('PUSHSECRET', 'KmCRxzR5RAye6-vqlbBhxg'); // Master Secret
define('PUSHURL', 'https://go.urbanairship.com/api/push/broadcast/'); 

$contents = array(); 
$contents['badge'] = "+4"; 
$contents['alert'] = "All mimsy were the borogoves, and the mome raths outgrabe."; 
$contents['sound'] = "cow"; 
$push = array("aps" => $contents); 

$json = json_encode($push); 

$session = curl_init(PUSHURL); 
curl_setopt($session, CURLOPT_USERPWD, APPKEY . ':' . PUSHSECRET); 
curl_setopt($session, CURLOPT_POST, True); 
curl_setopt($session, CURLOPT_POSTFIELDS, $json); 
curl_setopt($session, CURLOPT_HEADER, False); 
curl_setopt($session, CURLOPT_RETURNTRANSFER, True); 
curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); 
$content = curl_exec($session); 
echo $content; // just for testing what was sent

// Check if any error occured 
$response = curl_getinfo($session); 
if($response['http_code'] != 200) { 
    echo "Got negative response from server, http code: ". 
    $response['http_code'] . "\n"; 
} else { 

    echo "Wow, it worked!\n"; 
} 

curl_close($session);
?>
