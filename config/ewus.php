<?php
declare(strict_types=1);

return [
    'sandbox_mode' => env('EWUS_SANDBOX_MODE', false),

    'connection' => env('EWUS_CONNECTION', 'http'),

    'connections' => [
        'http' => [],

        'soap' => [],
    ],
];
