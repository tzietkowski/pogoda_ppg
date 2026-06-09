<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightConditionsController;
use App\Http\Controllers\SpotController;

Route::get('/conditions/{spot:metar_code?}', [FlightConditionsController::class, 'check']);
Route::apiResource('spots', SpotController::class);
