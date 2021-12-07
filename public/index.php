<?php

use Core\App;
use GuzzleHttp\Psr7\ServerRequest;

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

require ROOT . 'vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    App::run(ServerRequest::fromGlobals());
}
