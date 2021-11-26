<?php

namespace Core\Facades;

use Core\View as CoreView;

class View {

    public static $view;

    public static function __callStatic($method, $arguments)
    {
        if(is_null(self::$view)){
            self::$view = new CoreView();
        }
        return call_user_func_array([self::$view, $method], $arguments);
    }
}