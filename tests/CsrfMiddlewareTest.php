<?php

namespace Tests;

use Core\App;
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

    public function test()
    {
        $this->router->post('/route', function (array $data) {
            return 'boo';
        });
    }
}
