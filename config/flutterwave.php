<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flutterwave Keys
    |--------------------------------------------------------------------------
    |
    | The public and secret keys for your Flutterwave account.
    | You can find these in your Flutterwave dashboard.
    |
    */
    'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
    'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
    'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
    'payment_url' => env('FLUTTERWAVE_PAYMENT_URL', 'https://api.flutterwave.com'),
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    */
    'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('FLUTTERWAVE_CURRENCY', 'NGN'),
];