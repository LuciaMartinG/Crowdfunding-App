<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectUpdate;


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
    public function updateProject(Request $request)
    {
        // Obtener el ID del proyecto desde el request
        $id = $request->input('id');
    
        // Buscar el proyecto en la base de datos
        $project = Project::find($id);
    
        // Verificar si el proyecto existe
        if (!$project) {
            return redirect()->back()->with('error', 'Project not found');
        }
    
        // Verificar si el usuario autenticado es el dueño del proyecto y que esté activo
        if (auth()->user()->id !== $project->user_id || $project->state !== 'active') {
            return redirect()->back()->with('error', 'You are not authorized to update this project');
        }
    
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
    
        // Crear una nueva actualización para el proyecto
        ProjectUpdate::create([
            'project_id' => $project->id,
            'user_id' => auth()->user()->id,
            'title' => $request->input('title', $project->title),
            'description' => $request->input('description', $project->description),
            'image_url' => $request->input('image_url', $project->image_url),
        ]);
    
        return $project;
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
    // Obtener el proyecto por su ID
    $project = Project::find($id);

    // Verificar si el proyecto fue encontrado
    if (!$project) {
        return redirect()->back()->with('error', 'El proyecto no existe.');
    }

    // Obtener el valor de 'state' del formulario
    $state = $request->input('state');

    // Verificar si el proyecto pertenece al administrador
    if (Auth::user()->role == 'admin') {
        // Si se va a activar el proyecto
        if ($state === 'active') {
            // Verificar que el emprendedor no tenga más de 2 proyectos activos
            $activeProjectsCount = Project::where('user_id', $project->user_id)
                                          ->where('state', 'active')
                                          ->count();

            if ($activeProjectsCount >= 2) {
                return redirect()->back()->with('error', 'El emprendedor ya tiene 2 proyectos activos.');
            }
            // Si no tiene más de 2 proyectos activos, cambiar el estado del proyecto a 'active'
            $project->state = 'active';
        }

        // Si se va a rechazar el proyecto
        elseif ($state === 'rejected') {
            $project->state = 'rejected';
        }

        // Guardar los cambios en el proyecto
        $project->save();

        // Redirigir de nuevo a la lista de proyectos pendientes con un mensaje de éxito
        return redirect('/projects/pending')->with('success', 'Proyecto actualizado con éxito.');
    }

    return redirect()->back()->with('error', 'No tienes permisos para cambiar el estado de este proyecto.');
}

    
 

    public function showActiveAndInactiveProjects()
    {
        // Obtener todos los proyectos cuyo estado sea 'active' o 'inactive'
        $projectList = Project::whereIn('state', ['active', 'inactive'])->paginate(10); // 10 proyectos por página

        // Retornar la vista con los proyectos
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

    // Método para mostrar el detalle del proyecto
    public function showUpdates($id)
    {
        // Obtener el proyecto por ID
        $project = Project::find($id);
    
        // Obtener las actualizaciones del proyecto
        $updates = $project->updates; // Esto obtiene todas las actualizaciones relacionadas con el proyecto
    
        // Retornar la vista con el proyecto y las actualizaciones
        return view('projectDetail', [
            'project' => $project,
            'updates' => $updates,
        ]);
    }




}