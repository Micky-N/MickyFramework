<?php

namespace Core\Facades;

use Core\Template as CoreTemplate;

/**
 * @method static string escape(string $data)
 * @method static array _templateParams(array $params = [])
 * @method static bool layout(string $path = '')
 * @method static mixed content(string $name)
 * @method static bool section(string $name)
 * @method static bool endsection()
 * @method static bool authorize(string $permission, $subject = null)
 * @method static bool endauthorize()
 *
 * @see \Core\Template
 */
class Template
{
    public static ?CoreTemplate $template;

    public static function __callStatic($method, $arguments)
    {
        if(empty(self::$template)){
            self::$template = new CoreTemplate();
        }
        return call_user_func_array([self::$template, $method], $arguments);
    }
}