<?php


$app = new \MkyCore\Application(dirname(__DIR__));

$app->singleton(
    \MkyCore\Interfaces\NodeRequestHandlerInterface::class,
    \MkyCore\NodeRequestHandler::class
);

$app->singleton(
    \MkyCore\Interfaces\NodeConsoleHandlerInterface::class,
    \MkyCore\Console\NodeConsoleHandler::class
);

return $app;