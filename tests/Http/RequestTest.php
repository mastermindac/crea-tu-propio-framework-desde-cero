<?php

namespace Lune\Tests\Http;

use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Server\Server;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
    public function test_request_returns_data_obtained_from_server_correctly() {
        $uri = '/test/route';
        $queryParams = ['a' => 1, 'b' => 2, 'test' => 'foo'];
        $postData = ['post' => 'test', 'foo' => 'bar'];

        $server = $this->getMockBuilder(Server::class)->getMock();
        $server->method("requestUri")->willReturn($uri);
        $server->method("requestMethod")->willReturn(HttpMethod::POST);
        $server->method("queryParams")->willReturn($queryParams);
        $server->method("postData")->willReturn($postData);

        $request = new Request($server);

        $this->assertEquals($uri, $request->uri());
        $this->assertEquals($queryParams, $request->query());
        $this->assertEquals($postData, $request->data());
        $this->assertEquals(HttpMethod::POST, $request->method());
    }
}
