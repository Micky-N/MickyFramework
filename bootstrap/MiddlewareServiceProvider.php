<?php


return [
    'routeMiddlewares' => [
        'auth' => \App\Http\Middlewares\AuthMiddleware::class
    ],

    'voters' => [
        \App\Voters\ProductVoter::class,
        \App\Voters\RoleVoter::class,
    ],
];
