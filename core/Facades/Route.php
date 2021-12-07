<?php

namespace Core\Facades;

use Core\MickyCLI;
use Core\Route as CoreRoute;

class Route{

    /**
     * @var CoreRoute|null
     */
    public static ?CoreRoute $route;

    public static function __callStatic($method, $arguments)
    {
        if(empty(self::$route)){
            self::$route = new CoreRoute();
        }
        return call_user_func_array([self::$route, $method], $arguments);
    }
}