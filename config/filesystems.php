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
     *--------------------------------------------------------------------------
     * Default Filesystem space
     *--------------------------------------------------------------------------
     *
     * You can specify the default filesystem space that should be used
     * by the framework.
     *
     */
    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
     *--------------------------------------------------------------------------
     * Filesystem Spaces
     *--------------------------------------------------------------------------
     *
     * You can configure as many filesystem spaces as you wish, and you
     * may even configure multiple spaces of the same driver.
     *
     *
     */
    'spaces' => [
        'local' => [
            'driver' => 'local',
            'root' => tmp_path(),
            'visibility' => 'public',
        ],
        'dropbox' => [
            'driver' => 'dropbox',
            "access_token" => 'test_access_token',
        ]
    ],

    /*
     *--------------------------------------------------------------------------
     * Filesystem Drivers
     *--------------------------------------------------------------------------
     *
     * You can register your custom drivers, they will be called if the driver
     * name is bound to the current space in the driver value
     * Ex: dropbox => 'Class\DropboxSystem',
     * the class must extend from League\Flysystem\Filesystem.
     *
     *
     */
    'drivers' => [

    ],

    /*
     *--------------------------------------------------------------------------
     * Symbolic Links
     *--------------------------------------------------------------------------
     *
     * You can configure the symbolic links. use php mky tmp:link to create them.
     * The array form is [link => target].
     *
     */
    'links' => [
        public_path('tmp') => tmp_path('public'),
    ],

];
