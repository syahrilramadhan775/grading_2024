<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageControler;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/list', [UserController::class, 'listener'])->name('usersListener');
Route::get('/projects/list', [ProjectController::class, 'listener'])->name('projectsListener');
Route::get('/tasks/list', [TaskController::class, 'listener'])->name('projectsListener');
