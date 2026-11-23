<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Одна строка = пять маршрутов: GET /tasks, POST /tasks, GET /tasks/{task}, PUT /tasks/{task}, DELETE /tasks/{task}
Route::apiResource('tasks', TaskController::class);
