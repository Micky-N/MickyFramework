<?php


return [
    'routeMiddlewares' => [
        'auth' => \App\Http\Middlewares\AuthMiddleware::class
    ],

    'voters' => [
        \App\Voters\RoleVoter::class,
    ],
];
