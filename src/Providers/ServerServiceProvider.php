<?php

namespace Lune\Providers;

use Lune\Server\PhpNativeServer;
use Lune\Server\Server;

class ServerServiceProvider implements ServiceProvider {
    public function registerServices() {
        singleton(Server::class, PhpNativeServer::class);
    }
}
