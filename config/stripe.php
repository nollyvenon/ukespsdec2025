<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | The secret and publishable keys for your Stripe account.
    | You can find these in your Stripe dashboard.
    |
    */
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    
    /*
    |--------------------------------------------------------------------------
    | Stripe API Configuration
    |--------------------------------------------------------------------------
    */
    'api_version' => env('STRIPE_API_VERSION', '2023-10-16'),
    'payment_url' => env('STRIPE_PAYMENT_URL', 'https://api.stripe.com'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('STRIPE_CURRENCY', 'usd'),
    
    /*
    |--------------------------------------------------------------------------
    | Payment Intent Configuration
    |--------------------------------------------------------------------------
    */
    'automatic_payment_methods' => [
        'enabled' => true,
        'allow_redirects' => true,
    ],
];