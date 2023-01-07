<?php

return [
    /*
     * -------------------------------------------------------------
     *  Global Middlewares
     * -------------------------------------------------------------
     *
     * List of middlewares that will be called before each route of all modules
     *
     */
    'globalMiddlewares' => [
        \MkyCore\Middlewares\WhoopsHandlerMiddleware::class,
        \MkyCore\Middlewares\TrailingSlashMiddleware::class,
        \MkyCore\Middlewares\MethodMiddleware::class,
        \MkyCore\Middlewares\SessionStartMiddleware::class,
        \MkyCore\Middlewares\CsrfMiddleware::class,
	    \App\Middlewares\RememberTokenMiddleware::class,
    ],

    /*
     * -------------------------------------------------------------
     *  Module Middlewares
     * -------------------------------------------------------------
     *
     * List of middlewares that will be called before each route in the module
     *
     */
    'middlewares' => [
    ],

    /*
     * -------------------------------------------------------------
     *  Route Middlewares
     * -------------------------------------------------------------
     *
     * List of middlewares that will be defined in the routes by alias
     *
     */
    'routeMiddlewares' => [
    ],
];