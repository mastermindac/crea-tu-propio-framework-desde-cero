<?php

use App\Models\User;
use Lune\Crypto\Hasher;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Route;

Route::get('/', fn ($request) => Response::text(auth()->name));
Route::get('/form', fn ($request) => view("form"));

Route::get('/register', fn ($request) => view("auth/register"));
Route::post('/register', function (Request $request) {
    $data = $request->validate([
        "email" => ["required", "email"],
        "name" => "required",
        "password" => "required",
        "confirm_password" => "required",
    ]);

    if ($data["password"] !== $data["confirm_password"]) {
        return back()->withErrors([
            "confirm_password" => ["confirm_password" => "Passwords do not match"]
        ]);
    }

    $data["password"] = app(Hasher::class)->hash($data["password"]);

    User::create($data);

    $user = User::firstWhere('email', $data['email']);

    $user->login();

    return redirect('/');
});
