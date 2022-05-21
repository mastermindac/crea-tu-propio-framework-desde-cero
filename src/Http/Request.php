<?php

namespace Lune\Http;

use Lune\Routing\Route;

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
     * Route matched by URI.
     *
     * @var Route
     */
    protected Route $route;

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
     * Get the request URI.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Set request URI.
     *
     * @param string $uri
     * @return self
     */
    public function setUri(string $uri): self {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get route matched by the URI of this request.
     *
     * @return Route
     */
    public function route(): Route {
        return $this->route;
    }

    /**
     * Set route for this request.
     *
     * @param Route $route
     * @return self
     */
    public function setRoute(Route $route): self {
        $this->route = $route;
        return $this;
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
     * Set HTTP method.
     *
     * @param HttpMethod $method
     * @return self
     */
    public function setMethod(HttpMethod $method): self {
        $this->method = $method;
        return $this;
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
     * Set POST data.
     *
     * @param array $data
     * @return self
     */
    public function setPostData(array $data): self {
        $this->data = $data;
        return $this;
    }

    /**
     * Get all query parameters.
     *
     * @return array
     */
    public function query(): array {
        return $this->query;
    }

    /**
     * Set query parameters.
     *
     * @param array $query
     * @return self
     */
    public function setQueryParameters(array $query): self {
        $this->query = $query;
        return $this;
    }

    /**
     * Get all route parameters.
     *
     * @return array
     */
    public function routeParameters(): array {
        return $this->route->parseParameters($this->uri);
    }
}
