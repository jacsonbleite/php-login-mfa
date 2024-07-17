<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use App\Http\Router;
use App\Http\Request;
use App\Http\Response;

class RouterTest extends TestCase
{
    public function testAddRoute()
    {
        $router = new Router('http://localhost/php-login-mfa');

        $router->get('/test', [
            function () {
                return new Response(200, ['message' => 'Estou tesando'], 'application/json');
            }
        ]);

        $reflection = new \ReflectionClass($router);
        $routesProperty = $reflection->getProperty('routes');
        $routesProperty->setAccessible(true);
        $routes = $routesProperty->getValue($router);

        $this->assertArrayHasKey('/^\/test$/', $routes);
        $this->assertArrayHasKey('GET', $routes['/^\/test$/']);
        $this->assertArrayHasKey('controller', $routes['/^\/test$/']['GET']);
    }


    public function testGetUri()
    {
        $router = new Router('http://localhost/php-login-mfa/test');
        $reflection = new \ReflectionClass($router);
        $method = $reflection->getMethod('getUri');
        $method->setAccessible(true);

        $request = new Request($router);
        $reflectionRequest = new \ReflectionClass($request);
        $uriProperty = $reflectionRequest->getProperty('uri');
        $uriProperty->setAccessible(true);
        $uriProperty->setValue($request, '/test');

        $reflectionRouter = new \ReflectionClass($router);
        $requestProperty = $reflectionRouter->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($router, $request);

        $uri = $method->invoke($router);
        $this->assertEquals('/test', $uri);
    }

    public function testGetRoute()
    {
        $router = new Router('http://localhost/php-login-mfa');

        $router->get('/test/{id}', [
            function ($id) {
                return new Response(200, 'ID: ' . $id);
            }
        ]);

        $reflection = new \ReflectionClass($router);
        $method = $reflection->getMethod('getRoute');
        $method->setAccessible(true);

        $request = new Request($router);
        $reflectionRequest = new \ReflectionClass($request);
        $uriProperty = $reflectionRequest->getProperty('uri');
        $uriProperty->setAccessible(true);
        $uriProperty->setValue($request, '/test/123');

        $methodProperty = $reflectionRequest->getProperty('method');
        $methodProperty->setAccessible(true);
        $methodProperty->setValue($request, 'GET');

        $reflectionRouter = new \ReflectionClass($router);
        $requestProperty = $reflectionRouter->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($router, $request);

        $route = $method->invoke($router);
        $this->assertArrayHasKey('controller', $route);
        $this->assertArrayHasKey('variables', $route);
        $this->assertArrayHasKey('id', $route['variables']);
        $this->assertEquals('123', $route['variables']['id']);
    }

    public function testRun()
    {
        $router = new Router('http://localhost/php-login-mfa');

        $router->get('/test', [
            function () {
                return new Response(200, 'OK');
            }
        ]);

        $request = new Request($router);
        $reflectionRequest = new \ReflectionClass($request);
        $uriProperty = $reflectionRequest->getProperty('uri');
        $uriProperty->setAccessible(true);
        $uriProperty->setValue($request, '/test');

        $methodProperty = $reflectionRequest->getProperty('method');
        $methodProperty->setAccessible(true);
        $methodProperty->setValue($request, 'GET');

        $reflectionRouter = new \ReflectionClass($router);
        $requestProperty = $reflectionRouter->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($router, $request);

        $response = $router->run();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getHttpCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function testRunWithNotFound()
    {
        $router = new Router('http://localhost/php-login-mfa');

        $request = new Request($router);
        $reflectionRequest = new \ReflectionClass($request);
        $uriProperty = $reflectionRequest->getProperty('uri');
        $uriProperty->setAccessible(true);
        $uriProperty->setValue($request, '/notfound');

        $methodProperty = $reflectionRequest->getProperty('method');
        $methodProperty->setAccessible(true);
        $methodProperty->setValue($request, 'GET');

        $reflectionRouter = new \ReflectionClass($router);
        $requestProperty = $reflectionRouter->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($router, $request);

        $response = $router->run();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getHttpCode());
        $this->assertEquals('URL não encontrada', $response->getContent());
    }

    public function testRunWithMethodNotAllowed()
    {
        $router = new Router('http://localhost/php-login-mfa');

        $router->post('/test', [
            function () {
                return new Response(200, 'OK');
            }
        ]);

        $request = new Request($router);
        $reflectionRequest = new \ReflectionClass($request);
        $uriProperty = $reflectionRequest->getProperty('uri');
        $uriProperty->setAccessible(true);
        $uriProperty->setValue($request, '/test');

        $methodProperty = $reflectionRequest->getProperty('method');
        $methodProperty->setAccessible(true);
        $methodProperty->setValue($request, 'GET');

        $reflectionRouter = new \ReflectionClass($router);
        $requestProperty = $reflectionRouter->getProperty('request');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($router, $request);

        $response = $router->run();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(405, $response->getHttpCode());
        $this->assertEquals('Método não permitido', $response->getContent());
    }
}
