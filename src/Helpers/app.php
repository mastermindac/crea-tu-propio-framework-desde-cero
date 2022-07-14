<?php

use Lune\App;
use Lune\Container\Container;

function app(string $class = App::class) {
    return Container::resolve($class);
}

function singleton(string $class, string|callable|null $build) {
    return Container::singleton($class, $build);
}
