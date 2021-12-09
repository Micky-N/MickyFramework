<?php


namespace Core;


use Core\Facades\Permission;
use Core\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware
{

    private int $index = 0;
    private array $middlewares;

    public function __construct($appMiddlewares, array $matches)
    {
        $routeMiddlewares = [];
        $middlewares = is_array($appMiddlewares) ? $appMiddlewares : [$appMiddlewares];
        foreach ($middlewares as $middleware) {
            if (stripos($middleware, 'can') !== false) {
                $this->permissionCan($middleware, $matches);
            } else {
                $routeMiddlewares[] = App::getMiddleware($middleware);
            }
        }
        $routeMiddlewares = array_filter($routeMiddlewares);
        $this->middlewares = $routeMiddlewares;
    }

    public function process(ServerRequestInterface $request)
    {
        if ($this->index < count($this->middlewares)) {
            $index = $this->index;
            $this->index++;
            if (!empty($this->middlewares[$index]) && new $this->middlewares[$index]() instanceof MiddlewareInterface) {
                return call_user_func([new $this->middlewares[$index](), 'process'], [$this, 'process'], $request);
            }
        }
        $this->index = 0;
        return $request;
    }

    /**
     * @param string $middleware
     * @param array $matches
     * @return bool
     */
    private function permissionCan(string $middleware, array $matches): bool
    {
        $middlewares = explode(':', $middleware);
        $args = explode(',', $middlewares[1]);
        $permission = $args[0];
        $subject = $args[1];
        $model = "\\App\\Models\\" . ucfirst($subject);
        $subject = $model::find($matches[$subject]);
        return call_user_func([Permission::class, $middlewares[0]], $permission, $subject);
    }
}
