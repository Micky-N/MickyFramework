<?php

return [
    'app_name' => _env('APP_NAME', 'MICKYFRAMEWORK'),
    'env' => _env('ENV', 'local'),
    'debugMode' => true,
    'connection' => [
        'mysql' => [
            'user' => _env('DB_USER', 'root'),
            'password' => _env('DB_PASSWORD', ''),
            'name' => _env('DB_NAME', 'mickyframework'),
            'host' => _env('DB_HOST', 'localhost')
        ]
    ]
];
