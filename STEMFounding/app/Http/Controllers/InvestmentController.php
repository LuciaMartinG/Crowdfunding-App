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

    // Inicializar un mensaje de respuesta
    $message = '';

    // Obtener al usuario autenticado y el proyecto
    $user = auth()->user();
    $project = Project::find($request->project_id);

    // Verificar si el proyecto existe
    if (!$project) {
        $message = 'Project not found.';
    } 
    // Verificar que el monto de la inversión sea mayor o igual a 10 euros
    else if ($request->investment_amount < 10) {
        $message = 'The minimum investment amount is 10 euros.';
    } 
    // Si todo está bien, proceder con la inversión
    else {
        // Actualizar el current_investment del proyecto
        $project->current_investment += $request->investment_amount;
        $project->save();

        // Descontar el balance del usuario
        $user->balance -= $request->investment_amount;
        $user->save();

        // Crear la inversión en la tabla investments
        Investment::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'investment_amount' => $request->investment_amount,
        ]);

        // Establecer el mensaje de éxito
        $message = 'Investment successful!';
    }

    // Redirigir al usuario con el mensaje apropiado (ya sea de error o éxito)
    return redirect()->back()->with($message ? 'success' : 'error', $message);
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
        // Inicializar el mensaje de respuesta
        $message = '';
        $type = 'error'; // Por defecto, el mensaje será de tipo error
    
        // Obtener la inversión
        $investment = Investment::findOrFail($investmentId);
        $user = auth()->user(); // Usuario autenticado
    
        // Verificar que la inversión sea de este usuario
        if ($investment->user_id !== $user->id) {
            $message = 'No puedes retirar la inversión.';
        }
        // Verificar si han pasado más de 24 horas
        else if ($investment->created_at->addHours(24)->isPast()) {
            $message = 'More than 24 hours have passed. You cannot withdraw the investment.';
        }
        // Si todo está bien, proceder con el retiro
        else {
            // Obtener el proyecto relacionado
            $project = $investment->project;
    
            // Actualizar el saldo del proyecto
            $project->current_investment -= $investment->investment_amount; // Reducir la cantidad invertida en el proyecto
            $project->save();
    
            // Eliminar la inversión
            $investment->delete();
    
            // Actualizar el saldo del usuario
            $user->balance += $investment->investment_amount; // Aumentar el saldo del inversor con el monto de la inversión
            $user->save();
    
            // Establecer el mensaje de éxito
            $message = 'Inversión retirada correctamente.';
            $type = 'success'; // Cambiar tipo de mensaje a éxito
        }
    
        // Redirigir a la página de inversiones con el mensaje adecuado
        return redirect()->route('investments.show', ['id' => $investment->project_id])->with($type, $message);
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

public function showInvestorsPostman($projectId)
{
    try {
        // Obtener el proyecto por su ID
        $project = Project::findOrFail($projectId);

        // Obtener todos los inversores que han invertido en este proyecto
        $investors = $project->investments()
                            ->with('user') // Cargar información del usuario (inversor)
                            ->get();


        return $investors;
    } catch (\Exception $e) {
        // Manejo de errores
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve project investors.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    
    
}
