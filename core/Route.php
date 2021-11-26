<?php

namespace Core;

use Exception;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Route
{

    private array $routes;

    public function __construct($routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * @param string $path
     * @param Callable|array $action
     * @param string $name
     * 
     */
    public function get(string $path, $action, string $name = '')
    {
        $this->routes['GET'][] = new Router($path, $action, $name);
    }

    /**
     * @param string $path
     * @param Callable|string $action
     * @param string $name
     * 
     */
    public function post(string $path, $action, string $name = '')
    {
        $this->routes['POST'][] = new Router($path, $action, $name);
    }

    public function routeNeedParams($path){
        $needed = [];
        $pathArray = explode('/', $path);
        $needed = array_map(function($p) use ($needed){
            if(strpos($p, ':') === 0){
                $p = str_replace(':', '', $p);
                return $p;
            };
        }, $pathArray);
        $needed = array_filter($needed);
        if(!empty($needed)){
            throw new Exception('need params');
        }
        return $path;
    }

    public function generateUrlByName(string $routerName, $params = []){
        $path = '';
        foreach($this->routes as $method => $routers){
            foreach($routers as $router){
                if($router->name === $routerName){
                    $path = $router->path;
                    if(!empty($params)){
                        $path = explode('/', $path);
                        foreach ($path as $key => $value) {
                            if(strpos($value, ':') === 0){
                                $value = str_replace(':', '', $value);
                                if(isset($params[$value])){
                                    $path[$key] = $params[$value];
                                }else{
                                    throw new Exception("Le paramètre '$value' est requie", 13);
                                }
                            }
                        }
                        $path = implode('/', $path);
                    }
                }
            }
        }
        return $this->routeNeedParams($path);
    }

    /**
     * @param string $route
     * 
     * @return [type]
     */
    public function currentRoute(string $route = ''){
        $currentRoute = ServerRequest::fromGlobals()->getUri()->getPath();
        if($route){
            return $currentRoute === $route;
        }
        return $currentRoute;
    }

    public function run(ServerRequestInterface $request)
    {
        foreach ($this->routes[$request->getMethod()] as $router) {
            if($router->match($request)){
                return $router->execute($request);
            }
        }
    }

    public function redirect(string $url)
    {
        header("Location: $url");
    }

    public function redirectName(string $name)
    {
        $path = $this->generateUrlByName($name);
        header("Location: $path");
    }
}
