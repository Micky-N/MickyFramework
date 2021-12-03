<?php

require_once 'public/index.php';

return
    [
        'paths' => [
            'migrations' => ROOT . 'database/migrations',
            'seeds' => ROOT . 'database/seeds'
        ],
        'environments' => [
            'default_environment' => 'local',
            'local' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => config('connection.mysql.name'),
                'user' => config('connection.mysql.user'),
                'password' => config('connection.mysql.password'),
            ]
        ],
        'version_order' => 'creation'
    ];
