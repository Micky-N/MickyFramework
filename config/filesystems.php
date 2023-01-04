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
            'root' => tmp_path('tmp'),
            'visibility' => 'public',
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
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
