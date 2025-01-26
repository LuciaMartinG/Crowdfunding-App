<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectUpdate;
use Carbon\Carbon;
use App\Models\User;


class ProjectController extends Controller
{
    public function createProject(Request $request)
    {
        // Validación de los datos del proyecto
        $validated = $request->validate([
            'title' => 'required|string|max:255',  // El título es obligatorio, es una cadena y máximo 255 caracteres
            'description' => 'required|string|max:1000',  // La descripción es obligatoria, es una cadena y máximo 1000 caracteres
            'image_url' => 'required|string|max:1000',  // La URL de la imagen es opcional, pero si está presente debe ser una URL válida
            'video_url' => 'required|string|max:1000',  // La URL del video es opcional, pero si está presente debe ser una URL válida
            'min_investment' => 'required|numeric|min:1',  // Mínima inversión es obligatoria
            'max_investment' => 'required|numeric|min:1|gte:min_investment',  // Máxima inversión es obligatoria, debe ser mayor o igual que la mínima inversión
            'limit_date' => 'required|date|after_or_equal:today',  // La fecha límite es obligatoria, debe ser una fecha posterior o igual al día actual
        ]);
    
        // Crear el proyecto con los datos validados
        $project = Project::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'],
            'video_url' => $validated['video_url'],
            'min_investment' => $validated['min_investment'],
            'max_investment' => $validated['max_investment'],
            'limit_date' => $validated['limit_date'],
            'state' => 'pending',  // Para que por defecto se cree un proyecto en estado "pendiente"
            'current_investment' => 0,
        ]);
    
        return $project;
    }

    public function createProjectPostman(Request $request)
    {
            // Validación de los datos del proyecto
            $validated = $request->validate([
                'title' => 'required|string|max:255',  // El título es obligatorio, es una cadena y máximo 255 caracteres
                'description' => 'required|string|max:1000',  // La descripción es obligatoria, es una cadena y máximo 1000 caracteres
                'image_url' => 'required|string|max:1000',  // La URL de la imagen es opcional, pero si está presente debe ser una URL válida
                'video_url' => 'required|string|max:1000',  // La URL del video es opcional, pero si está presente debe ser una URL válida
                'min_investment' => 'required|numeric|min:1',  // Mínima inversión es obligatoria
                'max_investment' => 'required|numeric|min:1|gte:min_investment',  // Máxima inversión es obligatoria, debe ser mayor o igual que la mínima inversión
                'limit_date' => 'required|date|after_or_equal:today',  // La fecha límite es obligatoria, debe ser una fecha posterior o igual al día actual
            ]);
        // Crear el proyecto con los datos validados
        $project = Project::create([
            'user_id' => 1,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'],
            'video_url' => $validated['video_url'],
            'min_investment' => $validated['min_investment'],
            'max_investment' => $validated['max_investment'],
            'limit_date' => $validated['limit_date'],
            'state' => 'pending',  // Para que por defecto se cree un proyecto en estado "pendiente"
            'current_investment' => 0,
        ]);
    
        return $project;
    }
    public function updateProject(Request $request)
    {
        // Inicializar las variables
        $message = '';
        $type = 'error'; // Por defecto, el tipo de mensaje será error
        
        // Obtener el ID del proyecto desde el request
        $id = $request->input('id');
        
        // Buscar el proyecto en la base de datos
        $project = Project::find($id);
        
        // Verificar si el proyecto existe
        if (!$project) {
            $message = 'Project not found';
        }
        // Verificar si el usuario autenticado es el dueño del proyecto y que esté activo
        else if (auth()->user()->id !== $project->user_id || $project->state !== 'active') {
            $message = 'You are not authorized to update this project';
        }
        // Si todo está bien, proceder con la actualización
        else {
            // Actualizar los datos del proyecto
            $project->title = $request->input('title', $project->title);
            $project->description = $request->input('description', $project->description);
            $project->image_url = $request->input('image_url', $project->image_url);
            $project->video_url = $request->input('video_url', $project->video_url);
            $project->min_investment = $request->input('min_investment', $project->min_investment);
            $project->max_investment = $request->input('max_investment', $project->max_investment);
            $project->limit_date = $request->input('limit_date', $project->limit_date);
            $project->state = $request->input('state', $project->state);
    
            // Guardar los cambios en el proyecto
            $project->save();
    
            // Establecer el mensaje de éxito
            $message = 'Project updated successfully!';
            $type = 'success'; // Cambiar tipo de mensaje a éxito
        }
    
        // Redirigir con el mensaje adecuado
        return redirect()->back()->with($type, $message);
    }

    public function updateProjectPostman(Request $request)
{
    // Inicializar las variables
    $message = '';
    $type = 'error'; // Por defecto, el tipo de mensaje será error

    // Obtener el ID del proyecto desde el request
    $id = $request->input('id');

    // Buscar el proyecto en la base de datos
    $project = Project::find($id);

    // Verificar si el proyecto existe
    if (!$project) {
        $message = 'Project not found';
    } 
    // Verificar si el proyecto pertenece al usuario con ID 22 y está activo
    else if ($project->user_id !== 22 || $project->state !== 'active') {
        $message = 'You are not authorized to update this project';
    } 
    // Si todo está bien, proceder con la actualización
    else {
        // Actualizar los datos del proyecto
        $project->title = $request->input('title', $project->title);
        $project->description = $request->input('description', $project->description);
        $project->image_url = $request->input('image_url', $project->image_url);
        $project->video_url = $request->input('video_url', $project->video_url);
        $project->min_investment = $request->input('min_investment', $project->min_investment);
        $project->max_investment = $request->input('max_investment', $project->max_investment);
        $project->limit_date = $request->input('limit_date', $project->limit_date);
        $project->state = $request->input('state', $project->state);

        // Guardar los cambios en el proyecto
        $project->save();

        // Establecer el mensaje de éxito
        $message = 'Project updated successfully!';
        $type = 'success'; // Cambiar tipo de mensaje a éxito
    }

    // Retornar respuesta en formato JSON para Postman
    return response()->json([
        'type' => $type,
        'message' => $message,
        'project' => $type === 'success' ? $project : null // Si fue exitoso, retorna el proyecto actualizado
    ]);
}

    
    
   public function updateStateProject(Request $request) {
        // Obtener el ID y el estado del proyecto desde la solicitud
        $id = $request->input('id');
        $state = $request->input('state');
    
        // Buscar el proyecto por ID
        $project = Project::find($id);
       
        $project->state = $request->input('state');
        // Actualizar el estado del proyecto
        $project->state = $state;
    
        // Guardar los cambios
        $project->save();

        return $project;
    
       
    }

    public function activateOrRejectProject(Request $request, $id)
{
    // Inicializar las variables
    $message = '';
    $type = 'error'; // Por defecto, el tipo de mensaje será error

    // Obtener el proyecto por su ID
    $project = Project::find($id);

    // Verificar si el proyecto fue encontrado
    if (!$project) {
        $message = 'El proyecto no existe.';
    } else {
        // Obtener el valor de 'state' del formulario
        $state = $request->input('state');

        // Si se va a activar el proyecto
        if ($state === 'active') {
            // Verificar que el emprendedor no tenga más de 2 proyectos activos
            $activeProjectsCount = Project::where('user_id', $project->user_id)
                                          ->where('state', 'active')
                                          ->count();

            if ($activeProjectsCount >= 2) {
                $message = 'The user already has 2 active projects.';
            } else {
                // Cambiar el estado del proyecto a 'active'
                $project->state = 'active';
                $message = 'Proyecto actualizado con éxito.';
                $type = 'success'; // Cambiar tipo de mensaje a éxito
            }
        }
        // Si se va a rechazar el proyecto
        else if ($state === 'rejected') {
            $project->state = 'rejected';
            $message = 'Proyecto actualizado con éxito.';
            $type = 'success'; // Cambiar tipo de mensaje a éxito
        }

        // Guardar los cambios en el proyecto
        $project->save();
    }

    // Redirigir con el mensaje adecuado
    return redirect()->back()->with($type, $message);
}

public function activateOrRejectProjectPostman(Request $request, $id)
{
    // Inicializar las variables
    $message = '';
    $type = 'error'; // Por defecto, el tipo de mensaje será error

    // Obtener el proyecto por su ID
    $project = Project::find($id);

    // Verificar si el proyecto fue encontrado
    if (!$project) {
        $message = 'El proyecto no existe.';
    } else {
        // Obtener el valor de 'state' del formulario
        $state = $request->input('state'); 

        // Si se va a activar el proyecto
        if ($state === 'active') {
            // Verificar que el emprendedor no tenga más de 2 proyectos activos
            $activeProjectsCount = Project::where('user_id', $project->user_id)
                                          ->where('state', 'active')
                                          ->count();

            if ($activeProjectsCount >= 2) {
                $message = 'The user already has 2 active projects.';
            } else {
                // Cambiar el estado del proyecto a 'active'
                $project->state = 'active';
                $message = 'Proyecto actualizado con éxito.';
                $type = 'success'; // Cambiar tipo de mensaje a éxito
            }
        }
        // Si se va a rechazar el proyecto
        else if ($state === 'rejected') {
            $project->state = 'rejected';
            $message = 'Proyecto actualizado con éxito.';
            $type = 'success'; // Cambiar tipo de mensaje a éxito
        }

        // Guardar los cambios en el proyecto
        $project->save();
    }

    // Redirigir con el mensaje adecuado
    return $project;
}
    
    
 
public function showActiveAndInactiveProjects()
{
    $projectList = Project::whereIn('state', ['active', 'inactive'])->paginate(10); // 10 proyectos por página
    $now = Carbon::now();

    // foreach ($projectList as $project) {
    //     // Desactivar proyectos que alcanzaron la financiación máxima
    //     if ($project->current_investment >= $project->max_investment) {
    //         $project->state = 'inactive';
    //         $project->save();
    //     }

    //     // Desactivar proyectos cuya fecha límite expiró sin alcanzar la financiación mínima
    //     if ($project->deadline < $now && $project->current_investment < $project->min_investment) {
    //         $project->state = 'inactive';
    //         $project->save();
    //     }
    // }

    return view('projectList', ['projectList' => $projectList]);
}



    public function showPendingProjects()
    {
        // Obtener todos los proyectos cuyo estado sea 'active' o 'inactive'
        $pendingProjectList = Project::whereIn('state', ['pending'])->paginate(10); // 10 proyectos por página

        // Retornar la vista con los proyectos
        return view('pendingProjectList', ['pendingProjectList' => $pendingProjectList]);
    }

    // app/Http/Controllers/ProjectController.php



    public function showUserProjects()
    {
        // Obtener el usuario logueado
        $user = Auth::user();

        // Obtener los proyectos asociados con ese usuario
        $projects = $user->projects;  // Esta es la relación 'hasMany' definida en el modelo User

        // Pasar los proyectos a la vista
        return view('userProjects', ['projects' => $projects]);
    }

    public function showUserProjectsPostman($id)
    {
        // Obtener el usuario logueado
        $user = User::find($id);

        // Obtener los proyectos asociados con ese usuario
        $projects = $user->projects;  // Esta es la relación 'hasMany' definida en el modelo User

        // Pasar los proyectos a la vista
        return response()->json($projects);
    }
    
    public function showUpdates($id)
    {
        // Obtener el proyecto por ID
        $project = Project::find($id);
    
        // Obtener las actualizaciones del proyecto
        $updates = $project->updates; // Esto obtiene todas las actualizaciones relacionadas con el proyecto
    
        return $updates;
    }

    public function addUpdates(Request $request, $projectId)
{
    $request->validate([
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'image_url' => 'nullable|url',
    ]);

    $message = '';
    $type = 'error'; // Por defecto, error
    $project = Project::find($projectId);
    $update = null; // Inicializamos la variable de actualización

    if (!$project) {
        $message = 'Project not found.';
    } else if ($project->state !== 'active') {
        $message = 'Updates can only be added to active projects.';
    } else if ($project->user_id !== auth()->user()->id) {
        $message = 'Only the project owner can add updates.';
    } else {
        $update = ProjectUpdate::create([
            'project_id' => $project->id,
            'user_id' => auth()->user()->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
        ]);

        $message = 'Update added successfully';
        $type = 'success';
    }

    return (object)[
        'type' => $type,
        'message' => $message,
        'update' => $type === 'success' ? $update : null,
    ];
}

public function addUpdatesPostman(Request $request, $projectId)
{
    $request->validate([
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'image_url' => 'nullable|url',
    ]);

    $message = '';
    $type = 'error'; // Por defecto, error
    $project = Project::find($projectId);
    $update = null; // Inicializamos la variable de actualización

    if (!$project) {
        $message = 'Project not found.';
    } else if ($project->state !== 'active') {
        $message = 'Updates can only be added to active projects.';
    } else if ($project->user_id !== 22) {
        $message = 'Only the project owner can add updates.';
    } else {
        $update = ProjectUpdate::create([
            'project_id' => $project->id,
            'user_id' => 22,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
        ]);

        $message = 'Update added successfully';
        $type = 'success';
    }

    return (object)[
        'type' => $type,
        'message' => $message,
        'update' => $type === 'success' ? $update : null,
    ];
}
    
public function editUpdate(Request $request, $updateId)
{
    $message = '';
    $type = 'error'; // Por defecto, error
    $update = ProjectUpdate::find($updateId);

    // Verificar que el update existe
    if (!$update) {
        $message = 'Update not found.';
    } 
    // Verificar si el usuario tiene permiso para actualizar este update
    else if ($update->user_id !== auth()->user()->id || $update->project->user_id !== auth()->user()->id) {
        $message = 'You do not have permission to update this update.';
    } 
    else {
        // Validar la solicitud
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url',
        ]);

        // Actualizar la información del update
        $update->update([
            'title' => $request->input('title', $update->title),
            'description' => $request->input('description', $update->description),
            'image_url' => $request->input('image_url', $update->image_url),
        ]);

        $message = 'Update updated successfully.';
        $type = 'success'; // Cambiar el tipo a éxito
    }

    // Devolver el mensaje y el tipo en un objeto, junto con los detalles de la actualización si fue exitosa
    return (object)[
        'type' => $type,
        'message' => $message,
        'update' => $type === 'success' ? $update : null,
    ];
}


    public function editUpdatePostman(Request $request, $updateId)
    {
        $message = '';
        $type = 'error'; // Por defecto, error
        $update = ProjectUpdate::find($updateId);

        if (!$update) {
            $message = 'Update not found.';
        } else {
            // Validar los datos de la solicitud
            $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image_url' => 'nullable|url',
            ]);

            // Actualizar la actualización
            $update->update([
                'title' => $request->input('title', $update->title),
                'description' => $request->input('description', $update->description),
                'image_url' => $request->input('image_url', $update->image_url),
            ]);

            $message = 'Update updated successfully.';
            $type = 'success'; // Cambiar tipo a éxito
        }

        // Devolver el mensaje y el tipo en un objeto, junto con los detalles de la actualización si fue exitosa
        return (object)[
            'type' => $type,
            'message' => $message,
            'update' => $type === 'success' ? $update : null,
        ];
    }


    public function showProjects(Request $request)
    {
        // Obtener el valor de 'state' desde la solicitud GET
        $state = $request->input('state');

        // Crear la consulta inicial
        $query = Project::query();

        // Si se pasa un estado, filtrar los proyectos por el estado
        if ($state) {
            $query->where('state', $state);
        }

        // Filtrar para que los proyectos con estado 'pending' solo se muestren a administradores
        if (Auth::user() && Auth::user()->role !== 'admin') {
            $query->where('state', '!=', 'pending'); // Si el usuario no es admin, no mostrar proyectos 'pending'
        }

        // Obtener los proyectos con paginación (10 proyectos por página)
        $projectList = $query->paginate(10);

        // Retornar la vista con los proyectos filtrados
        return view('projectList', ['projectList' => $projectList]);
    }

        
    public function showProjectDetails($id)
    {
        // Buscar el proyecto por ID
        $project = Project::find($id);
        
        // Verificar si el proyecto existe
        if (!$project) {
            abort(404, 'Project not found');
        }

        // Verificar si el estado del proyecto es 'pending' y si el usuario no es admin
        if ($project->state === 'pending' && Auth::user()?->role !== 'admin') {
            abort(403, 'You are not authorized to view this project.');
        }

        // Si todo está bien, retornar la vista con los detalles del proyecto
        return view('projectDetail', ['project' => $project]);
    }


    }