<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',      
        'user_id',         
        'description',     
        'image_url',       
        'title',         
    ];

    // Relación con el Proyecto (un proyecto puede tener muchas actualizaciones)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relación con el Usuario (un usuario puede hacer muchas actualizaciones)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
