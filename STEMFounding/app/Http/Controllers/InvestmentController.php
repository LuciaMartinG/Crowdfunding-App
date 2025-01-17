<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function invest(Request $request)
    {
        $user = auth()->user();
        $project = Project::find($request->project_id);

        // Actualizar el current_investment del proyecto
        $project->current_investment += $request->investment_amount;
        $project->save();

        // Crear la inversión en la tabla investments
        $investment = Investment::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'investment_amount' => $request->investment_amount,
        ]);

        return redirect()->back()->with('success', 'Investment successful!');
    }
}
