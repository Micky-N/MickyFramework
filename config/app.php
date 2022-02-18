<?php

/*
 * -------------------------------------------------------------
 *  App config
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
    'app_name' => _env('APP_NAME', 'MkyFramework'),

    /*
     * -------------------------------------------------------------
     *  Application structure
     * -------------------------------------------------------------
     *
     * MVC by default, in your .env file you can change choosing between MVC or HMVC
     *
     */
    'structure' => _env('STRUCTURE', 'MVC'),
    /*
     * -------------------------------------------------------------
     *  Cache
     * -------------------------------------------------------------
     *
     * Root folder for cache, for compile views, logs or what you need
     *
     */

    'cache' => dirname(__DIR__) . '/cache',

    /*
     * -------------------------------------------------------------
     *  Environment
     * -------------------------------------------------------------
     *
     * Local by default, this value determines the environment of your
     * application. Set this in your .env file
     *
     */

    'env' => _env('ENV', 'local'),

    /*
     * -------------------------------------------------------------
     *  Permission
     * -------------------------------------------------------------
     *
     * Affirmative by default, this value determines which strategy
     * you want to use for auth permission. you can change it choosing
     * between [affirmative,unanimous, consensus].
     * 2 others parametre can be set [allow_if_all_abstain, allow_if_equal_granted_denied]
     * allow_if_all_abstain is false by default
     * allow_if_equal_granted_denied is true by default
     *
     */

    'permission' => [
        'strategy' => 'affirmative'
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

    'debugMode' => _env('ENV', 'local') == 'local',
];