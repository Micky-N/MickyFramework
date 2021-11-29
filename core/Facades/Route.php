<?php

namespace Core\Facades;

use Core\MickyCLI;
use Core\Route as CoreRoute;

class Route{

    public static $route;

    public static function __callStatic($method, $arguments)
    {
        if(is_null(self::$route)){
            self::$route = new CoreRoute();
        }
        return call_user_func_array([self::$route, $method], $arguments);
    }
}