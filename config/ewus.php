<?php
declare(strict_types=1);

return [
    'sandbox_mode' => env('EWUS_SANDBOX_MODE', false),

    'connection' => env('EWUS_CONNECTION', 'http'),

    'connections' => [
        'http' => [],

        'soap' => [],
    ],

    'password' => [
        'filename' => '.ewus-password',

        'length' => env('EWUS_PASSWORD_LENGTH', 8),
    ],

    'credentials' => [
        'domain' => env('EWUS_CREDENTIALS_DOMAIN'),

        'login' => env('EWUS_CREDENTIALS_LOGIN'),

        'operator_id' => env('EWUS_CREDENTIALS_OPERATOR_ID', null),

        'operator_type' => env('EWUS_CREDENTIALS_OPERATOR_TYPE', null),
    ],
];
