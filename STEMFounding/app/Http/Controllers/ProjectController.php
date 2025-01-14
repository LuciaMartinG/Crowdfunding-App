<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function createProject(Request $request)
    {
        // Crear película con los datos recibidos
        $project = Project::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
            'video_url' => $request->input('video_url'),
            'min_investment' => $request->input('min_investment'),
            'max_investment' => $request->input('max_investment'),
            'limit_date' => $request->input('limit_date'),
            'state' => 'pending', //para que por defecto se cree un proyecto en estado "pendiente"
            'current_investment' => 0,
        ]);

        return $project;
    }

    function updateProject(Request $request){ // $request me permite acceder a los datos de la petición, similar $_POST

        $id = $request->input('id');

        $project = Project::find($id);

        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->image_url = $request->input('image_url');
        $project->video_url = $request->input('video_url');
        $project->min_investment = $request->input('min_investment');
        $project->max_investment = $request->input('max_investment');
        $project->limit_date = $request->input('limit_date');
        $project->state = $request->input('state');
        $project->current_investment = $request->input('current_investment');

        $project->save();

        return $project;


    }

    function updateStateProject(Request $request) {
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
    
        // Obtener el valor de 'state' del formulario
        $state = $request->input('state');
    
        // Verificar si el estado es válido y actualizar el estado del proyecto
        if ($state === 'active') {
            $project->state = 'active';
        } elseif ($state === 'rejected') {
            $project->state = 'rejected';
        }
    
        // Guardar los cambios en el proyecto
        $project->save();
    
        // Redirigir de nuevo a la lista de proyectos pendientes con un mensaje de éxito
        return redirect('/projects/pending')->with('success', 'Proyecto actualizado con éxito.');
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

    }


