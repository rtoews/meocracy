<?php

require 'includes/smsified.class.php';

$message=$_REQUEST["message"]; 
$address = $_REQUEST["address"];

$username = "meocracy";
$password = "Badlsd22";
$senderAddress = "19493909951";

try { 

$sms = new SMSified($username, $password);

$response = $sms->sendMessage($senderAddress, $address, $message);
$responseJson = json_decode($response);
var_dump($response); 
}
catch (SMSifiedException $ex) {
echo $ex->getMessage();
}

// Checks response

try {

    $response = $sms->getMessages("047bb537cca6f278c2d59729fd7w1456");
    $responseJson = json_decode($response);
    var_dump($response);
}

catch (SMSifiedException $ex) {
    echo $ex->getMessage();
}

echo "$message : $address <br/>";


?>


