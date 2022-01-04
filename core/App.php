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

    /**
     * Retourne la liste du provider
     * @param string $key
     * @return mixed
     */
    public static function Providers(string $key = '')
    {
        $provider = include dirname(__DIR__) . '/bootstrap/Provider.php';
        return $key && isset($provider[$key]) ? $provider[$key] : $provider;
    }

    public static function EventServiceProviders(string $key = '')
    {
        $eventServiceProvider = include dirname(__DIR__) . '/bootstrap/EventServiceProvider.php';
        return $key && isset($eventServiceProvider[$key]) ? $eventServiceProvider[$key] : $eventServiceProvider;
    }

    public static function MiddlewareServiceProviders(string $key = '')
    {
        $middlewareServiceProvider = include dirname(__DIR__) . '/bootstrap/MiddlewareServiceProvider.php';
        return $key && isset($middlewareServiceProvider[$key]) ? $middlewareServiceProvider[$key] : $middlewareServiceProvider;
    }

    /**
     * Inscrit les middlewares à partir des providers
     */
    public static function setMiddlewares()
    {
        self::$middlewares = self::MiddlewareServiceProviders('middlewares');
    }

    /**
     * Inscrit les events et listeners à partir des providers
     */
    public static function setEvents()
    {
        self::$events = self::EventServiceProviders();
    }

    /**
     * Inscrit les voters à partir des providers
     */
    public static function setVoters()
    {
        $voters = self::MiddlewareServiceProviders('voters');
        foreach ($voters as $voter) {
            Permission::addVoter(new $voter());
        }
        self::$voters = $voters;
    }

    /**
     * Inscrit les routes à partir des providers
     */
    public static function setRoutes()
    {
        return includeAll(dirname(__DIR__) . '/routes');
    }

    /**
     * Lance le remplissage de providers
     * et envoi la requête dans la route
     * @param ServerRequestInterface $request
     * @return View
     * @throws Exception
     */
    public static function run(ServerRequestInterface $request)
    {
        self::setMiddlewares();
        self::setEvents();
        self::setVoters();
        self::setRoutes();
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
        return self::$voters;
    }

    /**
     * Retourne les middlewares
     *
     * @return MiddlewareInterface[]
     */
    public static function getMiddlewares(): array
    {
        return self::$middlewares;
    }

    /**
     * Retourne le middleware
     * @param string $middleware
     * @return MiddlewareInterface|null
     */
    public static function getMiddleware(string $middleware)
    {
        return self::$middlewares[$middleware] ?? null;
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
        return self::Providers('alias')[$key] ?? null;
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
}
