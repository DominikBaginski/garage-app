<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GarageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/garages', GarageController::class);

Route::apiResource('/bookings', BookingController::class);

Route::get('/dashboard', [DashboardController::class, 'stats']);
