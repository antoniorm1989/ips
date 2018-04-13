<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/bootstrap.php';
require __DIR__  . '/../../php/ext/PayPal-PHP-SDK/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

if (isset($_GET['success']) && $_GET['success'] == 'true') {

    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $apiContext);
	
    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    try {
    
    	$result = $payment->execute($execution, $apiContext);
    	
    	ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
        
        ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            exit(1);
        }
    } catch (Exception $ex) {
    
    ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        exit(1);
    }
    
    ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);

    return $payment;
    
  } else {
      ResultPrinter::printResult("User Cancelled the Approval", null);
      exit;
  }
  

?>