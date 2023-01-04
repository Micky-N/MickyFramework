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
            'manager' => \App\UserModule\Managers\UserManager::class,
            'properties' => ['email', 'password'],
        ]
    ],

    /*
     * -------------------------------------------------------------
     *  Remember
     * -------------------------------------------------------------
     *
     * Config Remember auth, lifetime can be int for number of days
     * or string DateTime::modify, +1 day, month, year, etc.
     *
     */
    'remember' => [
        'user' => [
            'lifetime' => 5
        ]
    ],

    /*
     * -------------------------------------------------------------
     *  Password reset
     * -------------------------------------------------------------
     *
     * Password reset config, lifetime of the token sent by email (in minute)
     *
     */
    'password_reset' => [
        'user' => [
            'lifetime' => 1
        ]
    ]
];