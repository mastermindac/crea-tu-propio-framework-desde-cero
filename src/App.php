<?php

namespace Lune;

use Lune\Http\HttpNotFoundException;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;
use Lune\Server\PhpNativeServer;
use Lune\Server\Server;
use Lune\Validation\Exceptions\ValidationException;
use Lune\View\LuneEngine;
use Lune\View\View;
use Throwable;

class App {
    public Router $router;

    public Request $request;

    public Server $server;

    public View $view;

    public static function bootstrap() {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->view = new LuneEngine(__DIR__ . "/../views");

        return $app;
    }

    public function run() {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text("Not found")->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(json($e->errors())->setStatus(422));
        } catch (Throwable $e) {
            $response = json([
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ]);

            $this->abort($response);
        }
    }

    public function abort(Response $response) {
        $this->server->sendResponse($response);
    }
}
