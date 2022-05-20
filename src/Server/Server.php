<?php

namespace Lune\Server;

use Lune\Http\HttpMethod;
use Lune\Http\Response;

interface Server {
    public function requestUri(): string;
    public function requestMethod(): HttpMethod;
    public function postData(): array;
    public function queryParams(): array;
    public function sendResponse(Response $response);
}
