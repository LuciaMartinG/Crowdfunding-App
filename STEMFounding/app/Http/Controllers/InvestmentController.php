<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;


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

        $user->balance -= $request->investment_amount;
        $user->save();
    
        // Crear la inversión en la tabla investments
        $investment = Investment::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'investment_amount' => $request->investment_amount,
        ]);
    
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->back()->with('success', 'Investment successful!');
    }

    public function projectInvestments()
    {
        $user = Auth::user();

        // Obtener los proyectos en los que el usuario ha invertido
        $projects = Project::whereHas('investments', function($query) use ($user) {
        $query->where('user_id', $user->id); // Filtra las inversiones que pertenecen al usuario
        })->paginate(10); // Paginación de proyectos
        
        return view('myInvestments', ['projects' => $projects]);
    }

    public function showInvestments($id)
    {
        // Obtener el proyecto por ID
        $project = Project::findOrFail($id);

        // Obtener las inversiones del usuario autenticado para ese proyecto
        $investments = Investment::where('project_id', $project->id)
            ->where('user_id', Auth::id())
            ->get();

        // Pasar el proyecto y las inversiones a la vista
        return view('projectInvestments',['investments' => $investments]);
    }

    public function withdrawInvestment($investmentId)
    {
        // Obtener la inversión
        $investment = Investment::findOrFail($investmentId);
        $user = auth()->user(); // Usuario autenticado
    
        // Verificar que la inversión sea de este usuario
        if ($investment->user_id !== $user->id) {
            return redirect()->route('investments.show', ['id' => $investment->project_id])->with('success', 'No puedes retirar la inversión.');

        }
    
        if ($investment->created_at->addHours(24)->isPast()) {
            return redirect()->route('investments.show', ['id' => $investment->project_id])
                ->with('error', 'More than 24 hours have passed. You cannot withdraw the investment.');
        }
    
        // Obtener el proyecto relacionado
        $project = $investment->project;
    
        // Actualizar el saldo del proyecto
        $project->current_investment -= $investment->investment_amount; // Reducir la cantidad invertida en el proyecto
        $project->save();
    
        // Eliminar la inversión
        $investment->delete();
    
       
        $user->balance += $investment->investment_amount; // Aumentar el saldo del inversor con el monto de la inversión
        $user->save();
    
        // Redirigir a la página de inversiones con un mensaje de éxito
        return redirect()->route('investments.show', ['id' => $investment->project_id])->with('success', 'Inversión retirada correctamente.');

    }

    public function showInvestors($projectId)
{
    // Obtener el proyecto por su ID
    $project = Project::findOrFail($projectId);

    // Obtener todos los inversores que han invertido en este proyecto
    $investors = $project->investments()
                        ->with('user')  // Obtener la información del usuario (inversor)
                        ->get();  // Obtener todas las inversiones

    // Mapear los inversores y sus cantidades invertidas
    $investorsWithAmount = $investors->map(function ($investment) {
        return [
            'user' => $investment->user->name,  // Nombre del inversor
            'investment_amount' => $investment->investment_amount,  // Cantidad invertida
        ];
    });

    // Pasar los inversores y sus cantidades a la vista
    return view('projectInvestors', [
        'project' => $project,
        'investorsWithAmount' => $investorsWithAmount,
    ]);
}

    
    
}
