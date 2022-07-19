<?php

namespace Lune\Auth;

use Lune\Auth\Authenticators\Authenticator;

class Auth {
    public static function user(): ?Authenticatable {
        return app(Authenticator::class)->resolve();
    }

    public static function isGuest(): bool {
        return is_null(self::user());
    }
}
