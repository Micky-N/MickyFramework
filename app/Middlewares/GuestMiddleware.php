<?php

namespace App\Middlewares;

use MkyCore\Interfaces\MiddlewareInterface;
use MkyCore\Interfaces\ResponseHandlerInterface;
use MkyCore\Request;

class GuestMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $next): ResponseHandlerInterface
    {
        if($request->auth()->isLogin()){
            return redirect()->back();
        }
        return $next($request);
    }
}