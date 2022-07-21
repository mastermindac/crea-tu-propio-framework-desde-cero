<?php

namespace App\Controllers;

use Lune\Http\Controller;

class HomeController extends Controller {
    public function show() {
        return view('home');
    }
}
