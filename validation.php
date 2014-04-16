<?php

require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/payzippy.php');
require_once(dirname(__FILE__) . '/bank_names.php');
require_once(dirname(__FILE__) . '/config.php');

$payzippy = new payzippy();
$myconfig = new my_config();

$request_params = $_POST;
unset($_POST);

$hash_received = $request_params['hash'];
$hash_string = '';
unset($request_params['hash']);
ksort($request_params);

foreach ($request_params as $key => $value)
    $hash_string = $hash_string . $value . '|';

Logger::addLog($hash_string, 1);
$hash_string = $hash_string . Configuration::get('SECRET_KEY');
$hash_calculated = hash($myconfig::HASH_METHOD, $hash_string);

$total = $request_params['transaction_amount'] / 100;
$cart_id = explode('||', $request_params['merchant_transaction_id']);

$pzid = $request_params['payzippy_transaction_id'];
$merchant_transaction_id = $request_params['merchant_transaction_id'];
$transaction_response_message = $request_params['transaction_response_message'];
$payment_method = $request_params['payment_instrument'];
$constant = new BankConstants();
$bank_name = $constant->bank_full_name($request_params['bank_name']);
$fraud_action = $request_params['fraud_action'];
$fraud_details = $request_params['fraud_details'];
$transaction_status = $request_params['transaction_status'];
$udf1 = $request_params['udf1'];

$extra_vars['transaction_id'] = $pzid;

if ($request_params['transaction_response_code'] == 'SUCCESS') {
    if ($hash_calculated == $hash_received) {
        $payzippy->validateOrder($cart_id[0], _PS_OS_PAYMENT_, $total, $payzippy->displayName, "Payment Successful\nPayZippy Transaction ID: " . $pzid . "\nMerchant Transaction ID: " . $merchant_transaction_id . "\nTransaction status: " . $transaction_status . "\nPayment Message: " . $transaction_response_message . "\nPayment Method: " . $payment_method . "\nBank Name: " . $bank_name . "\nFraud Action: " . $fraud_action . "\nFraud Details: " . $fraud_details . "\n", $extra_vars, null, false, $udf1, null);
//To get order_id so that we can pass it in argument and send it to order.php
    } else {
//log hash mismatch
        Logger::addLog('Hash mismatch', 4);
        $payzippy->validateOrder($cart_id[0], _PS_OS_ERROR_, $total, $payzippy->displayName, "Hash Mismatch\nPayZippy Transaction ID: " . $pzid . "\nMerchant Transaction ID: " . $merchant_transaction_id . "\nTransaction status: " . $transaction_status . "\nPayment Message: " . $transaction_response_message . "\nPayment Method: " . $payment_method . "\nBank Name: " . $bank_name . "\nFraud Action: " . $fraud_action . "\nFraud Details: " . $fraud_details . "\n", $extra_vars, null, false, $udf1, null);
    }
} else if ($request_params['transaction_response_code'] != 'SUCCESS')
    $payzippy->validateOrder($cart_id[0], _PS_OS_ERROR_, $total, $payzippy->displayName, "Payment Failed\nPayZippy Transaction ID: " . $pzid . "\nMerchant Transaction ID: " . $merchant_transaction_id . "\nTransaction status: " . $transaction_status . "\nPayment Message: " . $transaction_response_message . "\nPayment Method: " . $payment_method . "\nBank Name: " . $bank_name . "\nFraud Action: " . $fraud_action . "\nFraud Details: " . $fraud_details . "\n", $extra_vars, null, false, $udf1, null);


$result = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'orders WHERE id_cart = ' . (int) $cart_id[0]);

Tools::redirectLink(__PS_BASE_URI__ . 'order-detail.php?id_order=' . $result['id_order']);
?>
