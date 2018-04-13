<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__  . '/../../php/ext/PayPal-PHP-SDK/autoload.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


$client_id = 'AaluvcbPK-Bs9cbxqY0fmmi7LD-TZ145oHP2HCN3Ajq9BjVN2hRSSJ2yetqG9QCGVmhW7hQw1NK0N56L'; 
$secret = 'EFwMYk0sHvOeitK2KzRMC9OgRqmFyg3-jdrXMetKiZTYxtqF6VM7bmVNlV42kingu133dl-C2j5ByLCF';

$apiContext = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    $client_id,
    $secret
  )
);

$apiContext->setConfig(
      array(
        'mode' => 'live'
      )
);

/*
$payer = new Payer();
$payer->setPaymentMethod("paypal");
*/

//$payment = $_REQUEST["payment"];

$payment = new Payment($_REQUEST["payment"]);
/*$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));*/
    
$request = clone $payment;

try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    exit(1);
}

$approvalUrl = $payment->getApprovalLink();

return $payment;


/*
//Obtener Token
$ch = curl_init();
curl_setopt($ch, CURLOPT_POSTFIELDS, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_URL, 'https://api.paypal.com/v1/oauth2/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $secret);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
    'Content-type: text/html; charset=utf-8'
));

$exec = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


$json = json_decode($exec);

print_r($json);

if (isset($json->refresh_token)){
	global $refreshToken;
	$refreshToken = $json->refresh_token;
}

$accessToken = $json->access_token;
$token_type = $json->token_type;
//print_r($json->access_token);
//print_r($json->refresh_token);
//print_r($json->token_type);



$url = 'https://www.paypal.com/cgi-bin/webscr';
$payment = $_REQUEST["payment"];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "".$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "".$payment);
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $accessToken,
    'Accept: application/json',
    'Content-type: text/html; charset=utf-8'
  //  'Content-Type: application/json'
));

$response = curl_exec($ch);


// URL decode
$response = urldecode($response);


// Fix character encoding if different from UTF-8 (in my case)
if(isset($response['charset']) AND strtoupper($response['charset']) !== 'UTF-8')
{
  foreach($response as $key => &$value)
  {
    $value = mb_convert_encoding($value, 'UTF-8', $response['charset']);
  }
  $response['charset_original'] = $response['charset'];
  $response['charset'] = 'UTF-8';
}

//$transaction = json_encode($response);

$transaction = $response;


//$transaction = mb_convert_encoding($value, "UTF-8", "auto");

print_r($transaction);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch); */

?>
