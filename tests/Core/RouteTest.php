<?php

namespace Tests\Core;

use Core\Router;
use Core\Route;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Tests\Core\Helpers\Route\TestController;

class RouteTest extends TestCase
{
    public Router $route;

    protected function setUp(): void
    {
        $this->route = new Router();
    }

    public function testMatchRoute()
    {
        $request = new ServerRequest('GET', '/go');
        $router = new Route('go', [TestController::class, 'index']);

        $this->assertTrue($router->match($request));
    }

    public function testRunRoute()
    {
        $request = new ServerRequest('GET', '/go');
        $this->route->get('/go', [TestController::class, 'index']);
        

        $this->assertEquals('green', $this->route->run($request));
    }

    public function testRunWithParams()
    {
        $request = new ServerRequest('GET', '/go/5');
        $this->route->get('go/:id', [TestController::class, 'show']);
        

        $this->assertEquals('green5', $this->route->run($request));
    }

    
}
