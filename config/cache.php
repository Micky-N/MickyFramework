<?php
/*
 * -------------------------------------------------------------
 *  Filesystems config
 * -------------------------------------------------------------
 *
 * Filesystems configuration for app
 *
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem space
    |--------------------------------------------------------------------------
    |
    | You can specify the default filesystem space that should be used
    | by the framework.
    |
    */
    'default' => 'app',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Spaces
    |--------------------------------------------------------------------------
    |
    | You can configure as many filesystem spaces as you wish, and you
    | may even configure multiple spaces of the same driver.
    |
    | Supported Drivers: "local"
    |
    */
    'spaces' => [
        'app' => [
            'driver' => 'local',
            'path' => tmp_path('cache/app'),
        ],
        'session' => [
            'driver' => 'local',
            'path' => tmp_path('cache/session'),
        ],
    ],

];
