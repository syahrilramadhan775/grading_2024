<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::put('/role/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::prefix('projects')->group(function () {
        Route::controller(ProjectController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::put('/{id}/name', 'update');
            Route::delete('/{id}', 'destroy');
        });
     });

    Route::prefix('task')->group(function () {
        Route::controller(TaskController::class)->group(function () {
            Route::get('', 'index');
            Route::put('/{id}/parent/status', 'update');
            Route::post('/parent', 'store');
            Route::post('/sub-parent', 'storeSubParent');
            // Route::post('/child', 'storeChild');
        });
     });
});
