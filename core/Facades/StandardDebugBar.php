<?php


namespace Core\Facades;

use Core\StandardDebugBar as CoreStandardDebugBar;


/**
 * @method static \Core\StandardDebugBar addMessage(string $collector, $message, $type = 'info')
 * @method static string|null render()
 * @method static string|null renderhead()
 *
 * @see \Core\StandardDebugBar
 */
class StandardDebugBar
{
    /**
     * @var CoreStandardDebugBar|null
     */
    public static ?CoreStandardDebugBar $debugbar;

    public static function __callStatic($method, $arguments)
    {
        if(empty(self::$debugbar)){
            self::$debugbar = new CoreStandardDebugBar();
        }
        return call_user_func_array([self::$debugbar, $method], $arguments);
    }
}