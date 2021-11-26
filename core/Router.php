<?php

namespace Core;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private $path;

    private $action;


    private $name;
    
    private $matches;


    /**
     * @param string $path
     * @param Callable|string $action
     * @param string $name
     */
    public function __construct(string $path, $action, string $name = '')
    {
        $this->path = $path;
        $this->action = $action;
        $this->name = $name;
    }

    public function execute(ServerRequestInterface $request)
    {
        $params = [];
        if($this->matches){
            $params = $this->matches;
        }
        if($request->getParsedBody()){
            $params[] = $request->getParsedBody();
        }
        if (is_callable($this->action) && !is_array($this->action)) {
            $action = $this->action;
            return $action($params);
        }elseif(is_string($this->action)){
            $this->action = explode('!', $this->action);
        }
        $controller = new $this->action[0]();
        $method = $this->action[1];
        return call_user_func_array([$controller, $method], $params);

    }

    public function match(ServerRequestInterface $request)
    {
        $url = trim($request->getUri()->getPath(),'/');
        $path = preg_replace('#(:[\w]+)#', '([^/]+)', trim($this->path, '/'));
        $pathToMatch = "#^$path$#";
        if(preg_match($pathToMatch, $url, $matches))
        {
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
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }
    
    public function __get($name){
        if( isset($this->{$name}) ){
            return $this->{'get'.ucfirst($name)}();
        }
    }
}

