<?php

/*
 * -------------------------------------------------------------
 *  Database config
 * -------------------------------------------------------------
 *
 * Database configuration for app
 *
 */

return [

    /*
     * -------------------------------------------------------------
     *  Connections
     * -------------------------------------------------------------
     *
     * return the connection params for your database system
     *
     */
    'connections' => [

        /*
         * -------------------------------------------------------------
         *  Mysql
         * -------------------------------------------------------------
         *
         * return the PDO connection params you can change this in your
         * .env file
         *
         */

        'mysql' => [
            'user' => _env('DB_USER', 'root'),
            'password' => _env('DB_PASSWORD', ''),
            'name' => _env('DB_NAME', 'mkyframework'),
            'host' => _env('DB_HOST', 'localhost')
        ]
    ]
];