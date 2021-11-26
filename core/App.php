<?php

namespace Core;

use Core\Facades\Route;
use Psr\Http\Message\ServerRequestInterface;
includeAll(ROOT.'routes');

class App
{

    public function __construct()
    {

    }

    public function run(ServerRequestInterface $request)
    {
        Route::run($request);
    }
}