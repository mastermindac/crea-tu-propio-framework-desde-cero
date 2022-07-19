<?php

namespace App\Providers;

use Lune\App;
use Lune\Providers\ServiceProvider;
use Lune\Routing\Route;

class RouteServiceProvider implements ServiceProvider {
    public function registerServices() {
        Route::load(App::$root . "/routes");
    }
}
