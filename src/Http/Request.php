<?php

namespace Lune\Http;

use Lune\Routing\Route;
use Lune\Validation\Validator;

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

    protected array $headers = [];

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

    public function headers(string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->headers;
        }

        return $this->headers[strtolower($key)] ?? null;
    }

    public function setHeaders(array $headers): self {
        foreach ($headers as $header => $value) {
            $this->headers[strtolower($header)] = $value;
        }

        return $this;
    }

    /**
     * Get all POST data as key-value or get only specific value by providing
     * a `$key`.
     *
     * @return array|string|null Null if the key doesn't exist, the value of
     * the key if it is present or all the data if no key was provided.
     */
    public function data(?string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
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
     * Get all query params as key-value or get only specific value by providing
     * a `$key`.
     *
     * @return array|string|null Null if the key doesn't exist, the value of
     * the key if it is present or all the query params if no key was provided.
     */
    public function query(?string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->query;
        }

        return $this->query[$key] ?? null;
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
     * Get all route params as key-value or get only specific value by providing
     * a `$key`.
     *
     * @return array|string|null Null if the key doesn't exist, the value of
     * the key if it is present or all the route params if no key was provided.
     */
    public function routeParameters(?string $key = null): array|string|null {
        $parameters = $this->route->parseParameters($this->uri);

        if (is_null($key)) {
            return $parameters;
        }

        return $parameters[$key] ?? null;
    }

    public function validate(array $rules, array $messages = []): array {
        $validator = new Validator($this->data);

        return $validator->validate($rules, $messages);
    }
}
