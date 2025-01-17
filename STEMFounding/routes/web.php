<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\User;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
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
    // Route::get('/project', function () {
    //     return view('projectList', ['projectList' => Project::paginate(10)]);
    // })->middleware('auth');
    
    /*
        Ruta para mostrar la lista de proyectos activos e inactivos,accesible para todos los usuarios.
        Paginación de 10 proyectos por página.
    */
    Route::get('/project', [ProjectController::class, 'showActiveAndInactiveProjects'])->middleware('auth');
    Route::get('/projects/pending', [ProjectController::class, 'showPendingProjects'])->middleware('auth','role:admin');

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
        Solo accesible para usuarios con rol de entrepreneur.
    */
    Route::get('/project/create', function () {
        return view('createProject');
    })->middleware(['auth', 'role:entrepreneur']);

    /*
        Ruta para procesar la creación de un nuevo proyecto.
        Solo accesible para usuarios con rol de entrepreneur.
    */
    Route::post('/project/create', function (Request $request) {
        $project = app(ProjectController::class)->createProject($request);
        return redirect('/project/detail/' . $project->id);
    })->middleware(['auth', 'role:entrepreneur']);

    
    // Ruta para procesar el updateProject

   
    Route::post('/project/update', function (Request $request) {
        $project = app(ProjectController::class)->updateProject($request);
        return redirect('/project/detail/1');
    })->middleware('auth');

    

    /*
        Ruta para desactivar un proyecto.
        Solo accesible para usuarios con rol de admin.
    */
    
    Route::post('/projects/activate-or-deactivate', function (Request $request) {
        $project = app(ProjectController::class)->updateStateProject($request);
        return redirect('/project/detail/' . $project->id);
    });

    /*
        Ruta para desactivar un proyecto.
        Solo accesible para usuarios con rol de emprendedor(proyectos propios).
    */

    Route::post('/projects/user/activate-or-deactivate', function (Request $request) {
        $project = app(ProjectController::class)->updateStateProject($request);
        return redirect('/user/projects');
    });

    // Ruta para actualizar el estado del proyecto, solo accesible para administradores
    Route::post('/projects/{id}/updateState', [ProjectController::class, 'activateOrRejectProject'])
    ->middleware(['auth', 'role:admin']);  // Asegura que el usuario esté autenticado y sea admin

 

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        


    /*
        Ruta para mostrar la lista de usuarios, solo accesible para usuarios autenticados.
        Paginación de 10 proyectos por página.
    */
    Route::get('/user', function () {
        return view('userList', ['userList' => User::paginate(10)]);
    })->middleware('auth');


        /*
        Ruta para mostrar el detalle de usuario específico.
        La ruta recibe el ID del usuario y muestra su información.
    */
    Route::get('/user/detail/{id}', function ($id) {
        $user = User::findOrFail($id); // Encuentra al usuario o lanza un error 404
        $userProjects = Project::where('user_id', $id)->get(); // Recupera los proyectos del usuario
    
        return view('userDetail', [
            'user' => $user,
            'userProjects' => $userProjects,
        ]);
    })->middleware('auth');
    /*
        Ruta para actualizar un rol.
    */

    Route::post('/user/updateRole', function (Request $request) {
        $user = app(UserController::class)->updateRoleUser($request);
        return redirect('/user/detail/' . $user->id);
    })->middleware(['auth', 'role:admin']);
    
     /*
        Ruta para procesar la solicitud de actualización del usuario 
    */

    /*
        Ruta para mostrar el formulario de edición del perfil.
        Solo accesible para usuarios con rol de entrepreneur o investor.
    */
    Route::get('/user/update/{id}', function () {
        $user = auth()->user();  // Obtener el usuario autenticado
        return view('updateUser', ['user' => $user]); //Pasarlo a la vista
    })->middleware('auth'); //SI PONEMOS ENTREPRENEUR E INVESTOR NO FUNCIONA
    
    
    
    // Ruta para procesar el updateUser
    // Solo accesible para usuarios con rol de entrepreneur e investor.
   
    Route::post('/user/update', function (Request $request) {
        $user = app(UserController::class)->updateUser($request);
        return redirect('/user/detail/' . $user->id);
    })->middleware('auth'); //SI PONEMOS ENTREPRENEUR E INVESTOR NO FUNCIONA
    

    // Ruta para actualizar el saldo del usuario
    Route::post('/user/updateBalance', function (Request $request) {
        $user = app(UserController::class)->updateBalance($request);
        return redirect('/user/detail/' . $user->id)->with('success', 'Balance updated successfully!');
    })->middleware('auth');

    //Ruta para banear al usuario//

    Route::post('/user/ban', function (Request $request) {
        $user = app(UserController::class)->toggleBan($request);
        return redirect('/user/detail/' . $user->id)->with('success', 'Banned successfully!');
    })->middleware('auth');

   
    Route::get('/user/projects', [ProjectController::class, 'showUserProjects'])->middleware('auth')->name('user.projects');

