<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function invest(Request $request)
    {
        // Validar que la inversión sea mayor o igual a 10
        $request->validate([
            'investment_amount' => 'required|numeric|min:10',
        ]);
    
        // Obtener al usuario autenticado
        $user = auth()->user();
        
        // Obtener el proyecto
        $project = Project::find($request->project_id);
    
        // Verificar si el proyecto existe
        if (!$project) {
            return redirect()->back()->with('error', 'Project not found.');
        }
    
        // Verificar que el monto de la inversión sea mayor o igual a 10 euros
        if ($request->investment_amount < 10) {
            return redirect()->back()->with('error', 'The minimum investment amount is 10 euros.');
        }
    
        // Actualizar el current_investment del proyecto
        $project->current_investment += $request->investment_amount;
        $project->save();
    
        // Crear la inversión en la tabla investments
        $investment = Investment::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'investment_amount' => $request->investment_amount,
        ]);
    
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->back()->with('success', 'Investment successful!');
    }
    
}
