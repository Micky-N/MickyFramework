<?php

namespace Core;

use Core\Facades\Permission;
use Core\Facades\Route;
use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;
use Core\Interfaces\MiddlewareInterface;
use Core\Interfaces\VoterInterface;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    private static array $middlewareServiceProviders = [];

    /**
     * @var EventInterface[]
     */
    private static array $events = [];
    private static array $providers = [];

    /**
     * @var Route[]
     */
    private static array $routes = [];

    /**
     * Get Provider list
     *
     * @return mixed
     */
    public static function Providers()
    {
        self::$providers = include dirname(__DIR__) . '/bootstrap/Provider.php';
        return self::$providers;
    }

    /**
     * Get events and listeners list
     *
     * @return array|EventInterface[]|mixed
     */
    public static function EventServiceProviders()
    {
        self::$events = include dirname(__DIR__) . '/bootstrap/EventServiceProvider.php';
        return self::$events;
    }

    /**
     * Get middlewares, routeMiddlewares and voters list
     *
     * @return array|mixed
     */
    public static function MiddlewareServiceProviders()
    {
        self::$middlewareServiceProviders = include dirname(__DIR__) . '/bootstrap/MiddlewareServiceProvider.php';
        return self::$middlewareServiceProviders;
    }

    /**
     * Add voters list to Permission
     */
    public static function VotersInit()
    {
        foreach (self::$middlewareServiceProviders['voters'] as $voter) {
            Permission::addVoter(new $voter());
        }
    }

    /**
     * Add event and listener
     *
     * @param string $event
     * @param string $key
     * @param string $class
     */
    public static function setEvents(string $event, string $key, string $class)
    {
        self::$events[$event][$key] = $class;
    }

    /**
     * Add alias and class to Provider
     *
     * @param string $key
     * @param string $class
     */
    public static function setAlias(string $key, string $class)
    {
        self::$providers['alias'][$key] = $class;
    }

    /**
     * Set middleware
     * 
     * @param string $middleware
     * @return void
     */
    public static function setMiddleware(string $middleware)
    {
        self::$middlewareServiceProviders['middlewares'][] = $middleware;
    }

    /**
     * Set routeMiddleware
     * 
     * @param string $alias
     * @param string $routeMiddleware
     * @return void
     */
    public static function setRouteMiddleware(string $alias, string $routeMiddleware)
    {
        self::$middlewareServiceProviders['routeMiddlewares'][$alias] = $routeMiddleware;
    }

    /**
     * Get all route file in routes folder
     */
    public static function RoutesInit()
    {
        includeAll(dirname(__DIR__) . '/routes');
        self::$routes = Route::getRoutes();
    }

    /**
     * Run the application
     *
     * @param ServerRequestInterface $request
     * @return View
     * @throws Exception
     */
    public static function run(ServerRequestInterface $request)
    {
        self::Providers();
        self::MiddlewareServiceProviders();
        self::EventServiceProviders();
        self::VotersInit();
        self::RoutesInit();
        try {
            Route::run($request);
        } catch (Exception $ex) {
            return ErrorController::error($ex->getCode(), $ex->getMessage());
        }
        self::debugMode();
    }

    /**
     * Get voters
     *
     * @return VoterInterface[]
     */
    public static function getVoters(): array
    {
        return self::$middlewareServiceProviders['voters'];
    }

    /**
     * Get all middlewares
     * or specific middleware 
     *
     * @param string|null $middleware
     * @return MiddlewareInterface[]|MiddlewareInterface|null
     */
    public static function getMiddlewares(string $middleware = null)
    {
        if($middleware){
            return self::$middlewareServiceProviders['middlewares'][$middleware] ?? null;
        }
        return self::$middlewareServiceProviders['middlewares'];
    }

    /**
     * Get all routeMiddlewares
     * or specific routeMiddleware
     *
     * @param string|null $routeMiddleware
     * @return MiddlewareInterface[]|MiddlewareInterface|null
     */
    public static function getRouteMiddlewares(string $routeMiddleware = null)
    {
        if($routeMiddleware){
            return self::$middlewareServiceProviders['routeMiddlewares'][$routeMiddleware] ?? null;
        }
        return self::$middlewareServiceProviders['middlewares'];
    }

    /**
     * Get all events
     *
     * @return EventInterface[]|null
     */
    public static function getEvents()
    {
        return self::$events ?? null;
    }

    /**
     * Get event listeners
     * 
     * @param string $event
     * @return ListenerInterface[]|null
     */
    public static function getListeners(string $event)
    {
        return self::$events[$event] ?? null;
    }

    /**
     * Get event listener on action
     *
     * @param string $event
     * @param string $action
     * @return ListenerInterface|null
     */
    public static function getListenerActions(string $event, string $action)
    {
        if (isset(self::$events[$event]) && isset(self::$events[$event][$action])) {
            return self::$events[$event][$action];
        }
        return null;
    }

    /**
     * Get class by alias
     *
     * @param string $key
     * @return mixed|null
     */
    public static function getAlias(string $key)
    {
        return self::$providers['alias'][$key] ?? null;
    }

    /**
     * Run debugBar if active
     *
     * @throws Exception
     */
    public static function debugMode()
    {
        if (config('debugMode')) {
            echo _debugRender();
        }
    }

    /**
     * Get all providers
     * 
     * @return array
     */
    public static function getProviders(): array
    {
        return self::$providers;
    }

    /**
     * Get all routes
     * 
     * @return Route[]
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }
}
