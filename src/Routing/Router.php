<?php

namespace Lune\Routing;

use Closure;
use Lune\Http\HttpMethod;
use Lune\Http\HttpNotFoundException;
use Lune\Http\Request;
use Lune\Http\Response;

/**
 * HTTP router.
 */
class Router {
    /**
     * HTTP routes.
     *
     * @var array<string, Route[]>
     */
    protected array $routes = [];

    /**
     * Create a new router.
     */
    public function __construct() {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    /**
     * Resolve the route of the `$request`.
     *
     * @param Request $request
     * @return Route
     * @throws HttpNotFoundException when route is not found
     */
    public function resolveRoute(Request $request): Route {
        foreach ($this->routes[$request->method()->value] as $route) {
            if ($route->matches($request->uri())) {
                return $route;
            }
        }

        throw new HttpNotFoundException();
    }

    public function resolve(Request $request): Response {
        $route = $this->resolveRoute($request);
        $request->setRoute($route);
        $action = $route->action();

        if (is_array($action)) {
            $controller = new $action[0]();
            $action[0] = $controller;
        }

        return $this->runMiddlewares(
            $request,
            $route->middlewares(),
            fn () => call_user_func($action, $request)
        );
    }

    protected function runMiddlewares(Request $request, array $middlewares, $target): Response {
        if (count($middlewares) == 0) {
            return $target();
        }

        return $middlewares[0]->handle(
            $request,
            fn ($request) => $this->runMiddlewares($request, array_slice($middlewares, 1), $target)
        );
    }

    /**
     * Register a new route with the given `$method` and `$uri`.
     *
     * @param HttpMethod $method
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    protected function registerRoute(HttpMethod $method, string $uri, Closure|array $action): Route {
        $route = new Route($uri, $action);
        $this->routes[$method->value][] = $route;

        return $route;
    }

    /**
     * Register a GET route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param \Closure $action
     * @return Route
     */
    public function get(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::GET, $uri, $action);
    }

    /**
     * Register a POST route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function post(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::POST, $uri, $action);
    }

    /**
     * Register a PUT route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function put(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::PUT, $uri, $action);
    }

    /**
     * Register a PATCH route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function patch(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::PATCH, $uri, $action);
    }

    /**
     * Register a DELETE route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function delete(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::DELETE, $uri, $action);
    }
}
