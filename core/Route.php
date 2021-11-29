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
    public function get(string $path, $action)
    {
        $route = new Router($path, $action);
        $this->routes['GET'][] = $route;
        return $route;
    }

    /**
     * @param string $path
     * @param Callable|string $action
     * @param string $name
     * 
     */
    public function post(string $path, $action)
    {
        $route = new Router($path, $action);
        $this->routes['POST'][] = $route;
        return $route;
    }

    public function crud(string $namespace, $controller){
        $controllerName = str_replace('App\\Http\\Controllers\\', '', $controller);
        $controllerName = strtolower(str_replace('Controller', '', $controllerName));
        $this->get("$namespace", [$controller, 'index'])->name($controllerName.".index");
        $this->get("$namespace/:$controllerName", [$controller, 'show'])->name($controllerName.".show");
        $this->get("$namespace", [$controller, 'new'])->name($controllerName.".new");
        $this->post("$namespace", [$controller, 'create'])->name($controllerName.".create");
        $this->get("$namespace/:$controllerName", [$controller, 'edit'])->name($controllerName.".edit");
        $this->post("$namespace/:$controllerName", [$controller, 'update'])->name($controllerName.".update");
        $this->get("$namespace/:$controllerName", [$controller, 'delete'])->name($controllerName.".delete");
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

    public function generateUrlByName(string $routeName, $params = []){
        $path = '';
        $err = 0;
        foreach($this->routes as $method => $routes){
            foreach($routes as $router){
                if($router->name === $routeName){
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
                }else{
                    $err += 1;
                }
            }
        }
        if($err == count($routes)){
            throw new Exception("La route '$routeName' n'existe pas.", 13);
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
        foreach ($this->routes[$request->getMethod()] as $route) {
            if($route->match($request)){
                return $route->execute($request);
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

    public function toArray()
    {
        includeAll('routes');
        $routes = [];
        $namespace = "/";
        $currentPath = "\t";
        foreach ($this->routes as $method => $routers) {
            foreach ($routers as $router) {
                $paths = explode('/', $router->path);
                if(in_array($namespace, $paths)){
                    $currentPath = "\t/".(isset($paths[1]) ? $paths[1] : '');
                }else{
                    $namespace = $paths[0];
                    $currentPath = $router->path;
                }
                $routes[] = [$method, $currentPath, str_replace('App\\Http\\Controllers\\','',$router->action[0]), $router->action[1], $router->name, $router->middleware];
            }
        }
        return $routes;
    }
}
