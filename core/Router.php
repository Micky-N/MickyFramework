<?php

namespace Core;

use Core\Exceptions\Router\RouteAlreadyExistException;
use Core\Exceptions\Router\RouteNeedParamsException;
use Core\Exceptions\Router\RouteNotFoundException;
use Core\Facades\Session;
use Exception;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var Route[]
     */
    private array $routes = [];

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Set GET method route
     *
     * @param string $path
     * @param callable|array $action
     * @return Route
     * @throws RouteAlreadyExistException
     */
    public function get(string $path, $action): Route
    {
        if($this->checkIfAlreadyRouteExist('GET', $path)){
            throw new RouteAlreadyExistException("Route $path already exist in GET routes");
        }
        $route = new Route($path, $action);
        $this->routes['GET'][] = $route;
        return $route;
    }

    /**
     * Set POST method route
     *
     * @param string $path
     * @param callable|array $action
     * @return Route
     * @throws RouteAlreadyExistException
     */
    public function post(string $path, $action): Route
    {
        if($this->checkIfAlreadyRouteExist('POST', $path)){
            throw new RouteAlreadyExistException("Route $path already exist in POST routes");
        }
        $route = new Route($path, $action);
        $this->routes['POST'][] = $route;
        return $route;
    }

    /**
     * Check if route already exist in request method
     *
     * @param string $requestMethod
     * @param string $path
     * @return bool
     */
    private function checkIfAlreadyRouteExist(string $requestMethod, string $path)
    {
        if(isset($this->routes[$requestMethod])){
            foreach ($this->routes[$requestMethod] as $route) {
                if(trim($path, '/') === trim($route->getPath(), '/')){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get all routes by name
     *
     * @return Route[]
     */
    public function routesByName(): array
    {
        $routesNamed = [];
        foreach ($this->routes as $request => $routesRequest) {
            foreach ($this->routes[$request] as $route) {
                $routesNamed[$route->name] = $route;
            }
        }
        return $routesNamed;
    }

    /**
     * Create all crud model routes
     *
     * @param string $namespace
     * @param string $controller
     * @param array $only
     * @return void
     */
    public function crud(string $namespace, string $controller, array $only = []): void
    {
        $path = '';
        $action = '';
        $controllerName = get_plural(strtolower(str_replace('Controller', '', str_replace('App\\Http\\Controllers\\', '', $controller))));
        $id = "/:" . strtolower(str_replace('Controller', '', get_singular($controllerName)));
        if(strpos($namespace, '.')){
            $namespaces = explode('.', $namespace);
            $namespace = end($namespaces);
            foreach ($namespaces as $key => $name) {
                if($key < count($namespaces) - 1){
                    $path .= "$name/:" . get_singular($name) . '/';
                }
            }
            $controllerName = join('.', array_map(function ($n) {
                return strtolower($n);
            }, $namespaces));
            array_shift($namespaces);
            $action = join('', array_map(function ($n) {
                return ucfirst(get_singular($n));
            }, $namespaces));
            $id = "/:" . get_singular($namespace);
        }

        $crudActions = [
            'index' => [
                'request' => 'get',
                'path' => "{$path}$namespace",
                'action' => [$controller, 'index' . $action],
                'name' => "$controllerName.index",
            ],
            'show' => [
                'request' => 'get',
                'path' => "{$path}$namespace{$id}",
                'action' => [$controller, 'show' . $action],
                'name' => "$controllerName.show",
            ],
            'new' => [
                'request' => 'get',
                'path' => "{$path}$namespace/new",
                'action' => [$controller, 'new' . $action],
                'name' => "$controllerName.new",
            ],
            'create' => [
                'request' => 'post',
                'path' => "{$path}$namespace",
                'action' => [$controller, 'create' . $action],
                'name' => "$controllerName.create",
            ],
            'edit' => [
                'request' => 'get',
                'path' => "{$path}$namespace/edit{$id}",
                'action' => [$controller, 'edit' . $action],
                'name' => "$controllerName.edit",
            ],
            'update' => [
                'request' => 'post',
                'path' => "{$path}$namespace/update{$id}",
                'action' => [$controller, 'update' . $action],
                'name' => "$controllerName.update",
            ],
            'delete' => [
                'request' => 'get',
                'path' => "{$path}$namespace/delete{$id}",
                'action' => [$controller, 'delete' . $action],
                'name' => "$controllerName.delete",
            ],
        ];
        foreach ($crudActions as $key => $crudAction) {
            if(!empty($only) && in_array($key, $only)){
                $route = call_user_func_array(
                    [$this, $crudAction['request']],
                    [$crudAction['path'], $crudAction['action']]
                );
                call_user_func_array([$route, 'name'], [$crudAction['name']]);
            } elseif(empty($only)) {
                $route = call_user_func_array(
                    [$this, $crudAction['request']],
                    [$crudAction['path'], $crudAction['action']]
                );
                call_user_func_array([$route, 'name'], [$crudAction['name']]);
            }
        }
    }

    /**
     * Check if route needs params
     *
     * @param string $path
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function routeNeedParams(string $path, array $params = []): string
    {
        $path = explode('/', $path);
        foreach ($path as $key => $value) {
            if(strpos($value, ':') === 0){
                $value = str_replace(':', '', $value);
                if(!empty($params) && isset($params[$value])){
                    $path[$key] = $params[$value];
                } else {
                    throw new RouteNeedParamsException("No value for the parameter $value");
                }
            }
        }
        $path = implode('/', $path);
        return $path;
    }

    /**
     * Generate url by route name
     *
     * @param string $routeName
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function generateUrlByName(string $routeName, array $params = []): string
    {
        $requestTest = '';
        foreach ($this->routes as $request => $routesByRequest) {
            $requestTest = $request;
            foreach ($routesByRequest as $route) {
                if($route->name === $routeName){
                    return $this->routeNeedParams($route->path, $params);
                }
            }
        }
        throw new RouteNotFoundException("Route '$routeName' not found", 404);
    }

    /**
     * Get the current route
     * or if route is the currently used
     *
     * @param string $route
     * @return bool|string
     */
    public function currentRoute(string $route = '')
    {
        $currentRoute = ServerRequest::fromGlobals()
            ->getUri()
            ->getPath();
        if($route){
            return $currentRoute === $route;
        }
        return $currentRoute;
    }

    /**
     * Get route namespace
     * @param string $route
     * @return bool
     * @example namespace(x/y/z) = x
     *
     */
    public function namespaceRoute(string $route = '')
    {
        $currentRoute = ServerRequest::fromGlobals()
            ->getUri()
            ->getPath();
        $currentRouteArray = explode('/', trim($currentRoute, '/'));
        if($route){
            return $currentRouteArray[0] === $route;
        }
        return false;
    }

    /**
     * Run router application
     *
     * @param ServerRequestInterface $request
     * @return void|View
     * @throws Exception
     */
    public function run(ServerRequestInterface $request)
    {
        if(isset($this->routes[$request->getMethod()])){
            foreach ($this->routes[$request->getMethod()] as $route) {
                if($route->match($request)){
                    return $route->execute($request);
                }
            }
        }
        throw new RouteNotFoundException(sprintf('Route %s not found', $request->getUri()->getPort()), 404);
    }

    /**
     * Redirect to url
     *
     * @param string $url
     */
    public function redirect(string $url): void
    {
        header("Location: $url");
    }

    /**
     * Redirect to route name url
     *
     * @param string $name
     * @return Router
     * @throws Exception
     */
    public function redirectName(string $name): self
    {
        $path = $this->generateUrlByName($name);
        header("Location: $path");
        return $this;
    }

    /**
     * Redirect with errors flash message
     *
     * @param array $errors
     * @return $this
     */
    public function withError(array $errors): self
    {
        foreach ($errors as $name => $message) {
            Session::setFlashMessageOnType(Session::getConstant('FLASH_ERROR'), $name, $message);
        }
        return $this;
    }

    /**
     * Redirect with success flash message
     *
     * @param array $success
     * @return $this
     */
    public function withSuccess(array $success): self
    {
        foreach ($success as $name => $message) {
            Session::setFlashMessageOnType(Session::getConstant('FLASH_SUCCESS'), $name, $message);
        }
        return $this;
    }

    /**
     * Redirect with flash message
     *
     * @param array $messages
     * @return $this
     */
    public function with(array $messages): self
    {
        foreach ($messages as $name => $message) {
            Session::setFlashMessageOnType(Session::getConstant('FLASH_MESSAGE'), $name, $message);
        }
        return $this;
    }

    /**
     * Back redirection
     *
     * @return Router
     */
    public function back(): self
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        return $this;
    }

    /**
     * Affiche les routes sont forme de tableau
     * pour cli
     *
     * @return array
     */
    public function toArray(): array
    {
        includeAll('routes');
        $routesArray = [];
        $namespace = '/';
        $currentPath = "\t";
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route) {
                $paths = explode('/', trim($route->path, '/'));
                if(in_array($namespace, $paths)){
                    unset($paths[0]);
                    $currentPath = "\t/" . join('/', $paths);
                } else {
                    $namespace = $paths[0];
                    $currentPath = $route->path;
                }
                $routesArray[] = [
                    $method,
                    $currentPath,
                    str_replace(
                        'App\\Http\\Controllers\\',
                        '',
                        $route->action[0]
                    ),
                    $route->action[1],
                    $route->name,
                    $route->middleware,
                ];
            }
        }
        return $routesArray;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
