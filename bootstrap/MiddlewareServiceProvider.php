<?php


return [
    'routeMiddlewares' => [
        'auth' => \App\Http\Middlewares\AuthMiddleware::class
    ],

    'middlewares' => [],


    'voters' => [
        \App\Voters\ProductVoter::class,
        \App\Voters\RoleVoter::class,
    ],
];
