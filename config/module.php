<?php

/*
 * -------------------------------------------------------------
 *  Module config
 * -------------------------------------------------------------
 *
 * This value is the root folders for views,
 * layouts and includes files. Thank to this values, you are
 * completely free to separate this 3 root folders. More useful for HMVC structure,
 * this config is the default config for modules, exemple:
 * if in a module, the layouts config values is not set then all module
 * layouts will be in this layouts config value folder
 *
 */
return [
    'views' => dirname(__DIR__) . '/views',

    /*
     * -------------------------------------------------------------
     *  Layouts
     * -------------------------------------------------------------
     *
     * Exemple: <mky:extends name="layouts.template" />
     * or if this value is equal .../views/layouts then
     * <mky:extends name="template" />
     *
     */

    'layouts' => dirname(__DIR__) . '/views',

    /*
     * -------------------------------------------------------------
     *  Includes
     * -------------------------------------------------------------
     *
     * Exemple: <mky:include name="include.footer" />
     * or if this value is equal .../views/includes then
     * <mky:include name="footer" />
     *
     */

    'includes' => dirname(__DIR__) . '/views',
];