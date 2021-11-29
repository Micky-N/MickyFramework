<?php

namespace Core;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private string $path;
    private array $action;
    private string $name;
    private string $middleware;
    private array $matches;


    /**
     * @param string $path
     * @param Callable|string $action
     */
    public function __construct(string $path, $action)
    {
        $this->path = $path;
        $this->action = $action;
    }

    public function execute(ServerRequestInterface $request)
    {
        $params = [];
        if ($this->matches) {
            $params = $this->matches;
        }
        if ($request->getParsedBody()) {
            $params[] = $request->getParsedBody();
        }
        $controller = new $this->action[0]();
        $method = $this->action[1];
        return call_user_func_array([$controller, $method], $params);
    }

    public function match(ServerRequestInterface $request)
    {
        $url = trim($request->getUri()->getPath(), '/');
        $path = preg_replace('#(:[\w]+)#', '([^/]+)', trim($this->path, '/'));
        $pathToMatch = "#^$path$#";
        if (preg_match($pathToMatch, $url, $matches)) {
            array_shift($matches);
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
     * @param string $name
     * @return Router
     */
    public function middleware(string $middleware): Router
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

    public function __get($name)
    {
        if (isset($this->{$name})) {
            return $this->{'get' . ucfirst($name)}();
        }
    }
}
