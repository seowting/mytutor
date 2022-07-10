<?php

$email = $_GET['email'];
$phoneno = $_GET['phoneno']; 
$name = $_GET['name']; 
$amount = $_GET['amount']; 


$api_key = '95646a5e-a3b3-406f-b24b-f3f5ac1ddbed';
$collection_id = 'c8w8gk0f';
 
$host = 'https://www.billplz-sandbox.com/api/v3/bills';


$data = array(
          'collection_id' => $collection_id,
          'email' => $email,
          'phoneno' => $phoneno,
          'name' => $name,
          'amount' => ($amount + 1) * 100,
		  'description' => 'Payment for subject subscribe by '.$name,
          'callback_url' => "http://seowting.com/mytutor/mobile/php/return_url",
          'redirect_url' => "http://seowting.com/mytutor/mobile/php/payment_status.php?email=$email&phoneno=$phoneno&amount=$amount&name=$name" 
);


$process = curl_init($host );
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_USERPWD, $api_key . ":");
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data) ); 

$return = curl_exec($process);
curl_close($process);

$bill = json_decode($return, true);
header("Location: {$bill['url']}");
?>