<?php


namespace Core;


use Core\Exceptions\Router\RouteMiddlewareException;
use Core\Facades\Permission;
use Core\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteMiddleware
{

    private int $index = 0;
    private array $routeMiddlewares;

    public function __construct($appMiddlewares, array $matches)
    {
        $routeMiddlewares = [];
        $middlewares = is_array($appMiddlewares) ? $appMiddlewares : [$appMiddlewares];
        foreach ($middlewares as $middleware) {
            if (stripos($middleware, 'can') === 0) {
                $this->permissionCan($middleware, $matches);
            } else {
                if(is_null(App::getRouteMiddlewares($middleware))){
                    throw new RouteMiddlewareException(sprintf('No middleware found for ths alias %s', $middleware));
                }
                $routeMiddlewares[] = App::getRouteMiddlewares($middleware);
            }
        }
        $routeMiddlewares = array_filter($routeMiddlewares);
        $this->routeMiddlewares = $routeMiddlewares;
    }

    /**
     * Run middlewares control
     *
     * @param ServerRequestInterface $request
     * @return mixed|ServerRequestInterface
     */
    public function process(ServerRequestInterface $request)
    {
        if ($this->index < count($this->routeMiddlewares)) {
            $index = $this->index;
            $this->index++;
            if (!empty($this->routeMiddlewares[$index]) && new $this->routeMiddlewares[$index]() instanceof MiddlewareInterface) {
                return call_user_func([new $this->routeMiddlewares[$index](), 'process'], [$this, 'process'], $request);
            }
        }
        $this->index = 0;
        return true;
    }

    /**
     * Run permission control
     *
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
        return call_user_func([Permission::class, 'can'], $permission, $subject);
    }
}
