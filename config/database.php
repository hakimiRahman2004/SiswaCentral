<?php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'siswacentral'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'G7!kP9@xL2#vQ8$m'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];
