<?php

namespace App\Http\Middlewares;


use MkyCore\AuthManager;
use MkyCore\Facades\Route;
use MkyCore\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware implements MiddlewareInterface
{

    /**
     * @param callable $next
     * @param ServerRequestInterface $request
     * @return callable|void
     */
    public function process(callable $next, ServerRequestInterface $request)
    {
        $auth = new AuthManager();
        if(!$auth->isLogin()){
            Route::redirectName('auth.signin');
        }
        return $next($request);
    }
}