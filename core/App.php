<?php

namespace Core;

use Core\Facades\Permission;
use Core\Facades\Route;
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

    public static function Providers(string $key = '')
    {
        $provider = include (defined('ROOT') ? ROOT : './') . 'core/Provider.php';
        return $key && isset($key) ? $provider[$key] : $provider;
    }

    public static function setMiddlewares()
    {
        self::$middlewares = self::Providers('middlewares');
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
        self::setVoters();
        self::setRoutes();
        Route::run($request);
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


}