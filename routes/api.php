<?php

use App\Http\Controllers\EventController;
use Encore\BaseKit\Routes\ApiRoutes;
use Illuminate\Support\Facades\Route;

ApiRoutes::register();

Route::apiResource('events', EventController::class)->only(['index', 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
});
