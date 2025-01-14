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

    
    function activateProject(Request $request) {
        $id = $request->input('id');
      
        $project = Project::find($id);
        $state= $project->state;

        if($state == 'active'){
            $state = 'inactive';
            }else{
                $state = 'active';
    }
        $project->state = $state;
        $project->save();
        return $project;
    

}

    function activateOrRejectProject(Request $request) {
        $state = $request->input('state');

        if($state == 'active'){
            $state = 'inactive';
            }else{
                $state = 'activo';
    }
    $state->save();
    return $state;


}

public function showActiveAndInactiveProjects()
{
    // Obtener todos los proyectos cuyo estado sea 'active' o 'inactive'
    $projectList = Project::whereIn('state', ['active', 'inactive'])->paginate(10); // 10 proyectos por página

    // Retornar la vista con los proyectos
    return view('projectList', ['projectList' => $projectList]);
}



}
