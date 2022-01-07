<?php


namespace Tests\App\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class ConditionMiddleware implements \Core\Interfaces\MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(callable $next, ServerRequestInterface $request)
    {
        if($request->getParsedBody() && !$request->getParsedBody()['go']){
            return false;
        }
        return $next($request);
    }
}