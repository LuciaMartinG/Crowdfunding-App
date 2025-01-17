<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project; 
use App\Models\User; 

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'investment_amount',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
