<?php

namespace Core;

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
     * Inscrit la route en GET
     *
     * @param string $path
     * @param Callable|array $action
     * @return Route
     */
    public function get(string $path, $action): Route
    {
        $route = new Route($path, $action);
        $this->routes['GET'][] = $route;
        return $route;
    }

    /**
     * Inscrit la route en POST
     * @param string $path
     * @param array $action
     * @return Route
     */
    public function post(string $path, $action): Route
    {
        $route = new Route($path, $action);
        $this->routes['POST'][] = $route;
        return $route;
    }

    /**
     * Retourne les routes par nom
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
     * Créé un ensemble de routes selon
     * le model CRUD
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
     * Vérifié si la route a besoin
     * d'un paramètre
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
                    throw new RouteNeedParamsException("Le paramètre $value est requis");
                }
            }
        }
        $path = implode('/', $path);
        return $path;
    }

    /**
     * Génère la url de la route
     * par son nom
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
        throw new RouteNotFoundException("La route '$routeName' n'existe pas dans la requête $requestTest.", 404);
    }

    /**
     * Retourne la route actuel
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
     * Retourn le namespace de la route
     *
     * @param string $route
     * @return bool
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
     * Démarre la recherche de la route
     *
     * @param ServerRequestInterface $request
     * @return void|View
     * @throws Exception
     */
    public function run(ServerRequestInterface $request)
    {
        foreach ($this->routes[$request->getMethod()] as $route) {
            if($route->match($request)){
                return $route->execute($request);
            }
        }
        throw new RouteNotFoundException(sprintf('la route %s n\'existe pas', $request->getUri()->getPort()));
    }

    /**
     * Redirection par url
     *
     * @param string $url
     */
    public function redirect(string $url): void
    {
        header("Location: $url");
    }

    /**
     * Redirection par nom de route
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
     * Inscrit un message d'erreur
     * dans la session
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
     * Inscrit un message de succes
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
     * Inscrit un message
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
     * Redirection a arriere
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
        $routes = [];
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
                $routes[] = [
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
        return $routes;
    }
}
