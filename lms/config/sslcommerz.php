<?php

return [
    'store_id' => env('SSLCOMMERZ_STORE_ID', 'ariya68681c12bd732'),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD', 'ariya68681c12bd732@ssl'),
    'sandbox_mode' => env('SSLCOMMERZ_SANDBOX_MODE', true),
    'success_url' => env('SSLCOMMERZ_SUCCESS_URL', '/payment/success'),
    'fail_url' => env('SSLCOMMERZ_FAIL_URL', '/payment/fail'),
    'cancel_url' => env('SSLCOMMERZ_CANCEL_URL', '/payment/cancel'),
    'ipn_url' => env('SSLCOMMERZ_IPN_URL', '/payment/ipn'),
];