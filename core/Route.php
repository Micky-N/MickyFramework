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
     * Execute le controller et la méthode
     * de la route
     *
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
            $routeMiddleware = new RouteMiddleware($this->middleware, $this->matches);
            $routeMiddleware->process($request);
        }
        if($request->getParsedBody()){
            $params[] = $request->getParsedBody();
        }
        if(is_array($this->action)){
            $controller = new $this->action[0]();
            $method = $this->action[1];
            $this->routesDebugBar($request, $params, $controller, $method);
            return call_user_func_array([$controller, $method], $params);
        }else if(is_callable($this->action)){
            return call_user_func_array($this->action, $params);
        }
    }

    /**
     * Compare la requête et l'url de la route
     * et récupère les paramètres
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
     * Retourne le nom de la route
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne le middleware
     *
     * @return array|string
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Inscrit un nom de la route
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
     * Inscrit un middleware a la route
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
     * Retourne l'url de la route
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retourne l'action de la route
     *
     * @return array|callable
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Affiche la route dans le DebugBar
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
    }
}
