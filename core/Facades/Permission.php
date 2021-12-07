<?php


namespace Core\Facades;

use Core\Permission as CorePermission;

class Permission
{
    /**
     * @var CorePermission|null
     */
    public static ?CorePermission $permission;

    public static function __callStatic($method, $arguments)
    {
        if(empty(self::$permission)){
            self::$permission = new CorePermission();
        }
        return call_user_func_array([self::$permission, $method], $arguments);
    }
}