<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightConditionsController;

// Nasz endpoint dla raportu pogodowego
Route::get('/conditions', [FlightConditionsController::class, 'check']);
