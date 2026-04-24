<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use Encore\BaseKit\Routes\ApiRoutes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

ApiRoutes::register();

Route::apiResource('events', EventController::class)->only(['index', 'show']);
Route::apiResource('registrations', RegistrationController::class)->except(['store']);
Route::apiResource('register', RegistrationController::class)->only(['store']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
    Route::apiResource('registrations', RegistrationController::class);
});
