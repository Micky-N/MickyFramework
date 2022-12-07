<?php

/*
 * -------------------------------------------------------------
 *  Application config
 * -------------------------------------------------------------
 *
 * Main configuration for app
 *
 */
return [
    /*
     * -------------------------------------------------------------
     *  Application name
     * -------------------------------------------------------------
     */
    'app_name' => env('APP_NAME', 'MkyFramework'),

    /*
     * -------------------------------------------------------------
     *  Home url
     * -------------------------------------------------------------
     *
     * Homepage url for redirection
     *
     */
    'home' => '/',

    /*
     * -------------------------------------------------------------
     *  Cache
     * -------------------------------------------------------------
     *
     * Root folder for cache, for compile views, logs or what you need
     *
     */
    'cache' => [
        'base' => dirname(__DIR__) . '/cache',
    ],

    /*
     * -------------------------------------------------------------
     *  Environment
     * -------------------------------------------------------------
     *
     * Local by default, this value determines the environment of your
     * application. Set this in your .env file
     *
     */
    'env' => env('ENV', 'local'),

    /*
     * -------------------------------------------------------------
     *  Route mode
     * -------------------------------------------------------------
     *
     * System of route collection, value must be file, controller or both
     * file means save routes in start/routes.php file
     * controller meant save routes in controller/method with annotation @Router(...)
     *
     */
    'route_mode' => 'both',

    /*
     * -------------------------------------------------------------
     *  Security
     * -------------------------------------------------------------
     *
     * Affirmative by default, this value determines which strategy
     * you want to use for auth permission. you can change it choosing
     * between [affirmative,unanimous, consensus].
     * 2 others parameter can be set [allow_if_all_abstain, allow_if_equal_granted_denied]
     * allow_if_all_abstain is false by default
     * allow_if_equal_granted_denied is true by default
     *
     */
    'security' => [
        'csrf' => false
    ],

    /*
     * -------------------------------------------------------------
     *  DebugMode
     * -------------------------------------------------------------
     *
     * true if the environment of your application is local, allow you
     * to run debugBar in the bottom in web browser. In which you can see
     * HTTP request, route, database MYSQL request and voter you passed
     * if exist
     *
     */
    'debug_mode' => env('APP_ENV', 'local') == 'local',
];