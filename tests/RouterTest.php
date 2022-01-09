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
        $route = $this->router->get('boo', function () {})->name('booName');
        $this->assertTrue($route->getName() == 'booName');
        $this->assertTrue($this->router->generateUrlByName('booName') == '/boo');
    }

    public function testMatchRoute()
    {
        $route = $this->router->get('boo', function () {});
        $this->assertTrue($route->match(new ServerRequest('get', '/boo')));
        $this->assertFalse($route->match(new ServerRequest('get', '/boo2')));
    }

    public function testRunRoute()
    {
        $this->router->get('boo', function () {
            return true;
        });
        $this->assertTrue($this->router->run(new ServerRequest('get', '/boo')));
        try {
            $this->router->run(new ServerRequest('get', '/boo2'));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(RouteNotFoundException::class, $ex);
        }
    }

    public function testRunWithParams()
    {
        $route = $this->router->get('boo/:id', function ($id) {
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
        try {
            $this->router->get('boo', [TestController::class, 'index']);
            $this->router->get('boo', [TestController::class, 'index']);
        } catch (\Exception $ex) {
            $this->assertInstanceOf(RouteAlreadyExistException::class, $ex);
        }
    }

    public function testRouteNotFoundError()
    {
        try {
            $this->router->run(new ServerRequest('get', 'boo'));
        } catch (\Exception $ex) {
            $this->assertInstanceOf(RouteNotFoundException::class, $ex);
        }
    }

    public function testRouteGetNeedParamsError()
    {
        try {
            $this->router->get('boo/:id', function ($id) {
            })->name('boo');
            $this->router->generateUrlByName('boo');
        } catch (\Exception $ex) {
            $this->assertInstanceOf(RouteNeedParamsException::class, $ex);
        }
    }
}
