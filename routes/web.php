<?php

use Lune\Http\Response;
use Lune\Routing\Route;

Route::get('/', fn ($request) => Response::text("Lune Framework"));
Route::get('/form', fn ($request) => view("form"));
