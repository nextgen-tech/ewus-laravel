<?php
declare(strict_types=1);

return [
    /**
     * Enables or disabled sandbox mode.
     */
    'sandbox_mode' => env('EWUS_SANDBOX_MODE', false),

    /**
     * Connection used to communicate with API.
     */
    'connection' => env('EWUS_CONNECTION', 'http'),

    /**
     * Connections configuration.
     */
    'connections' => [
        'http' => [
            'timeout' => env('EWUS_CONNECTION_TIMEOUT'),
        ],

        'soap' => [],
    ],

    /**
     * Password configuration.
     */
    'password' => [
        /**
         * The name of file in which will be stored password.
         */
        'filename' => '.ewus-password',

        /**
         * The length of randomly generated password.
         */
        'length' => env('EWUS_PASSWORD_LENGTH', 8),
    ],

    /**
     * Credentials list.
     */
    'credentials' => [
        'domain' => env('EWUS_CREDENTIALS_DOMAIN'),

        'login' => env('EWUS_CREDENTIALS_LOGIN'),

        'operator_id' => env('EWUS_CREDENTIALS_OPERATOR_ID', null),

        'operator_type' => env('EWUS_CREDENTIALS_OPERATOR_TYPE', null),
    ],
];
