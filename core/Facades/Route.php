<?php

namespace Core\Facades;

use Core\Route as CoreRoute;


/**
 * @method static \Core\Route get(string $path, $action)
 * @method static \Core\Route post(string $path, $action)
 * @method static \Core\Route namespaceRoute(string $route = '')
 * @method static \Core\Route routesByName()
 * @method static \Core\Route crud(string $namespace, $controller, array $only = [])
 * @method static \Core\Route routeNeedParams(string $path)
 * @method static \Core\Route generateUrlByName(string $routeName, array $params = [])
 * @method static \Core\Route currentRoute(string $route = '')
 * @method static \Core\Route run(\Psr\Http\Message\ServerRequestInterface $request)
 * @method static \Core\Route redirectName(string $name)
 * @method static \Core\Route redirect(string $url)
 * @method static \Core\Route back()
 * @method static \Core\Route toArray()
 *
 * @see \Core\Route
 */
class Route{

    /**
     * @var CoreRoute|null
     */
    public static ?CoreRoute $route;

    /**
     * @param $method
     * @param $arguments
     * @return void
     */
    public static function __callStatic($method, $arguments)
    {
        if(empty(self::$route)){
            self::$route = new CoreRoute();
        }
        return call_user_func_array([self::$route, $method], $arguments);
    }
}