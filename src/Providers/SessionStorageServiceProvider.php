<?php

namespace Lune\Providers;

use Lune\Session\PhpNativeSessionStorage;
use Lune\Session\SessionStorage;

class SessionStorageServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("session.storage", "native")) {
            "native" => singleton(SessionStorage::class, PhpNativeSessionStorage::class),
        };
    }
}
