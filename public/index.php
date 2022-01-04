<?php

use Core\App;
use GuzzleHttp\Psr7\ServerRequest;


require dirname(__DIR__).'/vendor/autoload.php';

if(php_sapi_name() !== 'cli'){
    App::run(ServerRequest::fromGlobals());
}