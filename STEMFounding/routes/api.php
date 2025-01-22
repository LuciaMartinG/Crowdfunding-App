<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\User;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/project', function () {
    return Project::all();
});

Route::get('/project/{id}', function ($id) {
    return Project::find($id);
});

Route::delete('/project/{id}', function ($id) {
    return Project::destroy($id);
});

Route::post('/project', [ProjectController::class, 'createProjectPostman']);

Route::put('/project', [ProjectController::class, 'updateProject']);



// USER
Route::get('/user', function () {
    return User::all();
});

Route::get('/user/{id}', function ($id) {
    return User::find($id);
});

Route::delete('/user/{id}', function ($id) {
    return User::destroy($id);
});

Route::post('/user', [RegisterController::class, 'createUser']);

Route::put('/user', [UserController::class, 'updateUser']);