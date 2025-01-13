<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\User;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ============================== Rutas públicas (usuarios autenticados) ==============================

/*
    Ruta para mostrar la lista de proyectos, solo accesible para usuarios autenticados.
    Paginación de 10 proyectos por página.
*/
Route::get('/project', function () {
    return view('projectList', ['projectList' => Project::paginate(10)]);
})->middleware('auth');

Route::get('/', function () {
    return redirect('/project');
});

/*
    Ruta para mostrar el detalle de proyecto específico.
    La ruta recibe el ID del proyecto y muestra su información.
*/
Route::get('/project/detail/{id}', function ($id) {
    return view('projectDetail', ['project' => Project::find($id)]);
})->middleware('auth');

Route::get('/project/delete/{id}', function ($id) {
    Project::destroy($id);
    return redirect('/project');
});

/*
    Ruta para mostrar el formulario de creación de un nuevo proyecto.
    Solo accesible para usuarios con rol de entrepeneur.
*/
Route::get('/project/create', function () {
    return view('createProject');
})->middleware(['auth', 'role:entrepeneur']);

/*
    Ruta para procesar la creación de un nuevo proyecto.
    Solo accesible para usuarios con rol de entrepeneur.
*/
Route::post('/project/create', function (Request $request) {
    $project = app(ProjectController::class)->createProject($request);
    return redirect('/project/detail/' . $project->id);
})->middleware(['auth', 'role:entrepeneur']);


