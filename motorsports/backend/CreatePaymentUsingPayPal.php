<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// # Create Payment using PayPal as payment method
// This sample code demonstrates how you can process a 
// PayPal Account based Payment.
// API used: /v1/payments/payment

require __DIR__ . '/bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


$paymentJson = json_decode($_REQUEST["payment"]);

$payment = new Payment();

$payment->setIntent($paymentJson ->intent)
    ->setPayer($paymentJson ->payer)
    ->setRedirectUrls($paymentJson ->redirect_urls)
    ->setTransactions($paymentJson ->transactions);

// For Sample Purposes Only.
//$request = clone $payment;

// ### Create Payment
// Create a payment by calling the 'create' method
// passing it a valid apiContext.
// (See bootstrap.php for more on `ApiContext`)
// The return object contains the state and the
// url to which the buyer must be redirected to
// for payment approval
try {
    
    $payment->create($apiContext);

} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    print_r($ex);
    exit(1);
}

// ### Get redirect url
// The API response provides the url that you must redirect
// the buyer to. Retrieve the url from the $payment->getApprovalLink()
// method
$approvalUrl = $payment->getApprovalLink();

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
// ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);
 
print_r($payment->id);
 
return $payment;