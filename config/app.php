<?php

return [
    'app_name' => _env('APP_NAME', 'MICKYFRAMEWORK'),
    'cache' => dirname(__DIR__) . '/cache',
    'env' => _env('ENV', 'local'),
    'structure' => _env('STRUCTURE', 'MVC'),
    'permission' => [
        'strategy' => 'affirmative'
    ],
    'debugMode' => _env('ENV') == 'local',
];