<?php

namespace Tests;

use Core\App;
use Core\Exceptions\CsrfMiddlewareException;
use Core\Facades\Session;
use Core\Router;
use Core\Security\CsrfMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class CsrfMiddlewareTest extends TestCase
{

    /**
     * @var Router
     */
    private Router $router;

    public function setUp(): void
    {
        App::ConfigInit();
        $this->router = new Router();
    }

    public function testLetGetRequestPass()
    {
        $this->router->get('/passed', function () {
            return 'boo';
        });
        $this->assertEquals('boo', $this->router->run(new ServerRequest('get', '/passed')));
    }

    public function testBlockPostRequestWithoutCsrf()
    {
        $this->router->post('/route', function (array $data) {
            return 'boo';
        });
        $request = new ServerRequest('post', '/route');
        try {
            $this->router->run($request->withParsedBody(['go' => false]));
        }catch (\Exception $ex){
            $this->assertInstanceOf(CsrfMiddlewareException::class, $ex);
        }
    }

    public function testBlockPostRequestWithInvalidCsrf()
    {
        $this->router->post('/route', function (array $data) {
            return 'boo';
        });
        $request = new ServerRequest('post', '/route');
        try {
            $this->router->run($request->withParsedBody(['go' => false, '_csrf' => 'azeaze']));
        }catch (\Exception $ex){
            $this->assertInstanceOf(CsrfMiddlewareException::class, $ex);
        }
    }

    public function testPassPostRequestWithCsrf()
    {
        $this->router->post('/passed', function () {
            return 'boo';
        });
        $request = new ServerRequest('post', '/passed');
        $token = (new CsrfMiddleware())->generateToken();
        $this->assertEquals('boo', $this->router->run($request->withParsedBody(['_csrf' => $token])));
    }

    public function testLetPassTokenOnce()
    {
        $this->router->post('/passed', function () {
            return 'boo';
        });
        $request = new ServerRequest('post', '/passed');
        $token = (new CsrfMiddleware())->generateToken();
        $this->assertEquals('boo', $this->router->run($request->withParsedBody(['_csrf' => $token])));
        try {
            $this->expectException($this->router->run($request->withParsedBody(['_csrf' => $token])));
        }catch (\Exception $ex){
            $this->assertInstanceOf(CsrfMiddlewareException::class, $ex);
        }
    }

    public function testLimitTheTokenNumber()
    {
        $token = "";
        for ($i = 0; $i < 100; ++$i){
            $token = (new CsrfMiddleware())->generateToken();
        }
        $this->assertCount(50, Session::get('csrf'));
        $this->assertEquals($token, Session::get('csrf')[49]);
    }
}
