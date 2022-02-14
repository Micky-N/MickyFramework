<?php

namespace Tests;


use Core\App;
use Core\Exceptions\Router\RouteMiddlewareException;
use Core\Middleware;
use Core\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tests\App\Middleware\BlockedMiddleware;
use Tests\App\Middleware\TestMiddleware;
use Tests\App\Middleware\PassedMiddleware;
use Tests\App\Middleware\ConditionMiddleware;

class MiddlewareTest extends TestCase
{
    /**
     * @var Router
     */
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
        App::setConfig('app', ['csrf' => false]);
        App::setRouteMiddleware('passed', PassedMiddleware::class);
        App::setRouteMiddleware('blocked', BlockedMiddleware::class);
        App::setRouteMiddleware('condition', ConditionMiddleware::class);
    }

    public function testMultipleMiddleware()
    {
        $this->assertTrue(Middleware::run(PassedMiddleware::class));
        $this->assertEquals(1, Middleware::getInstance()->index);
        $this->assertTrue(Middleware::run([PassedMiddleware::class, TestMiddleware::class]));
        $this->assertEquals(2, Middleware::getInstance()->index);
    }

    public function testPriorityAndStopMiddleware()
    {
        $this->assertFalse(Middleware::run([PassedMiddleware::class, BlockedMiddleware::class, TestMiddleware::class]));
        // GET THE LAST RUNNING MIDDLEWARE
        $this->assertEquals(BlockedMiddleware::class, Middleware::getInstance()->getMiddlewares(Middleware::getInstance()->index - 1));
        $this->assertEquals(2, Middleware::getInstance()->index);
    }

    public function testPassedRouteMiddleware()
    {
        $this->router->get('/passed', function () {
            return 'boo';
        }, '', 'passed');
        $this->assertEquals('boo', $this->router->run(new ServerRequest('get', '/passed')));

        $this->router->get('block', function () {}, '', 'blocked');
        $this->assertFalse($this->router->run(new ServerRequest('get', 'block')));
    }

    public function testPriorityRouteMiddleware()
    {
        $this->router->get('/route', function () {}, '', ['passed', 'blocked', 'condition']);
        $this->assertFalse($this->router->run(new ServerRequest('get', '/route')));
    }

    public function testNotFoundAliasRouteMiddleware()
    {
        $this->router->get('/route', function () {}, '', 'pass');
        try {
            $this->router->run(new ServerRequest('get', '/route'));
        }catch (\Exception $ex){
            $this->assertInstanceOf(RouteMiddlewareException::class, $ex);
        }
    }
}
