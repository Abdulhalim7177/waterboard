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

    'nabroll' => [
        'api_key' => env('NABROLL_API_KEY', 'Pk_TeStHV9FnLZE1vSidgkH36b4s473lpKYkI58gYgc6M'),
        'secret_key' => env('NABROLL_SECRET_KEY', 'Sk_teSTN-HY[n1]wIO32A-AU0XP5kR[tzHpOxQ6bf9]]'),
        'base_url' => env('NABROLL_BASE_URL', 'https://demo.nabroll.com.ng/api/v1'),
    ],

    'glpi' => [
        'api_url' => env('GLPI_API_URL'),
        'username' => env('GLPI_USERNAME'),
        'password' => env('GLPI_PASSWORD'),
        'api_token' => env('GLPI_API_TOKEN'),
    ],

    'dolibarr' => [
        'api_url' => env('DOLIBARR_API_URL'),
        'username' => env('DOLIBARR_USERNAME'),
        'password' => env('DOLIBARR_PASSWORD'),
        'api_key' => env('DOLIBARR_API_KEY'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

];
