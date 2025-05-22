<?php
define('PAYPAL_CLIENT_ID', getenv('PAYPAL_CLIENT_ID')); // Set in environment
define('PAYPAL_APP_SECRET', getenv('PAYPAL_APP_SECRET')); // Set in environment
define('PAYPAL_API_URL', getenv('PAYPAL_API_URL') ?: 'https://api-m.sandbox.paypal.com');
define('PAYPAL_CURRENCY', getenv('PAYPAL_CURRENCY') ?: 'USD');
define('INR_TO_USD_RATE', getenv('INR_TO_USD_RATE') ?: 0.012);
?>
