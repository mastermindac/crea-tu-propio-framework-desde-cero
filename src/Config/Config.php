<?php

namespace Lune\Config;

class Config {
    private static array $config = [];

    public static function load(string $path) {
        foreach (glob("$path/*.php") as $config) {
            $key = explode(".", basename($config))[0];
            $values = require_once $config;
            self::$config[$key] = $values;
        }
    }

    public static function get(string $configuration, $default = null) {
        $keys = explode(".", $configuration);
        $finalKey = array_pop($keys);
        $array = self::$config;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return $default;
            }
            $array = $array[$key];
        }

        return $array[$finalKey] ?? $default;
    }
}
