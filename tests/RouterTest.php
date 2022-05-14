<?php

namespace Lune\Tests;

use Lune\HttpMethod;
use Lune\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    public function test_resolve_basic_route_with_callback_action() {
        $uri = '/test';
        $action = fn () => "test";
        $router = new Router();
        $router->get($uri, $action);

        $route = $router->resolve($uri, HttpMethod::GET->value);
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
            $route = $router->resolve($uri, HttpMethod::GET->value);
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
            $route = $router->resolve($uri, $method->value);
            $this->assertEquals($uri, $route->uri());
            $this->assertEquals($action, $route->action());
        }
    }
}
