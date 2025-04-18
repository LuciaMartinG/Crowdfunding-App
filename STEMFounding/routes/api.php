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
use App\Http\Controllers\Api\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); // Ruta para logout (solo accesible si el usuario está autenticado)

Route::get('/project', function () {
    return Project::all();
});

Route::get('/project/{id}', function ($id) {
    return Project::find($id);
});

Route::delete('/project/{id}', function ($id) {
    return Project::destroy($id);
});

Route::middleware('auth:sanctum')->post('/project', [ProjectController::class, 'createProject']);

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getAuthenticatedUser']);

Route::delete('/user/{id}', function ($id) {
    return User::destroy($id);
});


Route::put('/user', [UserController::class, 'updateUser']);

Route::put('/updateUserBalance', [UserController::class, 'updateBalance']);

Route::middleware('auth:sanctum')->get('/userProjects', [ProjectController::class, 'showUserProjectsPostman']);

Route::middleware('auth:sanctum')->put('/updateProject', [ProjectController::class, 'updateProject']);

Route::get('/projects/{projectId}/investors', [InvestmentController::class, 'showInvestors']);

Route::put('/activateOrDeactivateProject/{id}', [ProjectController::class, 'activateOrDeactivateProject']);

Route::get('/showUpdates/{id}', [ProjectController::class, 'showUpdates']);

Route::middleware('auth:sanctum')->post('/addUpdate/{projectId}', [ProjectController::class, 'addUpdates']);

Route::delete('/update/{id}', function ($id) {
    return ProjectUpdate::destroy($id);
});

Route::middleware('auth:sanctum')->put('/update/{id}', [ProjectController::class, 'editUpdate']); // RUTA PARA EDITAR UNA ACTUALIZACIÓN

Route::post('/process-refunds', [InvestmentController::class, 'processRefunds']); //Ruta para procesar reembolsos

Route::middleware('auth:sanctum')->post('/withdraw/{projectId}', [ProjectController::class, 'withdrawFunds']);