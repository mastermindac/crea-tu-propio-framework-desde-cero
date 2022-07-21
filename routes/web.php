<?php

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Models\User;
use Lune\Auth\Auth;
use Lune\Http\Response;
use Lune\Routing\Route;

Auth::routes();

Route::get('/', fn () => redirect('/home'));
Route::get('/home', [HomeController::class, 'show']);

Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/create', [ContactController::class, 'create']);
Route::post('/contacts', [ContactController::class, 'store']);
