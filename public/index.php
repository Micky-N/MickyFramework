<?php


require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/start.php';

$nodeRequestHandler = $app->get(\MkyCore\NodeRequestHandler::class);

$nodeRequestHandler->handle(
    $request = \MkyCore\Request::fromGlobals()
)->send();
