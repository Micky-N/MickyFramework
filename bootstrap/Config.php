<?php

return [
    'app_name' => _env('APP_NAME', 'MICKYFRAMEWORK'),
    'cache' => dirname(__DIR__) . '/cache',
    'env' => _env('ENV', 'local'),
    'structure' => 'MVC',
    'debugMode' => _env('ENV') == 'local',
    'connection' => [
        'mysql' => [
            'user' => _env('DB_USER', 'root'),
            'password' => _env('DB_PASSWORD', ''),
            'name' => _env('DB_NAME', 'mickyframework'),
            'host' => _env('DB_HOST', 'localhost')
        ]
    ],
    'mkyEngine' => [
        'views' => dirname(__DIR__) . '/views',
        'cache' => dirname(__DIR__) . '/cache/views',
        'layouts' => 'layouts'
    ]
];
