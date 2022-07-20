<?php

namespace App\Controllers\Auth;

use App\Models\User;
use Lune\Crypto\Hasher;
use Lune\Http\Controller;
use Lune\Http\Request;

class RegisterController extends Controller {
    public function create(Request $request) {
        return view('auth/register');
    }

    public function store(Request $request) {
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
    
        $user = User::create($data);

        $user->login();
    
        return redirect('/');
    }
}
