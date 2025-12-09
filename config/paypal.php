<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal Configuration
    |--------------------------------------------------------------------------
    |
    | The client ID and secret for your PayPal account.
    | You can find these in your PayPal developer dashboard.
    |
    */
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
    
    /*
    |--------------------------------------------------------------------------
    | PayPal API URLs
    |--------------------------------------------------------------------------
    */
    'sandbox_url' => 'https://api.sandbox.paypal.com',
    'live_url' => 'https://api.paypal.com',
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    */
    'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    
    /*
    |--------------------------------------------------------------------------
    | Other Configuration
    |--------------------------------------------------------------------------
    */
    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'sale'), // capture or sale
];