<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sslcommerz' => [
    'store_id'       => env('SSLC_STORE_ID'),
    'store_password' => env('SSLC_STORE_PASSWORD'),
    'mode'           => env('SSLC_MODE', 'sandbox'),
    'sandbox_url'    => env('SSLC_SANDBOX_URL'),
    'live_url'       => env('SSLC_LIVE_URL'),
],
'aamarpay' => [
    'store_id'      => env('AAMARPAY_STORE_ID'),
    'signature_key' => env('AAMARPAY_SIGNATURE_KEY'),
    'mode'          => env('AAMARPAY_MODE', 'sandbox'),
    'sandbox_url'   => env('AAMARPAY_SANDBOX_URL'),
    'live_url'      => env('AAMARPAY_LIVE_URL'),
],

];
