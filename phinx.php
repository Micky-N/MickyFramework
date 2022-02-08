<?php

require_once 'public/index.php';

return
    [
        'paths' => [
            'migrations' => 'database/migrations',
            'seeds' => 'database/seeds'
        ],
        'environments' => [
            'default_environment' => 'local',
            'local' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => config('connections.mysql.name'),
                'user' => config('connections.mysql.user'),
                'password' => config('connections.mysql.password'),
            ]
        ],
        'version_order' => 'creation'
    ];