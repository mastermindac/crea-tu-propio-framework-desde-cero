<?php

namespace Lune\Tests\Http;

use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Routing\Route;
use Lune\Server\Server;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
    public function test_request_returns_data_obtained_from_server_correctly() {
        $uri = '/test/route';
        $queryParams = ['a' => 1, 'b' => 2, 'test' => 'foo'];
        $postData = ['post' => 'test', 'foo' => 'bar'];

        $request = (new Request())
            ->setUri($uri)
            ->setMethod(HttpMethod::POST)
            ->setQueryParameters($queryParams)
            ->setPostData($postData);

        $this->assertEquals($uri, $request->uri());
        $this->assertEquals($queryParams, $request->query());
        $this->assertEquals($postData, $request->data());
        $this->assertEquals(HttpMethod::POST, $request->method());
    }

    public function test_data_returns_value_if_key_is_given() {
        $data = ['test' => 5, 'foo' => 1, 'bar' => 2];
        $request = (new Request())->setPostData($data);

        $this->assertEquals($request->data('test'), 5);
        $this->assertEquals($request->data('foo'), 1);
        $this->assertNull($request->data("doesn't exist"));
    }

    public function test_query_returns_value_if_key_is_given() {
        $data = ['test' => 5, 'foo' => 1, 'bar' => 2];
        $request = (new Request())->setQueryParameters($data);

        $this->assertEquals($request->query('test'), 5);
        $this->assertEquals($request->query('foo'), 1);
        $this->assertNull($request->query("doesn't exist"));
    }

    public function test_route_parameters_returns_value_if_key_is_given() {
        $route = new Route('/test/{param}/foo/{bar}', fn () => "test");
        $request = (new Request())
            ->setRoute($route)
            ->setUri('/test/1/foo/2');

        $this->assertEquals($request->routeParameters('param'), 1);
        $this->assertEquals($request->routeParameters('bar'), 2);
        $this->assertNull($request->routeParameters("doesn't exist"));
    }
}
