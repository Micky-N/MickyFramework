<?php

return [
    'app_name' => _env('APP_NAME', 'MICKYFRAMEWORK'),
    'cache' => dirname(__DIR__) . '/cache',
    'env' => _env('ENV', 'local'),
    'structure' => _env('STRUCTURE', 'MVC'),
    'csrf' => true,
    'permission' => [
        'strategy' => 'affirmative',
        'allow_if_all_abstain' => false,
        'allow_if_equal_granted_denied' => true
    ],
    'debugMode' => _env('ENV') == 'local',
];