<?php

namespace Core;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private string $path;
    private array $action;
    private string $name;
    /**
     * @var string|array
     */
    private $middleware;
    private array $matches;

    /**
     * @param string $path
     * @param array $action
     */
    public function __construct(string $path, array $action)
    {
        $this->path = '/' . trim($path, '/');
        $this->action = $action;
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    public function execute(ServerRequestInterface $request)
    {
        $params = [];
        if($this->matches){
            $params = $this->matches;
        }
        if(!empty($this->middleware)){
            $routerMiddleware = new RouterMiddleware($this->middleware, $this->matches);
            $routerMiddleware->process($request);
        }
        if($request->getParsedBody()){
            $params[] = $request->getParsedBody();
        }
        $controller = new $this->action[0]();
        $method = $this->action[1];
        $this->routesDebugBar($request, $params, $controller, $method);
        return call_user_func_array([$controller, $method], $params);
    }

    public function match(ServerRequestInterface $request)
    {
        $url = trim($request->getUri()->getPath(), '/');
        $path = preg_replace('#(:[\w]+)#', '([^/]+)', trim($this->path, '/'));

        $pathToMatch = "#^$path$#";
        if(preg_match($pathToMatch, $url, $matches)){
            $key = array_map(function ($pa) {
                if(strpos($pa, ':') == 0){
                    $pa = str_replace(':', '', $pa);
                }
                return $pa;
            }, explode('/:', trim($this->path, '/')));
            array_shift($key);
            array_shift($matches);
            $matches = $matches != [] ? array_combine($key, $matches) : $matches;
            $this->matches = $matches;
            return true;
        }
        return false;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of middleware
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Set a new name to $this->name
     * @param string $name
     * @return Router
     */
    public function name(string $name): Router
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set a new middleware to $this->middleware
     * @param string|array $middleware
     * @return Router
     */
    public function middleware($middleware): Router
    {
        $this->middleware = $middleware;
        return $this;
    }

    /**
     * Get the value of path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the value of action
     */
    public function getAction()
    {
        return $this->action;
    }

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
    }
}
