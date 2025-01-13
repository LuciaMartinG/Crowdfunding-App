<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\RegisterController;

use App\Models\Project;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/project', function () {
    return Project::all();
});

Route::get('/project/{id}', function ($id) {
    return Project::find($id);
});

Route::delete('/project/{id}', function ($id) {
    return Project::destroy($id);
});

Route::post('/project', [ProjectController::class, 'createProject']);

Route::put('/project', [ProjectController::class, 'updateProject']);

Route::post('/register', [RegisterController::class, 'register']);