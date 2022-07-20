<?php

use App\Controllers\Auth\RegisterController;
use App\Models\User;
use Lune\Crypto\Hasher;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Route;

Route::get('/', function ($request) {
    if (isGuest()) {
        return Response::text('Guest');
    }

    return Response::text(auth()->name);
});

Route::get('/form', fn ($request) => view("form"));

Route::get('/register', [RegisterController::class, 'create']);

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', fn ($request) => view('auth/login'));

Route::post('/login', function (Request $request) {
    $data = $request->validate([
        "email" => ["required", "email"],
        "password" => "required",
    ]);

    $user = User::firstWhere('email', $data['email']);

    if (is_null($user) || !app(Hasher::class)->verify($data["password"], $user->password)) {
        return back()->withErrors([
            'email' => ['email' => 'Credentials do not match']
        ]);
    }

    $user->login();

    return redirect('/');
});

Route::get('/logout', function ($request) {
    auth()->logout();
    return redirect('/');
});
