<?php

namespace Core\Facades;

use Core\Session as CoreSession;

/**
 * @method static mixed get(string $key, $default = null)
 * @method static void set(string $key, $value)
 * @method static void delete(string $key)
 * @method static void setFlashMessageOnType(string $type, string $name, string $message)
 * @method static void setFlashMessage(string $name, string $message)
 * @method static void getFlashMessagesByType(string $type)
 * @method static void getFlashMessage()
 * @method static string getConstant(string $constant)
 * @method static array getAll()
 *
 * @see \Core\Session
 */
class Session
{
    /**
     * @var CoreSession|null
     */
    public static ?CoreSession $session;

    public static function __callStatic($method, $arguments)
    {
        if (empty(self::$session)) {
            self::$session = new CoreSession();
        }
        return call_user_func_array([self::$session, $method], $arguments);
    }
}
