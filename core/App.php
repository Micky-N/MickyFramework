<?php

namespace Core;

use Core\Facades\Permission;
use Core\Facades\Route;
use Core\Interfaces\VoterInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    public static function Providers(string $key = '')
    {
        $provider = include (defined('ROOT') ? ROOT : './') . 'core/Provider.php';
        return $key && isset($key) ? $provider[$key] : $provider;
    }

    /**
     * @var Middleware[]
     */
    private static array $middlewares;

    /**
     * @var VoterInterface[]
     */
    private static array $voters;

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
     * @return Middleware[]
     */
    public static function getMiddlewares(): array
    {
        return self::$middlewares;
    }


}