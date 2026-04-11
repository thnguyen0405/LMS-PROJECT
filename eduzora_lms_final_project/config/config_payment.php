<?php
use Omnipay\Omnipay;

define('CLIENT_ID', 'your_paypal_client_id');
define('CLIENT_SECRET', 'your_paypal_client_secret');

define('PAYPAL_RETURN_URL', BASE_URL.'paypal-success.php');
define('PAYPAL_CANCEL_URL', BASE_URL.'payment-cancel.php');
define('PAYPAL_CURRENCY', 'USD');

$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true); //set it to 'false' when go live

define('STRIPE_TEST_PK', 'your_stripe_test_publishable_key');
define('STRIPE_TEST_SK', 'your_stripe_test_secret_key');

define('STRIPE_SUCCESS_URL', BASE_URL.'stripe-success.php');
define('STRIPE_CANCEL_URL', BASE_URL.'payment-cancel.php');