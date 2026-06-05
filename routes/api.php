<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightConditionsController;

Route::get('/conditions', [FlightConditionsController::class, 'check']);
