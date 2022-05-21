<?php

namespace Lune\Http;

use Lune\Server\Server;

/**
 * HTTP request.
 */
class Request {
    /**
     * URI requested by the client.
     *
     * @var string
     */
    protected string $uri;

    /**
     * HTTP method used for this request.
     *
     * @var HttpMethod
     */
    protected HttpMethod $method;

    /**
     * POST data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Query parameters.
     *
     * @var array
     */
    protected array $query;

    /**
     * Create a new request from the given `$server`.
     *
     * @param Server $server
     */
    public function __construct(Server $server) {
        $this->uri = $server->requestUri();
        $this->method = $server->requestMethod();
        $this->data = $server->postData();
        $this->query = $server->queryParams();
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Get the request HTTP method.
     *
     * @return HttpMethod
     */
    public function method(): HttpMethod {
        return $this->method;
    }

    /**
     * Get POST data.
     *
     * @return array
     */
    public function data(): array {
        return $this->data;
    }

    /**
     * Get all query parameters.
     *
     * @return array
     */
    public function query(): array {
        return $this->query;
    }
}
