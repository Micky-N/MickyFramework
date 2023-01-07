<?php

/*
 * -------------------------------------------------------------
 *  Log config
 * -------------------------------------------------------------
 *
 * Log configuration for app
 *
 */
return [
    /*
     * -------------------------------------------------------------
     *  Default logger
     * -------------------------------------------------------------
     */
    'default' => 'rootLogger',

    /*
     * -------------------------------------------------------------
     *  Loggers config
     * -------------------------------------------------------------
     *
     * Configure all loggers you will use in your app, rootLogger is
     * the root parent of all loggers.
     *
     * For more information go to
     * https://logging.apache.org/log4php/docs/loggers.html
     *
     */
    'loggers' => [
        'rootLogger' => [
            'appenders' => ['default'],
        ],
        'daily' => [
            'additivity' => false,
            'appenders' => ['html'],
            'level' => 'trace'
        ],
        'foo' => [
            'additivity' => false,
            'appenders' => ['file'],
            'level' => 'trace'
        ]
    ],

    /*
     * -------------------------------------------------------------
     *  Appenders config
     * -------------------------------------------------------------
     *
     * Configure all appenders loggers will need, contrary to the documentation
     * the appender class is registered by adding only the class name suffix in
     * camel case
     * Ex: class => 'echo' will use the class LoggerAppenderEcho
     * Ex: class => 'dailyFile' will use the class LoggerAppenderDailyFile
     * same for layout
     * Ex: layout => 'pattern' will use the class LoggerLayoutPattern
     *
     *
     * For more information go to
     * https://logging.apache.org/log4php/docs/appenders.html
     *
     */
    'appenders' => [
        'default' => [
            'class' => 'echo',
        ],
        'daily' => [
            'class' => 'dailyFile',
            'layout' => [
                'class' => 'pattern',
                'params' => ['conversionPattern' => '%date %logger %-5level %msg%n']
            ],
            'params' => [
                'datePattern' => 'Y-m-d',
                'file' => tmp_path('logs/app-%s.log'),
            ],
        ],
        'file' => [
            'class' => 'file',
            'layout' => 'simple',
            'params' => [
                'file' => tmp_path('logs/app.log'),
            ],
        ],
        'rolling' => [
            'class' => 'rollingFile',
            'layout' => 'simple',
            'params' => [
                'file' => tmp_path('logs/app.log'),
                'maxFileSize' => '1KB',
                'maxBackupIndex' => 5,
            ],
        ],
        'html' => [
            'class' => 'echo',
            'layout' => 'html',
            'filters' => ['interesting']
        ],
        'mail' => array(
            'class' => 'mail',
            'layout' => 'simple',
            'params' => array(
                'to' => ['baz@example.com', 'fkgldg@example.com'],
                'from' => 'logger@example.com',
                'subject' => 'logs test report',
            ),
        ),
    ],

    /*
     * -------------------------------------------------------------
     *  Filters config
     * -------------------------------------------------------------
     *
     * Configure all filters appenders will need, contrary to the documentation
     * the filter class is registered by adding only the class name suffix in
     * camel case
     * Ex: class => 'stringMatch' will use the class LoggerFilterStringMatch
     *
     *
     * For more information go to
     * https://logging.apache.org/log4php/docs/filters.html
     *
     */
    'filters' => [
        'interesting' => [
            'class' => 'stringMatch',
            'params' => [
                'stringToMatch' => 'interesting',
                'acceptOnMatch' => false
            ]
        ],
        'debug_error' => [
            'class' => 'levelMatch',
            'params' => [
                'levelToMatch' => 'fatal',
                'acceptOnMatch' => false
            ]
        ]
    ],

    /*
     * -------------------------------------------------------------
     *  Renderers config
     * -------------------------------------------------------------
     *
     * If you try to log an object it will be converted to a string and logged.
     * The component which converts Objects to strings in log4php is called a renderer.
     * In order to make log4php log a object in a readable format,
     * You have to create a custom renderer class and defined it.
     *
     * Ex: rendering a instance of Person class
     * [
     *   'renderedClass' => 'Person', // class to render
     *   'renderingClass' => 'PersonRenderer' // renderer called
     * ]
     */
    'renderers' => [

    ],
];