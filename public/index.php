<?php

require_once "../vendor/autoload.php";

use Lune\HttpNotFoundException;
use Lune\Router;

$router = new Router();

$router->get('/test', function () {
    return "GET OK";
});

$router->post('/test', function () {
    return "POST OK";
});

try {
    $route = $router->resolve($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"]);
    $action = $route->action();
    print($action());
} catch (HttpNotFoundException $e) {
    print("Not found");
    http_response_code(404);
}
