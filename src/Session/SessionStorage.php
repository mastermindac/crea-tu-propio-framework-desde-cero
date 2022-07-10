<?php

namespace Lune\Session;

interface SessionStorage {
    public function start();

    public function save();

    public function id(): string;

    public function get(string $key, $default = null);

    public function set(string $key, mixed $value);

    public function has(string $key): bool;

    public function remove(string $key);

    public function destroy();
}
