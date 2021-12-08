<?php


namespace Core;


use Core\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware
{

    private int $index = 0;
    private array $middlewares;

    public function __construct(array $middlewares)
    {
        $middlewares = array_filter($middlewares);
        $this->middlewares = $middlewares;
    }

    public function process(ServerRequestInterface $request)
    {
        if($this->index < count($this->middlewares)){
            $index = $this->index;
            $this->index++;
            if(!empty($this->middlewares[$index]) && new $this->middlewares[$index]() instanceof MiddlewareInterface){
                return call_user_func([new $this->middlewares[$index](), 'process'], [$this, 'process'], $request);
            }
        }
        $this->index = 0;
        return $request;
    }
}