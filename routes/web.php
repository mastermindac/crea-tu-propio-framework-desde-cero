<?php

use App\Controllers\Auth\RegisterController;
use App\Models\User;
use Lune\Crypto\Hasher;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Route;

Route::get('/', function () {
    if (isGuest()) {
        return Response::text('Guest');
    }

    return Response::text(auth()->name);
});

Route::get('/form', fn () => view("form"));
Route::get('/user/{user}', fn (User $user) => json($user->toArray()));
Route::get('/route/{param}', fn (string $param) => json(["param" => $param]));

Route::get('/register', [RegisterController::class, 'create']);

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', fn () => view('auth/login'));

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

Route::get('/logout', function () {
    auth()->logout();
    return redirect('/');
});
