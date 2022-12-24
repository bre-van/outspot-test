<?php
session_start();
require_once __DIR__ . "/include/config.php";
require_once __DIR__ . "/include/functions.php";
require_once __DIR__ . "/vendor/autoload.php";

use Mollie\Api\MollieApiClient;

$errors = array();
unset($_SESSION['errors']);

$total_amount = number_format((float)$_POST['payment'], 2, '.', '');

if (!(is_numeric($total_amount) && $total_amount >= 10 && $total_amount <= 100)) {
    $errors[] = 'Amount is not correct';
}

if (count($errors) == 0) {
    try {
        $mollie = new MollieApiClient();

        $mollie->setApiKey(MOLLIE_API_KEY);
        $payment_hash = getPaymentHash();

        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => $total_amount
            ],
            "description" => "Payment Outspot test",
            "redirectUrl" => getPaymentUrl($payment_hash)
            //"webhookUrl" => getPaymentUrl($payment_hash): this doesn't work in a localhost environment
        ]);

        $_SESSION['payment_id'] =  $payment->id;
        $_SESSION['payment_hash'] = $payment_hash;

        header("Location: " . $payment->getCheckoutUrl(), true, 303);
    } catch (\Mollie\Api\Exceptions\ApiException $e) {
        /* Mollie API errors, wouldn't display these on screen normally */
        $errors[] = 'Mollie exception: ' . $e;
    }
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
}