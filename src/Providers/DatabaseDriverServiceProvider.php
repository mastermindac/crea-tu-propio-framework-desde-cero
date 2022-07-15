<?php

namespace Lune\Providers;

use Lune\Database\Drivers\DatabaseDriver;
use Lune\Database\Drivers\PdoDriver;

class DatabaseDriverServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("database.connection", "mysql")) {
            "mysql", "pgsql" => singleton(DatabaseDriver::class, PdoDriver::class),
        };
    }
}
