<?php

return [
    'app_name' => _env('APP_NAME', 'MICKYFRAMEWORK'),
    'cache' => dirname(__DIR__) . '/cache',
    'env' => _env('ENV', 'local'),
    'structure' => _env('STRUCTURE', 'MVC'),
    'security' => ['csrf', 'xss'],
    'debugMode' => _env('ENV') == 'local',
];