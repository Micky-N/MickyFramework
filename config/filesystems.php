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
    'default' => env('FILESYSTEM_DRIVER', 'local'),

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
        'local' => [
            'driver' => 'local',
            'root' => tmp_path(),
            'visibility' => 'public',
        ],
        'dropbox' => [
            'driver' => 'dropbox',
            "client_id" => 'test',
            "client_secret" => 'test',
            "access_token" => 'test'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | You can configure the symbolic links. use php mky tmp:link to create them.
    | The array form is [link => target].
    |
    */
    'links' => [
        public_path('tmp') => tmp_path('public'),
    ],

];
