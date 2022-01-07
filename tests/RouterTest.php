<?php

namespace Tests;

use Core\Exceptions\Router\RouteAlreadyExistException;
use Core\Exceptions\Router\RouteNeedParamsException;
use Core\Exceptions\Router\RouteNotFoundException;
use Core\Router;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Tests\App\Route\TestController;

class RouterTest extends TestCase
{
    public Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testNameRoute()
    {
        $route = $this->router->get('boo', function () {
            return;
        })->name('booName');
        $this->assertTrue($route->getName() == 'booName');
        $this->assertTrue($this->router->generateUrlByName('booName') == '/boo');
    }

    public function testMatchRoute()
    {
        $route = $this->router->get('boo', function () {
            return;
        });
        $this->assertTrue($route->match(new ServerRequest('get', '/boo')));
        $this->assertFalse($route->match(new ServerRequest('get', '/boo2')));
    }

    public function testRunRoute()
    {
        $this->router->get('boo', function () {
            return true;
        });
        $this->assertTrue($this->router->run(new ServerRequest('get', '/boo')));
        $this->expectException(RouteNotFoundException::class);
        $this->assertTrue($this->router->run(new ServerRequest('get', '/boo2')));
    }

    public function testRunWithParams()
    {
        $route = $this->router->get('boo/:id', function ($id) {
            return;
        });
        $this->assertTrue($route->match(new ServerRequest('get', '/boo/1')));
        $this->assertFalse($route->match(new ServerRequest('get', '/boo')));
    }

    public function testRouteToController()
    {
        $this->router->get('boo', [TestController::class, 'index']);
        $this->router->get('boo/:id', [TestController::class, 'show']);
        $this->assertEquals('green', $this->router->run(new ServerRequest('get', 'boo')));
        $this->assertEquals('red 12', $this->router->run(new ServerRequest('get', 'boo/12')));
    }

    public function testPostRouteToController()
    {
        $this->router->post('boo', [TestController::class, 'post']);
        $client = new ServerRequest('post', 'boo');
        $data = ['name' => 'micky'];
        $this->assertTrue($this->router->run($client->withParsedBody($data)) === 'micky');
    }

    public function testRouteAlreadyExistError()
    {
        $this->router->get('boo', [TestController::class, 'index']);
        $this->expectException(RouteAlreadyExistException::class);
        $this->router->get('boo', [TestController::class, 'index']);
    }

    public function testRouteNotFoundError()
    {
        $this->expectException(RouteNotFoundException::class);
        $this->router->run(new ServerRequest('get', 'boo'));
    }

    public function testRouteGetNeedParamsError()
    {
        $this->router->get('boo/:id', function ($id) {
            return;
        })->name('boo');
        $this->expectException(RouteNeedParamsException::class);
        $this->router->generateUrlByName('boo');
    }
}
