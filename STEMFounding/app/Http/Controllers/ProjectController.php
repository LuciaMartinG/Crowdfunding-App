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

    function updateStateProject(Request $request){ // $request me permite acceder a los datos de la petición, similar $_POST

        $id = $request->input('id');

        $project = Project::find($id);

        $project->state = $request->input('state');
       

        $project->save();

        return $project;

    }

    function activateOrDeactivateProject (Request $request){ // Función para que el emprendedor solo pueda poner que el estado sea activo o inactivo

        $id = $request->input('id');

        $project = Project::find($id);

        $state = $request->input('state');
        if (!in_array($state, ['activo', 'inactivo'])) {
            return response()->json(['error' => 'Estado no permitido. Solo se permite "activo" o "inactivo".'], 400);
        }

        // Actualizar el estado del proyecto
        $project->state = $state;

        // Guardar los cambios
        $project->save();

        return $project;

    }

}
