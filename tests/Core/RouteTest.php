<?php

namespace Tests\Core;

use Core\Route;
use Core\Router;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;

class RouteTest extends TestCase
{
    public Route $route;

    protected function setUp(): void
    {
        $this->route = new Route();
    }

    public function testMatchRoute()
    {
        $request = new ServerRequest('GET', '/go');
        $router = new Router('/go', function(){},'GET');

        $this->assertTrue($router->match($request));
    }

    public function testRunRoute()
    {
        $request = new ServerRequest('GET', '/go');
        $this->route->get('/go', function(){return 'go';});
        

        $this->assertEquals('go', $this->route->run($request));
    }

    public function testRunWithParams()
    {
        $request = new ServerRequest('GET', '/go/5');
        $this->route->get('/go/:id', function($id){return $id;});
        

        $this->assertEquals('5', $this->route->run($request));
    }

    
}
