<?php

use Illuminate\Support\Facades\Route;

// Kiedy ktoś wejdzie na główny adres strony, zwróć widok "dashboard"
Route::get('/', function () {
    return view('dashboard');
});
