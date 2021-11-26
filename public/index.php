<?php

use Core\App;
use GuzzleHttp\Psr7\ServerRequest;

define('ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR);

require ROOT.'vendor/autoload.php';

$app = new App();

if (php_sapi_name() != 'cli'){
    $app->run(ServerRequest::fromGlobals());
}
    
