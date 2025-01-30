<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUpdate;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvestmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('projectList', ['projectList' => Project::whereIn('state', ['active', 'inactive'])->paginate(10)]);
});

  

    // ============================== Rutas públicas (usuarios autenticados) ==============================

    /*
        Ruta para mostrar la lista de proyectos activos e inactivos,accesible para todos los usuarios.
        Paginación de 10 proyectos por página.
    */
    Route::get('/project', [ProjectController::class, 'showActiveAndInactiveProjects']);
    Route::get('/projects/pending', [ProjectController::class, 'showPendingProjects'])->middleware('auth','role:admin');

    // Route::get('/', function () {
    //     return redirect('/project');
    // });

    /*
        Ruta para mostrar el detalle de proyecto específico.
        La ruta recibe el ID del proyecto y muestra su información.
    */
    // Route::get('/project/detail/{id}', function ($id) {
    //     return view('projectDetail', ['project' => Project::find($id)]);
    // });

    Route::get('/project/detail/{id}', [ProjectController::class, 'showProjectDetails'])
    ->name('projects.show');
    
    Route::get('/projects', [ProjectController::class, 'showProjects'])->name('projects.list'); 

    Route::get('/project/delete/{id}', function ($id) {
        $status = 'error';
        $message = 'An error occurred while deleting the project.';
    
        if (Project::destroy($id)) {
            $status = 'success';
            $message = 'Project deleted successfully!';
        }
    
        return redirect('/project')->with($status, $message);
    })->middleware(['auth', 'role:admin']);
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
    })->middleware('auth','role:entrepreneur');

    

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

    // Ruta para activar o RECHAZAR un proyecto, solo accesible para administradores
    Route::post('/projects/{id}/updateState', [ProjectController::class, 'activateOrRejectProject'])
    ->middleware(['auth', 'role:admin']);  // Asegura que el usuario esté autenticado y sea admin  ->>>>>>> comprobar en REACT

 
    /*
        Ruta para mostrar la lista de usuarios, solo accesible para el admin.
        Paginación de 10 proyectos por página.
    */
    Route::get('/user', [UserController::class, 'listUsers'])
    ->middleware('auth','role:admin')
    ->name('users.list');

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
    });
    /*
        Ruta para actualizar un rol.
    */

    Route::post('/user/updateRole', function (Request $request) {
        $user = app(UserController::class)->updateRoleUser($request);
        return redirect('/user/detail/' . $user->id);
    })->middleware(['auth', 'role:admin']);
    

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
    })->middleware('auth','role:admin');

   
    Route::get('/user/projects', [ProjectController::class, 'showUserProjects'])->middleware('auth')->name('user.projects');
    
   // Ruta para añadir actualizaciones
   Route::post('/projects/{projectId}/comments', function (Request $request, $projectId) {
    $response = app(ProjectController::class)->addUpdates($request, $projectId);
    return redirect()->route('projects.show', $projectId)
    ->with($response->type, $response->message);
    })->middleware('auth','role:entrepreneur')  // Verifica que el usuario esté autenticado
    ->name('projects.comments.add');



    // Ruta para eliminar actualizaciones
    Route::get('/comment/delete/{id}', function ($id) {
    $update = ProjectUpdate::find($id);
    ProjectUpdate::destroy($id);
    $projectId = $update->project_id;
    return redirect()->route('projects.show', ['id' => $projectId])->with('success', 'Update deleted successfully.');
    })->middleware('auth','role:entrepreneur')
    ->name('projects.comments.delete');
        

    // Ruta para actualizar actualizaciones (usando PUT)
    Route::put('/projects/edit/{updateId}', function (Request $request, $updateId) {
    $response = app(ProjectController::class)->editUpdate($request, $updateId);
    return redirect()
        ->route('projects.show', ['id' => $response->update ? $response->update->project_id : null])  // Redirige al proyecto
        ->with($response->type, $response->message);  // Con el mensaje de éxito o error
    })->middleware('auth', 'role:entrepreneur') // Verifica que el usuario esté autenticado y sea emprendedor
     ->name('projects.comments.edit');

    //Ruta para invertir
    Route::post('/invest', function (Request $request) {
        $response = app(InvestmentController::class)->invest($request);
        return redirect()->back()->with($response['status'], $response['message']);
    })->middleware('auth','role:investor');
    
    //Ruta para ver mis inversiones(investor)
    Route::get('/investments/my-projects', function () {
        $projects = app(InvestmentController::class)->projectInvestments();
        return view('myInvestments', ['projects' => $projects]);
    })->middleware(['auth', 'role:investor']);

   // Ruta para ver las inversiones de un proyecto específico
   Route::get('/project/investments/{id}', function ($id) {
    $response = app(InvestmentController::class)->showInvestments($id);
    return view('projectInvestments', [
        'project' => $response['project'],
        'investments' => $response['investments']
    ]);
    })->middleware(['auth', 'role:investor'])
    ->name('investments.show');

    // Ruta para borrar las inversiones de un proyecto específico
    Route::delete('/investments/withdraw/{investment}', [InvestmentController::class, 'withdrawInvestment'])
    ->middleware(['auth', 'role:investor'])
    ->name('investments.withdraw');

    Route::get('/project/{id}/investors', function ($id) {
        $response = app(InvestmentController::class)->showInvestors($id);
        return view('projectInvestors', [
            'project' => $response->project,
            'investorsWithAmount' => $response->investors,
        ]);
    })->middleware(['auth', 'role:entrepreneur'])
      ->name('projects.investors');
    

    // Ruta para retirar fondos del proyecto, solo accesible si el usuario está autenticado
      Route::middleware('auth','role:entrepreneur')->post('/projects/{projectId}/withdraw', [ProjectController::class, 'withdrawFunds'])->name('projects.withdraw');

      Route::post('/projects/{projectId}/withdraw-funds', function ($projectId) {
        $response = app(ProjectController::class)->withdrawFunds($projectId);
    
        return redirect()->route('projects.myProjects')->with($response['status'], $response['message']);
    })->middleware('auth', 'role:entrepreneur')->name('projects.withdrawFunds');
    