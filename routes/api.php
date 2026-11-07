<?php

use App\Http\Controllers\Api\OrderApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn (Request $request) => $request->user());
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
});
