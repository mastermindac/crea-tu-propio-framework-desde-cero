<?php

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use Lune\Auth\Auth;
use Lune\Routing\Route;

Auth::routes();

Route::get('/', fn () => redirect('/home'));
Route::get('/home', [HomeController::class, 'show']);

