<?php

namespace Core;

use Core\Facades\Session;
use Exception;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Route
{
    /**
     * @var Router[]
     */
    private array $routes;

    public function __construct($routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * @param string $path
     * @param Callable|array $action
     * @return Router
     */
    public function get(string $path, $action): Router
    {
        $route = new Router($path, $action);
        $this->routes['GET'][] = $route;
        return $route;
    }

    /**
     * @param string $path
     * @param array $action
     * @return Router
     */
    public function post(string $path, array $action): Router
    {
        $route = new Router($path, $action);
        $this->routes['POST'][] = $route;
        return $route;
    }

    /**
     * @return array
     */
    public function routesByName(): array
    {
        $routes = [];
        foreach ($this->routes as $request => $routers) {
            foreach ($this->routes[$request] as $router) {
                $routes[$router->name] = $router;
            }
        }
        return $routes;
    }

    /**
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
        if (strpos($namespace, '.')) {
            $namespaces = explode('.', $namespace);
            $namespace = end($namespaces);
            foreach ($namespaces as $key => $name) {
                if ($key < count($namespaces) - 1) {
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
            if (!empty($only) && in_array($key, $only)) {
                $router = call_user_func_array(
                    [$this, $crudAction['request']],
                    [$crudAction['path'], $crudAction['action']]
                );
                call_user_func_array([$router, 'name'], [$crudAction['name']]);
            } elseif (empty($only)) {
                $router = call_user_func_array(
                    [$this, $crudAction['request']],
                    [$crudAction['path'], $crudAction['action']]
                );
                call_user_func_array([$router, 'name'], [$crudAction['name']]);
            }
        }
    }

    /**
     * @param string $path
     * @return string
     * @throws Exception
     */
    public function routeNeedParams(string $path): string
    {
        $needed = [];
        $pathArray = explode('/', $path);
        $needed = array_map(function ($p) use ($needed) {
            if (strpos($p, ':') === 0) {
                $p = str_replace(':', '', $p);
                return $p;
            }
        }, $pathArray);
        $needed = array_filter($needed);
        if (!empty($needed)) {
            throw new Exception('need params');
        }
        return $path;
    }

    /**
     * @param string $routeName
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function generateUrlByName(string $routeName, array $params = []): string
    {
        $path = '';
        $err = 0;
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $router) {
                if ($router->name === $routeName) {
                    $path = $router->path;
                    if (!empty($params)) {
                        $path = explode('/', $path);
                        foreach ($path as $key => $value) {
                            if (strpos($value, ':') === 0) {
                                $value = str_replace(':', '', $value);
                                if (isset($params[$value])) {
                                    $path[$key] = $params[$value];
                                } else {
                                    throw new Exception(
                                        "Le paramètre '$value' est requie",
                                        13
                                    );
                                }
                            }
                        }
                        $path = implode('/', $path);
                    }
                } else {
                    $err += 1;
                }
            }
        }
        if ($err == count((array)$routes)) {
            throw new Exception("La route '$routeName' n'existe pas dans la méthode $method.", 13);
        }
        return $this->routeNeedParams($path);
    }

    /**
     * @param string $route
     *
     * @return bool|string
     */
    public function currentRoute(string $route = '')
    {
        $currentRoute = ServerRequest::fromGlobals()
            ->getUri()
            ->getPath();
        if ($route) {
            return $currentRoute === $route;
        }
        return $currentRoute;
    }

    /**
     * @param string $route
     *
     * @return bool
     */
    public function namespaceRoute(string $route = '')
    {
        $currentRoute = ServerRequest::fromGlobals()
            ->getUri()
            ->getPath();
        $currentRouteArray = explode('/', trim($currentRoute, '/'));
        if ($route) {
            return $currentRouteArray[0] === $route;
        }
        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     * @throws Exception
     */
    public function run(ServerRequestInterface $request)
    {
        foreach ($this->routes[$request->getMethod()] as $route) {
            if ($route->match($request)) {
                return $route->execute($request);
            }
        }
        throw new Exception("La route {$request->getUri()->getPath()} n'existe pas.");
    }

    /**
     * @param string $url
     */
    public function redirect(string $url): void
    {
        header("Location: $url");
    }

    /**
     * @param string $name
     * @return Route
     * @throws Exception
     */
    public function redirectName(string $name): self
    {
        $path = $this->generateUrlByName($name);
        header("Location: $path");
        return $this;
    }

    public function withError(array $errors): self
    {
        foreach ($errors as $name => $message) {
            Session::setFlashMessageOnType(Session::getConstant('FLASH_ERROR'), $name, $message);
        }
        return $this;
    }

    public function withSuccess(array $success): self
    {
        foreach ($success as $name => $message) {
            Session::setFlashMessageOnType(Session::getConstant('FLASH_SUCCESS'), $name, $message);
        }
        return $this;
    }

    public function with(array $messages): self
    {
        foreach ($messages as $name => $message) {
            Session::setFlashMessageOnType(Session::getConstant('FLASH_MESSAGE'), $name, $message);
        }
        return $this;
    }

    /**
     *
     */
    public function back(): self
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        includeAll('routes');
        $routes = [];
        $namespace = '/';
        $currentPath = "\t";
        foreach ($this->routes as $method => $routers) {
            foreach ($routers as $router) {
                $paths = explode('/', trim($router->path, '/'));
                if (in_array($namespace, $paths)) {
                    unset($paths[0]);
                    $currentPath = "\t/" . join('/', $paths);
                } else {
                    $namespace = $paths[0];
                    $currentPath = $router->path;
                }
                $routes[] = [
                    $method,
                    $currentPath,
                    str_replace(
                        'App\\Http\\Controllers\\',
                        '',
                        $router->action[0]
                    ),
                    $router->action[1],
                    $router->name,
                    $router->middleware,
                ];
            }
        }
        return $routes;
    }
}
