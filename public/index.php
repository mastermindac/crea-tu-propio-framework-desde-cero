<?php

use Lune\App;
use Lune\Http\Request;
use Lune\Http\Response;

require_once "../vendor/autoload.php";

$app = App::bootstrap();

$app->router->get('/test/{param}', function (Request $request) {
    return Response::json($request->routeParameters());
});

$app->router->post('/test', function (Request $request) {
    return Response::json($request->data());
});

$app->router->get('/redirect', function (Request $request) {
    return Response::redirect("/test");
});

$app->run();
