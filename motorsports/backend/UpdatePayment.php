<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


require __DIR__ . '/bootstrap.php';

use PayPal\Api\Payment;



$paymentId = $_REQUEST["paymentId"];
$amount = json_decode($_REQUEST["amount"]);

try {

    $payment = Payment::get($paymentId, $apiContext);

} catch (Exception $ex) {
    print_r("Get Payment: ".$ex);
    exit(1);
}


$patchReplace = new \PayPal\Api\Patch();
$patchReplace->setOp('replace')
    ->setPath('/transactions/0/amount')
    ->setValue($amount);

$patchRequest = new \PayPal\Api\PatchRequest();
$patchRequest->setPatches(array($patchReplace));

try {

    $result = $payment->update($patchRequest, $apiContext);

    ///$payment->execute($execution, $apiContext);

} catch (Exception $ex) {
    print_r("Update payment. Exception: ".$ex." PatchRequest: ".$patchRequest);
    exit(1);
}


if ($result == true) {

    $result = Payment::get($payment->getId(), $apiContext);

     //print_r("Get payment: ".$result->getId()." Result: ".$result);


foreach ($result->getLinks() as $link) {
    if ($link->getRel() == 'approval_url') {
        $approvalUrl = $link->getHref();
        break;
    }
}

}

print_r($result);

return $result;