<?php


namespace Tests\App\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class TestMiddleware implements \Core\Interfaces\MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(callable $next, ServerRequestInterface $request)
    {
        return $next($request);
    }
}