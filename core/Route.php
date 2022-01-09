<?php

namespace Core;

use Psr\Http\Message\ServerRequestInterface;

class Route
{
    private string $path;
    /**
     * @var array|callable
     */
    private $action;

    private string $name;
    /**
     * @var string|array
     */
    private $middleware;

    private array $matches;

    /**
     * @param string $path
     * @param array|callable $action
     */
    public function __construct(string $path, $action)
    {
        $this->path = '/' . trim($path, '/');
        $this->action = $action;
    }

    /**
     * Control route and run route callback
     *
     * @param ServerRequestInterface $request
     * @return void|bool
     * @throws Exceptions\Router\RouteMiddlewareException
     */
    public function execute(ServerRequestInterface $request)
    {
        $params = [];
        if($this->matches){
            $params = $this->matches;
        }
        if(!empty($this->middleware)){
            $routeMiddleware = new RouteMiddleware($this->middleware, $this->matches);
            if(!$routeMiddleware->process($request)){
                return false;
            }
        }
        if($request->getParsedBody()){
            $params[] = $request->getParsedBody();
        }
        if(is_array($this->action)){
            $controller = new $this->action[0]();
            $method = $this->action[1];
            $this->routesDebugBar($request, $params, $controller, $method);
            return call_user_func_array([$controller, $method], $params);
        } else if(is_callable($this->action)){
            return call_user_func_array($this->action, $params);
        }
    }

    /**
     * Check if url match wih one route and get params
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request)
    {
        $url = trim($request->getUri()->getPath(), '/');
        $path = preg_replace('#(:[\w]+)#', '([^/]+)', trim($this->path, '/'));

        $pathToMatch = "#^$path$#";
        if(preg_match($pathToMatch, $url, $matches)){
            $key = array_map(function ($pa) {
                if(strpos($pa, ':') !== false){
                    $pa = preg_match('/:(.*)?/', $pa, $matches);
                    return $matches[1];
                }
                return null;
            }, explode('/', trim($this->path, '/')));
            $key = array_filter($key);
            array_shift($matches);
            $matches = $matches != [] ? array_combine($key, $matches) : $matches;
            $this->matches = $matches;
            return true;
        }
        return false;
    }

    /**
     * Get route name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get route middleware
     *
     * @return array|string
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Set name to route
     *
     * @param string $name
     * @return Route
     */
    public function name(string $name): Route
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set middleware to route
     *
     * @param string|array $middleware
     * @return Route
     */
    public function middleware($middleware): Route
    {
        $this->middleware = $middleware;
        return $this;
    }

    /**
     * Get route uri
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get route action
     *
     * @return array|callable
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Render the route to debugBar
     *
     * @param ServerRequestInterface $request
     * @param array $params
     * @param Controller $controller
     * @param string $method
     */
    public function routesDebugBar(ServerRequestInterface $request, array $params, Controller $controller, string $method)
    {
        \Core\Facades\StandardDebugBar::addMessage('Routes', [
            'url' => $request->getUri()->getPath(),
            'params' => $params,
            'controller' => get_class($controller),
            'method' => $method
        ]);
    }

    public function __get($name)
    {
        if(isset($this->{$name})){
            return $this->{'get' . ucfirst($name)}();
        }
        return null;
    }
}
