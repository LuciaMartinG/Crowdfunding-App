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
        $request->validate([
            'investment_amount' => 'required|numeric|min:10',
        ]);
    
        $status = 'error';
        $message = '';
    
        $user = auth()->user();
        $project = Project::find($request->project_id);
    
        if (!$project) {
            $message = 'Project not found.';
        } elseif ($request->investment_amount < 10) {
            $message = 'The minimum investment amount is 10 euros.';
        } elseif ($project->state === 'inactive' || $project->state === 'pending') {
            $message = 'This project is not available.';
        } else {
            $project->current_investment += $request->investment_amount;
            $project->save();
    
            $user->balance -= $request->investment_amount;
            $user->save();
    
            Investment::create([
                'user_id' => $user->id,
                'project_id' => $request->project_id,
                'investment_amount' => $request->investment_amount,
            ]);
    
            $status = 'success';
            $message = 'Investment successful!';
        }
    
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
    
   
    public function projectInvestments()
    {
        $user = Auth::user();
    
        // Obtener los proyectos en los que el usuario ha invertido
        $projects = Project::whereHas('investments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->paginate(10); 
        
        return $projects;
    }
    

    public function showInvestments($id)
    {
      
        $project = Project::findOrFail($id);
    
        $investments = Investment::where('project_id', $project->id)
            ->where('user_id', Auth::id())
            ->get();
    
        return [
            'project' => $project,
            'investments' => $investments
        ];
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
    

    // public function showInvestors($projectId)
    // {
    //     // Obtener el proyecto por su ID
    //     $project = Project::findOrFail($projectId);
    
    //     // Obtener todos los inversores que han invertido en este proyecto
    //     $investors = $project->investments()
    //                         ->with('user')  // Obtener la información del usuario (inversor)
    //                         ->get();  // Obtener todas las inversiones
    
    //     // Mapear los inversores y sus cantidades invertidas en objetos PHP normales
    //     $investorsWithAmount = $investors->map(function ($investment) {
    //         return (object)[
    //             'user' => $investment->user->name,  // Nombre del inversor
    //             'investment_amount' => $investment->investment_amount,  // Cantidad invertida
    //         ];
    //     });
    
    //     // Devolver un objeto PHP normal con los datos (sin vista ni JSON)
    //     return response()->json([
    //         'project' => $project,  // Proyecto con sus datos
    //         'investors' => $investorsWithAmount,  // Inversores y sus inversiones
    //     ]);
    // }
    
    public function showInvestors($projectId)
{
    // Inicializamos la variable de respuesta en caso de error
    $response = (object)[
        'success' => false,
        'message' => 'Failed to retrieve project investors.',
        'error' => null,
    ];

    try {
        // Obtener el proyecto por su ID
        $project = Project::findOrFail($projectId);

        // Obtener todos los inversores que han invertido en este proyecto
        $investors = $project->investments()
                            ->with('user') // Cargar la información del usuario (inversor)
                            ->get();

        // Crear una colección para almacenar los inversores y sus cantidades invertidas
        $investorsWithAmount = collect();

        // Recorrer las inversiones y agregar los datos al arreglo
        foreach ($investors as $investment) {
            $investorsWithAmount->push((object)[
                'user' => $investment->user->name,  // Nombre del inversor
                'investment_amount' => $investment->investment_amount,  // Cantidad invertida
            ]);
        }

        // Si todo fue exitoso, modificamos la respuesta
        $response->success = true;
        $response->project = $project;  // Datos del proyecto
        $response->investors = $investorsWithAmount;  // Inversores y sus inversiones
    } catch (\Exception $e) {
        // Si ocurre un error, almacenamos el error en la respuesta
        $response->error = $e->getMessage();
    }

    // Retornamos la respuesta (ya sea exitosa o con error) en una sola vez
    return $response;
}

    
    
    
}
