<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/payzippy.php');

$payzippy = new payzippy();
$request_params=$_REQUEST;
unset($_REQUEST);

$hash_received = $request_params['hash'];
$hash_string = '';
unset($request_params['hash']);          
ksort($request_params);

foreach($request_params as $key => $value) {
  $hash_string = $hash_string . $value . "|";
}
Logger::addLog($hash_string,1);
$hash_string = $hash_string . Configuration::get('SECRET_KEY');
$hash_calculated = hash('SHA256',$hash_string);

$total=$request_params['transaction_amount']/100;
$cart_id = explode("||",$request_params['merchant_transaction_id']);
$cart->id = $cart_id[0];

$extra_vars['transaction_id'] = $request_params['payzippy_transaction_id'];;
if($request_params['transaction_response_code']=='SUCCESS') {
  if($hash_calculated == $hash_received) {
    $payzippy->validateOrder($cart->id,  _PS_OS_PAYMENT_, $total, $payzippy->displayName, "Payment Message: ".$request_params['transaction_response_message']."\nPayment Method: ".$request_params['payment_instrument']."\n", $extra_vars, NULL, false, false, NULL);
  //To get order_id so that we can pass it in argument and send it to order.php...
    $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'orders WHERE id_cart = '.(int)$cart->id );

    //$sql1 = 'UPDATE '._DB_PREFIX_.'order_payment SET `transaction_id` = \''.$request_params['payzippy_transaction_id'].'\' WHERE `order_reference` = \''.$result['reference'].'\'';
    //Db::getInstance()->ExecuteS($sql1);
    Tools::redirectLink(__PS_BASE_URI__.'order-detail.php?id_order='.$result['id_order']);

  } else {
    //log hash mismatch
    Logger::addLog("Hash mismatch",4);
    $payzippy->validateOrder($cart->id,  _PS_OS_ERROR_, $total, $payzippy->displayName, $request_params['transaction_response_message'], $extra_vars, NULL, false, FALSE, NULL);
    $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'orders WHERE id_cart = '.(int)$cart->id );
    Tools::redirectLink(__PS_BASE_URI__.'order-detail.php?id_order='.$result['id_order']);
  }
} else if($request_params['transaction_response_code'] != 'SUCCESS') {
    $payzippy->validateOrder($cart->id,  _PS_OS_ERROR_, $total, $payzippy->displayName, "Payment Message: ".$request_params['transaction_response_message']."\nPayment Method: ".$request_params['payment_instrument']."\n", $extra_vars, NULL, FALSE, FALSE, NULL);
    $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'orders WHERE id_cart = '.(int)$cart->id );
    Tools::redirectLink(__PS_BASE_URI__.'order-detail.php?id_order='.$result['id_order']);
  }
    
?>