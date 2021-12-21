<?php

namespace Core;

use Core\Facades\Permission;
use Core\Facades\Route;
use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;
use Core\Interfaces\MiddlewareInterface;
use Core\Interfaces\VoterInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    /**
     * @var MiddlewareInterface[]
     */
    private static array $middlewares;

    /**
     * @var VoterInterface[]
     */
    private static array $voters;
    /**
     * @var EventInterface[]
     */
    private static array $events;

    public static function Providers(string $key = '')
    {
        $provider = include (defined('ROOT') ? ROOT : './') . 'bootstrap/Provider.php';
        return $key && isset($provider[$key]) ? $provider[$key] : $provider;
    }

    public static function setMiddlewares()
    {
        self::$middlewares = self::Providers('middlewares');
    }

    public static function setEvents()
    {
        self::$events = self::Providers('events');
    }

    public static function setVoters()
    {
        $voters = self::Providers('voters');
        foreach ($voters as $voter) {
            Permission::addVoter(new $voter());
        }
        self::$voters = $voters;
    }

    public static function setRoutes()
    {
        return includeAll(ROOT . 'routes');
    }

    public static function run(ServerRequestInterface $request)
    {
        self::setMiddlewares();
        self::setEvents();
        self::setVoters();
        self::setRoutes();
        Route::run($request);
        self::debugMode();
    }

    /**
     * @return VoterInterface[]
     */
    public static function getVoters(): array
    {
        return self::$voters;
    }

    /**
     * @return MiddlewareInterface[]
     */
    public static function getMiddlewares(): array
    {
        return self::$middlewares;
    }

    /**
     * @param string $middleware
     * @return MiddlewareInterface|null
     */
    public static function getMiddleware(string $middleware)
    {
        if(isset(self::$middlewares[$middleware])){
            return self::$middlewares[$middleware];
        }
        return null;
    }

    /**
     * @return EventInterface[]|null
     */
    public static function getEvents()
    {
        return self::$events ?? null;
    }

    /**
     * @param string $event
     * @return ListenerInterface[]|null
     */
    public static function getListeners(string $event)
    {
        if(isset(self::$events[$event])){
            return self::$events[$event];
        }
        return null;
    }

    /**
     * @param string $event
     * @param string $listener
     * @return ListenerInterface|null
     */
    public static function getListener(string $event, string $listener)
    {
        if(isset(self::$events[$event]) && isset(self::$events[$event][$listener])){
            return self::$events[$event][$listener];
        }
        return null;
    }

    public static function debugMode()
    {
        if(config('debugMode')){
            echo _debugRender();
        }
    }
}