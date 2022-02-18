<?php

/*
 * -------------------------------------------------------------
 *  MiddlewareServiceProvider
 * -------------------------------------------------------------
 *
 * This provider records all routes middlewares with an alias and
 * all voters for permission
 *
 */

return [
    'routeMiddlewares' => [
        //'test' => \App\Http\Middlewares\TestMiddleware::class
    ],

    'voters' => [
        // \App\Voters\TestVoter::class,
    ],
];
