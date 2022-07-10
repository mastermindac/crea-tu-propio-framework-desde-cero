<?php

use Lune\Session\Session;

function session(): Session {
    return app()->session;
}

function error(string $field) {
    $errors = session()->get('_errors', [])[$field] ?? [];

    $keys = array_keys($errors);

    if (count($keys) > 0) {
        return $errors[$keys[0]];
    }

    return null;
}

function old(string $field) {
    return session()->get('_old', [])[$field] ?? null;
}
