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
     * Retourne la liste du provider
     *
     * @return mixed
     */
    public static function Providers()
    {
        self::$providers = include dirname(__DIR__) . '/bootstrap/Provider.php';
        return self::$providers;
    }

    /**
     * Retourne les events et les listeners
     *
     * @return array|EventInterface[]|mixed
     */
    public static function EventServiceProviders()
    {
        self::$events = include dirname(__DIR__) . '/bootstrap/EventServiceProvider.php';
        return self::$events;
    }

    /**
     * Retourne les middlewares, routeMiddlewares et voters
     *
     * @return array|mixed
     */
    public static function MiddlewareServiceProviders()
    {
        self::$middlewareServiceProviders = include dirname(__DIR__) . '/bootstrap/MiddlewareServiceProvider.php';
        return self::$middlewareServiceProviders;
    }

    /**
     * Inscrit les voters à partir des providers
     */
    public static function VotersInit()
    {
        foreach (self::$middlewareServiceProviders['voters'] as $voter) {
            Permission::addVoter(new $voter());
        }
    }

    /**
     * Inscrit les events et listeners à partir des providers
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
     * Inscrit un alias dans le Provider
     *
     * @param string $key
     * @param string $class
     */
    public static function setAlias(string $key, string $class)
    {
        self::$providers['alias'][$key] = $class;
    }

    public static function setMiddleware(string $middleware)
    {
        self::$middlewareServiceProviders['middlewares'][] = $middleware;
    }

    public static function setRouteMiddleware(string $alias, string $routeMiddleware)
    {
        self::$middlewareServiceProviders['routeMiddlewares'][$alias] = $routeMiddleware;
    }

    /**
     * Inscrit les routes à partir des providers
     */
    public static function RoutesInit()
    {
        includeAll(dirname(__DIR__) . '/routes');
        self::$routes = Route::getRoutes();
    }

    /**
     * Lance le remplissage de providers
     * et envoi la requête dans la route
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
     * Retourne les voters
     *
     * @return VoterInterface[]
     */
    public static function getVoters(): array
    {
        return self::$middlewareServiceProviders['voters'];
    }

    /**
     * Retourne les middlewares ou le middleware
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
     * Retourne les routeMiddlewares ou le routeMiddleware
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
     * Retourne les events
     *
     * @return EventInterface[]|null
     */
    public static function getEvents()
    {
        return self::$events ?? null;
    }

    /**
     * Retourne les listeners d'un event
     * @param string $event
     * @return ListenerInterface[]|null
     */
    public static function getListeners(string $event)
    {
        return self::$events[$event] ?? null;
    }

    /**
     * Retourne le listener de l'action de l'event
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
     * Retourne les classes par l'alias
     *
     * @param string $key
     * @return mixed|null
     */
    public static function getAlias(string $key)
    {
        return self::$providers['alias'][$key] ?? null;
    }

    /**
     * Lance le debugbar si activer
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
     * @return array
     */
    public static function getProviders(): array
    {
        return self::$providers;
    }

    /**
     * @return Route[]
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }
}
