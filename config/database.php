<?php

return [
    'connections' => [
        'mysql' => [
            'user' => _env('DB_USER', 'root'),
            'password' => _env('DB_PASSWORD', ''),
            'name' => _env('DB_NAME', 'mkyframework'),
            'host' => _env('DB_HOST', 'localhost')
        ]
    ]
];