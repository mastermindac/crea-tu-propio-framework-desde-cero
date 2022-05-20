<?php

namespace Lune\Routing;

class Route {
    protected string $uri;

    protected \Closure $action;

    protected string $regex;

    protected array $parameters;

    public function __construct(string $uri, \Closure $action) {
        $this->uri = $uri;
        $this->action = $action;
        $this->regex = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9]+)', $uri);
        preg_match_all('/\{([a-zA-Z]+)\}/', $uri, $parameters);
        $this->parameters = $parameters[1];
    }

    public function uri() {
        return $this->uri;
    }

    public function action() {
        return $this->action;
    }

    public function matches(string $uri): bool {
        return preg_match("#^$this->regex/?$#", $uri);
    }

    public function hasParameters(): bool {
        return count($this->parameters) > 0;
    }

    public function parseParameters(string $uri): array {
        preg_match("#^$this->regex$#", $uri, $arguments);

        return array_combine($this->parameters, array_slice($arguments, 1));
    }
}
