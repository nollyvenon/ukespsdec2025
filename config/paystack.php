<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paystack Keys
    |--------------------------------------------------------------------------
    |
    | The public and secret keys for your Paystack account.
    | You can find these in your Paystack dashboard.
    |
    */
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    
    /*
    |--------------------------------------------------------------------------
    | Merchant Information
    |--------------------------------------------------------------------------
    */
    'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    */
    'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('PAYSTACK_CURRENCY', 'NGN'),
];