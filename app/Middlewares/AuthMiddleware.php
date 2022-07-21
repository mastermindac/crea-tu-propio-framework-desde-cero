<?php

namespace App\Middlewares;

use Closure;
use Lune\Auth\Auth;
use Lune\Http\Middleware;
use Lune\Http\Request;
use Lune\Http\Response;

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if (Auth::isGuest()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
