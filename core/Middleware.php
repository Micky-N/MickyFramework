<?php


namespace Core;


use Core\Interfaces\MiddlewareInterface;
use GuzzleHttp\Psr7\ServerRequest;

class Middleware
{

    public static int $index = 0;

    /**
     * Lance le process de middleware en static
     *
     * @param MiddlewareInterface[] $middlewares
     * @return mixed
     */
    public function run(array $middlewares)
    {
        if (self::$index < count($middlewares)) {
            $index = self::$index;
            self::$index++;
            if (!empty($middlewares[$index]) && new $middlewares[$index]() instanceof MiddlewareInterface) {
                return call_user_func([new $middlewares[$index](), 'process'], [$this, 'process'], ServerRequest::fromGlobals());
            }
        }
        self::$index = 0;
        return ServerRequest::fromGlobals();
    }
}