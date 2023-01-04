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
     *  Timezone default value for date
     * -------------------------------------------------------------
     */
    'timezone' => 'Europe/Paris',

    /*
     * -------------------------------------------------------------
     *  Locale default setting
     * -------------------------------------------------------------
     */
    'locale' => 'fr_FR',

    /*
     * -------------------------------------------------------------
     *  Home Url
     * -------------------------------------------------------------
     */
    'home' => '/',

    /*
     * -------------------------------------------------------------
     *  Route default mode
     * -------------------------------------------------------------
     *
     * System of route collection, value must be file, controller or both
     * file means save routes in start/routes.php file
     * controller meant save routes in controller/method with annotation @Router(...)
     * can be overwrite in module by setting value in config file
     * Ex: app:route_mode => 'file'
     *
     */
    'route_mode' => 'controller',

    /*
     * -------------------------------------------------------------
     *  Security
     * -------------------------------------------------------------
     */
    'security' => [
        /*
         * CSRF security system
         */
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