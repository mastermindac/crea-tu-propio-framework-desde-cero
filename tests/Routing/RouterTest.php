<?php

namespace Lune\Tests\Routing;

use Closure;
use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    private function createMockRequest(string $uri, HttpMethod $method): Request {
        return (new Request())
            ->setUri($uri)
            ->setMethod($method);
    }

    public function test_resolve_basic_route_with_callback_action() {
        $uri = '/test';
        $action = fn () => "test";
        $router = new Router();
        $router->get($uri, $action);

        $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
        $this->assertEquals($uri, $route->uri());
        $this->assertEquals($action, $route->action());
    }

    public function test_resolve_multiple_basic_routes_with_callback_action() {
        $routes = [
            '/test' => fn () => "test",
            '/foo' => fn () => "foo",
            '/bar' => fn () => "bar",
            '/long/nested/route' => fn () => "long nested route",
        ];

        $router = new Router();

        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }

        foreach ($routes as $uri => $action) {
            $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
            $this->assertEquals($uri, $route->uri());
            $this->assertEquals($action, $route->action());
        }
    }

    public function test_resolve_multiple_basic_routes_with_callback_action_for_different_http_methods() {
        $routes = [
            [HttpMethod::GET, "/test", fn () => "get"],
            [HttpMethod::POST, "/test", fn () => "post"],
            [HttpMethod::PUT, "/test", fn () => "put"],
            [HttpMethod::PATCH, "/test", fn () => "patch"],
            [HttpMethod::DELETE, "/test", fn () => "delete"],

            [HttpMethod::GET, "/random/get", fn () => "get"],
            [HttpMethod::POST, "/random/nested/post", fn () => "post"],
            [HttpMethod::PUT, "/put/random/route", fn () => "put"],
            [HttpMethod::PATCH, "/some/patch/route", fn () => "patch"],
            [HttpMethod::DELETE, "/d", fn () => "delete"],
        ];

        $router = new Router();

        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method->value)}($uri, $action);
        }

        foreach ($routes as [$method, $uri, $action]) {
            $route = $router->resolveRoute($this->createMockRequest($uri, $method));
            $this->assertEquals($uri, $route->uri());
            $this->assertEquals($action, $route->action());
        }
    }

    public function test_run_middlewares() {
        $middleware1 = new class () {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('x-test-one', 'one');

                return $response;
            }
        };

        $middleware2 = new class () {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('x-test-two', 'two');

                return $response;
            }
        };

        $router = new Router();
        $uri = '/test';
        $expectedResponse = Response::text("test");
        $router->get($uri, fn ($request) => $expectedResponse)
            ->setMiddlewares([$middleware1::class, $middleware2::class]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET));

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($response->headers('x-test-one'), 'one');
        $this->assertEquals($response->headers('x-test-two'), 'two');
    }

    public function test_middleware_stack_can_be_stopped() {
        $stopMiddleware = new class () {
            public function handle(Request $request, Closure $next): Response {
                return Response::text("Stopped");
            }
        };

        $middleware2 = new class () {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('x-test-two', 'two');

                return $response;
            }
        };

        $router = new Router();
        $uri = '/test';
        $unreachableResponse = Response::text("Unreachable");
        $router->get($uri, fn ($request) => $unreachableResponse)
            ->setMiddlewares([$stopMiddleware, $middleware2]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET));

        $this->assertEquals("Stopped", $response->content());
        $this->assertNull($response->headers('x-test-two'));
    }
}
