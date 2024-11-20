<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/send', [UserController::class, 'send']);

Route::prefix('v1')->group(function(){
    Route::prefix('user')->group(function(){
        Route::post('', [UserController::class, 'storeRole']);
        Route::put('/{id}/project', [UserController::class, 'update']);
    });

    Route::prefix('/project')->group(function(){
        Route::get('/user', [ProjectController::class, 'index']);
        Route::post('', [ProjectController::class, 'store']);
        Route::get('', [ProjectController::class, 'update']);
    });

    Route::get('user/project', [UserController::class, 'userProjectTask']);
});
