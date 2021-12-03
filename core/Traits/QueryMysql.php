<?php

namespace Core\Traits;

use Core\QueryBuilderMysql;

trait QueryMysql
{

    public static $query;

    public static function __callStatic($method, $arguments)
    {
        if (is_null(self::$query) || self::$query->getInstance() != get_called_class()) {
            self::$query = new QueryBuilderMysql(get_called_class());
        }
        return call_user_func_array([self::$query, $method], $arguments);
    }
}
