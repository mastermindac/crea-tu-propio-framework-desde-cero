<?php

use Lune\Auth\Auth;
use Lune\Auth\Authenticatable;

function auth(): ?Authenticatable {
    return Auth::user();
}

function isGuest(): bool {
    return Auth::isGuest();
}
