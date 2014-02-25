<?php

class my_config
{
const MERCHANT_ID = 'test_flipkart';
const TRANSACTION_TYPE = 'SALE';
const CURRENCY = 'INR';
const UI_MODE = 'REDIRECT';
const HASH_METHOD = 'MD5';
const MERCHANT_KEY_ID = 'payment';
const CALLBACK_URL = 'http://localhost/php-sdk/examples/response/charging_response.php';

const API_BASE = 'http://www.payzippy.com/payment/api/charging/v1';
const API_CHARGING = 'charging';
const API_QUERY = 'query';
const API_REFUND = 'refund';
const CURRENT_VERSION = '1.0';
const VERIFY_SSL_CERTS = true;
}
?>