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
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
            'video_url' => $request->input('video_url'),
            'min_investment' => $request->input('min_investment'),
            'max_investment' => $request->input('max_investment'),
            'limit_date' => $request->input('limit_date'),
            'state' => $request->input('state'),
            'current_investment' => $request->input('current_investment'),
        ]);

        return $project;
    }
}
