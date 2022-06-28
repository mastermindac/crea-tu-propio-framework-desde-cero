<?php

use Lune\App;
use Lune\Http\Middleware;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Route;

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

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ($request->headers('Authorization') != 'test') {
            return Response::json(["message" => "Not authenticated"])->setStatus(401);
        }

        $response = $next($request);
        $response->setHeader('X-Test-Custom-Header', 'Hola');

        return $response;
    }
}

Route::get('/middlewares', fn (Request $request) => Response::json(["message" => "ok"]))
    ->setMiddlewares([AuthMiddleware::class]);

$app->run();
