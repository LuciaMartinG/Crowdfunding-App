<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'video_url',
        'min_investment',
        'max_investment',
        'limit_date',
        'state',
        'current_investment'
    ];

    // protected $attributes = [
    //     'state' => 'pending',
    // ];
}
