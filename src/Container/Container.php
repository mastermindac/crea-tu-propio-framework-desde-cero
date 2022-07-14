<?php

namespace Lune\Container;

class Container {
    private static array $instances = [];

    public static function singleton(string $class, string|callable|null $build = null) {
        if (!array_key_exists($class, self::$instances)) {
            match (true) {
                is_null($build) => self::$instances[$class] = new $class(),
                is_string($build) => self::$instances[$class] = new $build(),
                is_callable($build) => self::$instances[$class] = $build(),
            };
        }

        return self::$instances[$class];
    }

    public static function resolve(string $class) {
        return self::$instances[$class] ?? null;
    }
}
