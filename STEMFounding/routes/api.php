<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvestmentController;
use App\Models\Project;
use App\Models\User;
use App\Models\Investment;
use App\Models\ProjectUpdate;


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

Route::put('/updateProject', [ProjectController::class, 'updateProject']);



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

Route::put('/updateUserBalance', [UserController::class, 'updateBalance']);

Route::get('/userProjects/{id}', [ProjectController::class, 'showUserProjectsPostman']);

Route::put('/updateProjectPostman', [ProjectController::class, 'updateProjectPostman']);

Route::get('/projects/{projectId}/investors', [InvestmentController::class, 'showInvestors']);

Route::put('/activateOrRejectProject/{id}', [ProjectController::class, 'activateOrRejectProjectPostman']);

Route::get('/showUpdates/{id}', [ProjectController::class, 'showUpdates']);

Route::post('/addUpdate/{projectId}', [ProjectController::class, 'addUpdatesPostman']);

Route::delete('/update/{id}', function ($id) {
    return ProjectUpdate::destroy($id);
});