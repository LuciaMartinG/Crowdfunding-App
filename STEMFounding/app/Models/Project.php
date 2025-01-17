<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function investments()  {
    return $this->hasMany(Investment::class);
    }

    public function investors(){
        return $this->belongsToMany(User::class, 'investments')
                    ->withPivot('investment_amount')
                    ->withTimestamps();
    }

}
