<?php

/*
 * -------------------------------------------------------------
 *  Auth config
 * -------------------------------------------------------------
 *
 * Auth configuration for app
 *
 */
return [
    /*
     * -------------------------------------------------------------
     *  Default auth config
     * -------------------------------------------------------------
     *
     * Provider that be called by default for authentication
     *
     */
    'default' => [
        'provider' => 'user'
    ],

    /*
     * -------------------------------------------------------------
     *  Providers
     * -------------------------------------------------------------
     *
     * List of providers
     *
     */
    'providers' => [
        'user' => [
            'manager' => 'UserManager',
            'properties' => ['email', 'password']
        ]
    ],
];