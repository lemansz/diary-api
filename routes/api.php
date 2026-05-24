<?php

use App\Http\Controllers\API\V1\DiaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::apiResource('diaries', DiaryController::class)->middleware('auth:sanctum')->only('index', 'store', 'show', 'update', 'destroy');

    require __DIR__.'/auth.php';
});


